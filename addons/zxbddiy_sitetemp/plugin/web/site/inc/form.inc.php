<?php 
	global $_W,$_GPC;

	//$token = '4_pZbSW99marW0ED-RYXTyxh_wmRHobgxTrnShYCDhlOPaP24NpodwowfDk6hRvow76hkhLWg2ocs8iqoJH718HYV_noKV6ZT1vliSwo4t504rH_LAJ8VE4lna1zItmifuPahekJy7uCJR_txLDVVgAJAUMM';
	//$url = 'https://api.weixin.qq.com/card/membercard/activate/geturl?access_token='.$token;
	//$cid = 'psfrpwiB9FKriHu0b9qV2XjCbgXg';

	//encrypt_card_id=8g1lInvGbuQgLHbfWMiikGlbzKesWylb6Ita0k3sdw7OoIF66zJKZMlyw7PM3dPd&outer_str=123&biz=MzI3MDExMzk2OQ%3D%3D#wechat_redirect
	//$res = Util::httpPost( $url,json_encode( array('card_id'=>$cid,'outer_str'=>123) ) );

	//var_dump( $res );

	/*$uniacccount = WeAccount::create( 10 );
	var_dump( $uniacccount->getAccessToken() );*/

	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_form');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	//批量删除
	if(checksubmit('deletealladmin')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_admin');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	if(checksubmit('readall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('请先选择要审核的数据','','success');

		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_form',array('isread'=>1),array('id'=>$v,'uniacid'=>$_W['uniacid']));
		}
		itoast('操作完成','','success');
	}	


	if($_GPC['op'] == 'wait' || $_GPC['op'] == 'scaned'){

		$where['isread'] = 0;
		$where['uniacid'] = $_W['uniacid'];
		if( $_GPC['op'] == 'scaned' ) $where['isread'] = 1;

		$num = 10;

		if( $_GPC['down'] == 1 ){
			$num = 9999999;
			$_GPC['page'] = 1;

			$where['createtime>'] = strtotime( $_GPC['time']['start'] );
			$andstr = ' AND createtime <= '. strtotime( $_GPC['time']['end'] ) ;
		}


		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_form',$where,$_GPC['page'],$num,' `id` DESC ',false,true,' * ',$andstr);
		$list = $info[0];
		$pager = $info[1];
		
		if( $_GPC['down'] == 1 ){
			downLoadOrder($list);
		}

		if( !empty( $list ) ) {
			foreach ( $list as &$v ) {
				$v['data'] = iunserializer( $v['data'] );
						
				$v['content'] = '';
				foreach ((array)$v['data'] as $kk => $vv) {
					if( is_string( $vv ) ) {
						$v['content'] .= $kk.'：'.$vv.'，';
					}elseif( is_array( $vv ) ) {
						if( $vv['type'] == 'img' ){
							$instr = '';
							foreach ($vv['list'] as $kkk => $vvv) {
								$img = tomedia( $vvv );
								$instr .= '<a href="'.$img.'" target="_blank"><img src="'.$img.'" style="width:40px;height:40px;display:inline-block;margin:0 10px 10px 0" ></a>';
							}
							$v['content'] .= $kk.'：'.$instr.'，';
						}else{
							$instr = '';
							foreach ($vv as $vvv) {
								$instr .= $vvv.'-';
							}
							$instr = trim( $instr,'-' );
							$v['content'] .= $kk.'：'.$instr.'，';
						}	

					}
					
				}
			}
		}

	}
	


	if($_GPC['op'] == 'deleteform'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_form');
		if($res) itoast('删除成功','','success');
	}


	/*if( $_GPC['op'] == 'admin' ){
		
		Util::deleteCache('scene','all');
		
		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid']),1,9999,' `id` DESC ',false,false);
		$list = $info[0];
		if( !empty( $list ) ) {

			foreach ( $list as  &$v ) {
				$user = pdo_get('mc_mapping_fans',array('openid'=>$v['openid']),array('tag'));
				$tag = iunserializer( base64_decode( $user['tag'] ) );
				$v['headimgurl'] = $tag['headimgurl'];
				$v['nickname'] = $tag['nickname'];
				
			}
		}
	}

	if($_GPC['op'] == 'deleteadmin'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_admin');
		if($res) message('删除成功',referer(),'success');
	}*/


	//下载表格
	function downLoadOrder($list){
		
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF".$html; //添加BOM
		/* 输出表头 */		
		$html .= '数据id' . "\t,";
		$html .= '数据内容' . "\t,";		
		$html .= '提交时间' . "\t,";
		$html .= "\n";
			
 		foreach((array)$list as $k => $v){	

 			$time = date('Y-m-d H:i:s',$v['createtime']);

 			$data = iunserializer( $v['data'] );
			$content = '';
			foreach ($data as $kk => $vv) {
				if( is_string( $vv ) ) {
					$content .= $kk.'：'.$vv."，";
				}elseif( is_array( $vv ) ) {
					if( $vv['type'] == 'img' ){
						$content .= $kk.'：(图片表单，请在页面内查看)';
					}else{
						$instr = '';
						foreach ($vv as $vvv) {
							$instr .= $vvv.'-';
						}
						$instr = trim( $instr,'-' );
						$content .= $kk.'：'.$instr."，";
					}
				}
			}
 			
			$html .= $v['id'] . "\t,";
			$html .= $content . "\t,";					
			$html .= $time . "\t,";
			
			$html .= "\n"; 
			
		}
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=表单数据.csv");
		echo $html;
		exit;
		
	}

	function input_csv($handle) { 
		$out = array (); 
		$n = 0; 
		while ($data = fgetcsv($handle, 10000)) { 
			$num = count($data); 
			for ($i = 0; $i < $num; $i++) { 
				$out[$n][$i] = $data[$i]; 
			} 
			$n++; 
		} 
		return $out; 
	}


	include $this->pTemplate('form');