<?php
/**
 * 企业官网DIY模块定义
 *
 * @author 众惠科技
 * @url http://bbs.we7.cc/
 */
global $_W;
defined('IN_IA') or exit('Access Denied');
define('ST_ROOT',IA_ROOT.'/addons/zxbddiy_sitetemp/');
define('ST_URL',$_W['siteroot'].'addons/zxbddiy_sitetemp/');
define('MODULE','zxbddiy_sitetemp');
require_once(ST_ROOT.'class/autoload.php');

class zxbddiy_sitetempModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
    	

		//设置模板id
		$tid = $this->setTempid($settings,'ordermid','订单状态通知','AT0202',array(11,3,4,19),array('商品信息','订单金额','订单状态','通知时间'));
		$settings['ordermid'] = $tid;

		$tid = $this->setTempid($settings,'appointmid','预约成功通知','AT0104',array(6,14,103,83),array('预约项目','服务描述','预约状态','下单时间'));
		$settings['appointmid'] = $tid;
	  	
	  	pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('zxbddiy_sitetemp_set')." (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
		  `params` mediumtext,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


	  	if(!pdo_fieldexists('zxbddiy_sitetemp_good', 'vurl')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_good')." ADD `vurl` varchar(1000) DEFAULT NULL;");
	  	}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_good', 'isaddex')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_good')." ADD `isaddex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0叠加运费 1不叠加';");
	  	}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_article', 'type')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_article')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0自己添加 1url';");
	  	}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_article', 'url')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_article')." ADD `url` varchar(500) DEFAULT NULL;");
	  	}

		if(!pdo_fieldexists('zxbddiy_sitetemp_page', 'isinit')) {
		    pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_page')." ADD `isinit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1添加页面 0无';");
		}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_article', 'vurl')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_article')." ADD `vurl` varchar(600) DEFAULT NULL;");
	  	}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_product', 'vurl')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_product')." ADD `vurl` varchar(600) DEFAULT NULL;");
	  	}

	  	if(!pdo_fieldexists('zxbddiy_sitetemp_article', 'falseview')) {
	    	pdo_query("ALTER TABLE ".tablename('zxbddiy_sitetemp_article')." ADD `falseview` int(11) unsigned NOT NULL DEFAULT '0';");
	  	}


		header('Location: ./index.php?c=site&a=entry&do=temp&p=site&op=my&m=zxbddiy_sitetemp');die;
		
		
		/////////////////


		if(checksubmit('submit')) {
			$_GPC = Util::trimWithArray($_GPC);

			load()->func('file');
            $r = mkdirs(ST_ROOT . '/cert/'.$_W['uniacid']);
			if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['rootca'])) {
                $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['rootca']));
                $r = $r && $ret;
            }			
			if(!$r) {
                message('证书保存失败, 请保证 /addons/zxbddiy_sitetemp/cert/ 目录可写，如果无法解决请使用上传工具将证书文件上传至'.ST_ROOT . '/cert/'.$_W['uniacid'].'目录下');
            }


			$dat = array(
				'frompass' => $_GPC['frompass'],
				'mail' => $_GPC['mail'],
				'shopname' => $_GPC['shopname'],
				'shoptel' => $_GPC['shoptel'],
				'shopaddress' => $_GPC['shopaddress'],
				'kefutype' => intval( $_GPC['kefutype'] ),
				'kefutel' => $_GPC['kefutel'],
				'kefuurl' => $_GPC['kefuurl'],
				'kefuimg' => $_GPC['kefuimg'],
				'shoplat' => $_GPC['shoplat'],
				'shoplng' => $_GPC['shoplng'],
            );


			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		
		$auth = model_auth::authList();

		if( $auth['isshop'] == 1 ){
			
		}
		// 管理员
		$admin = pdo_getall('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid']));

		if( !empty( $admin ) ) {
			foreach ( $admin as &$v ) {
				$v['user'] = model_user::getSingleUser($v['openid']);
			}
		}




		include $this->template('web/setting');
	}




	public function getMidList($token,$start,$num){

		$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token='.$token;
		$res = Util::httpPost( $url , json_encode( array('offset'=>$start,'count'=>$num) ) );
		return $res;
	}

	public function setTempid($settings,$sname,$tname,$tid,$keylist,$verifylist){
		global $_W;
			load() -> model('account');
			$account = WeAccount::create( $_W['acid'] );
			$access_token = $account->getAccessToken();	

			if( !empty( $access_token ) ) {
				$res = $this->getMidList($access_token,0,20);
				$res = json_decode($res,true);

								
				if( !empty( $res ) && $res['errmsg'] == 'ok' && $res['errcode'] == '0' && !empty( $res['list'] ) ) {

					foreach ($res['list'] as $v ) {
						$isin = 1;
						foreach ( $verifylist as $vv ) {
							if( strpos($v['example'], $vv) === false ){
								$isin = 0;
							}
						}
						if( $v['title'] == $tname && $isin == 1 ) {
							$template_id = $v['template_id'];
							$isset = 1;
							break;
						}
					}

					// 查余下的5个
					if( !$isset && count( $res['list'] ) >= 20 ){
						$res = $this->getMidList($access_token,20,5);

						$res = json_decode($res,true);
						if( !empty( $res ) && $res['errmsg'] == 'ok' && $res['errcode'] == '0' && !empty( $res['list'] ) ) {

							foreach ($res['list'] as $v ) {
								$isin = 1;
								foreach ( $verifylist as $vv ) {
									if( strpos($v['example'], $vv) === false ){
										$isin = 0;
									}
								}
								if( $v['title'] == $tname && $isin == 1 ) {
									$template_id = $v['template_id'];
									$isset = 1;
									break;
								}
							}
						}
					}
				}
				
				if( !$isset ) { // 不存在 加入
					$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token='.$access_token;
					$res = Util::httpPost( $url , json_encode( array('id'=>$tid,'keyword_id_list'=>$keylist) ) );
					
					$res = json_decode( $res , true );
					if( !empty( $res['template_id'] ) ) {
						$template_id = $res['template_id'];
					}
				}

				if( !empty( $template_id ) ) {
					$settings[$sname] = $template_id;
					$this->saveSettings($settings);
				}
				return $template_id;
			}
		
	}




}