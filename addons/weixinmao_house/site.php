<?php
/**
 * 企业小程序模块微站定义
 *
 * @author weixinmao
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Weixinmao_houseModuleSite extends WeModuleSite {
  

	public function doWebIntro() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

	   if ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'name'=>$_GPC['name'],
					'city'=>$_GPC['city'],
					'address'=>$_GPC['address'],
					'tel'=>$_GPC['tel'],
					'qq'=>$_GPC['qq'],
					'email'=>$_GPC['email'],
					'logo'=>$_GPC['logo'],
					'indexadv'=>$_GPC['indexadv'],
					'fxbanner'=>$_GPC['fxbanner'],
					'name'=>$_GPC['name'],
					'opentime'=>$_GPC['opentime'],
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
                    'content' => ihtmlspecialchars($_GPC['content']),
                    'ischeck'=>$_GPC['ischeck'],
                    'isagent'=>$_GPC['isagent'],
                    'ischeck2'=>$_GPC['ischeck2'],
                    'ispay'=>$_GPC['ispay'],
                    'maincolor'=>$_GPC['maincolor'],
                    'moban'=>$_GPC['moban'],
                    'rate1'=>$_GPC['rate1'],
                    'rate2'=>$_GPC['rate2'],
                    'isoldhouse'=>$_GPC['isoldhouse'],
                    'islethouse'=>$_GPC['islethouse'],
                    'isbuyhouse'=>$_GPC['isbuyhouse'],
                    'issalehouse'=>$_GPC['issalehouse'],
                    'isagentoldhouse'=>$_GPC['isagentoldhouse'],
                    'isagentlethouse'=>$_GPC['isagentlethouse'],
                    'isgetuser'=>$_GPC['isgetuser'],
                    'newlimit'=>$_GPC['newlimit'],
                    'oldlimit'=>$_GPC['oldlimit'],
                    'letlimit'=>$_GPC['letlimit'],
                    'createtime' => TIMESTAMP,
                );
        
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_intro', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_intro', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('intro', array('op' => 'display')), 'success');
            }
            if (empty($shop)) {
                $shop['displayorder'] = 0;
                $shop['enabled'] = 1;
            }
        }elseif($operation == 'display'){
   	
		$intro = pdo_fetch("select * from " . tablename('weixinmao_house_intro') . " where uniacid=:uniacid limit 1", array(":uniacid" => $_W['uniacid']));

		include $this->template('intro');
		}
	}


	public function doWebStore() {
		//这个操作被定义用来呈现 管理中心导航菜单
			global $_W,$_GPC;
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        $sql = 'SELECT * FROM ' . tablename('weixinmao_house_storecate') . ' WHERE `weid` = :uniacid ORDER BY `displayorder` DESC';
		$storecate = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));


	   if ($operation == 'post') {
		   
		   
             $id = intval($_GPC['id']);
			 if (!empty($id)) {
					$intro = pdo_fetch("select * from " . tablename('weixinmao_house_store') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
			}
            if (checksubmit('submit')) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'name'=>$_GPC['name'],
					'address'=>$_GPC['address'],
					'tel'=>$_GPC['tel'],
					'qq'=>$_GPC['qq'],
					'email'=>$_GPC['email'],
					'logo'=>$_GPC['logo'],
					'name'=>$_GPC['name'],
					'cateid'=>$_GPC['cateid'],
					'opentime'=>$_GPC['opentime'],
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
                    'content' => htmlspecialchars_decode($_GPC['content']),
                    'createtime' => TIMESTAMP,
					'sort'=>$_GPC['sort'],
					'isdefault'=>$_GPC['isdefault'],
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_store', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_store', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('store', array('op' => 'display')), 'success');
            }
           
        }elseif($operation == 'display'){
   	
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_store') .$condition ;

			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_store') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_store') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_store', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('store');
		
	}




public function doWebPaylist() {
		global $_W, $_GPC;
	    load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_paylist') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
				
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'money' => $_GPC['money'],
				    'days'=>$_GPC['days'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_paylist', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_paylist', $data);
					$id = pdo_insertid();
				}
				message('更新区域成功！', $this->createWebUrl('paylist', array('op' => 'display')), 'success');
			}

			$toplist = pdo_fetch("select * from " . tablename('weixinmao_house_paylist') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_paylist') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('paylist', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_paylist', array('id' => $id));
			message('删除成功！', $this->createWebUrl('paylist', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('paylist', TEMPLATE_INCLUDEPATH, true);
	}


	public function doWebCate() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('weixinmao_house_category', array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
				}
				message('分类排序更新成功！', $this->createWebUrl('cate', array('op' => 'display')), 'success');
			}
			$children = array();
			
			$category = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_category') . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('weixinmao_house_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'model'=>intval($_GPC['model']),
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb']
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('weixinmao_house_category', $data, array('id' => $id, 'weid' => $_W['uniacid']));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('weixinmao_house_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('cate', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('weixinmao_house_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('weixinmao_house_category', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('cate', array('op' => 'display')), 'success');
		}
		
	}
	public function doWebContent() {
		//这个操作被定义用来呈现 管理中心导航菜单
				global $_GPC, $_W;
		load()->func('tpl');

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_category') . ' WHERE `weid` = :weid ORDER BY `parentid`, `displayorder` DESC';
		
		$category = pdo_fetchall($sql, array(':weid' => $_W['uniacid']), 'id');
		
	
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';


		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_content') . " WHERE id = :id", array(':id' => $id));
					
						
			}
			
			$pid = $_GPC['category']['parentid'];
			
			$sid = 0;

			
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'pid'=>$pid,
					'sid'=>$sid,
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_content', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_content', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('content', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
			echo $_GPC['keyword'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_content') .$condition ;

			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_content') .$condition.' ORDER BY  `sort`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}

			if($list)
				{
			foreach($list as $k=>$v)
			{
			
				$parent_info = pdo_fetch("SELECT name  FROM " . tablename('weixinmao_house_category') . " WHERE id = :id", array(':id' => $v['pid']));
				$list[$k]['parent_catename'] = $parent_info['name'];
			
				$children_info = pdo_fetch("SELECT name  FROM " . tablename('weixinmao_house_category') . " WHERE id = :id", array(':id' => $v['sid']));
				$list[$k]['children_catename'] = $children_info['name'];

			    

				
			}
					}
			
		
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_content') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_content', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('goods');
		
	}
	
		public function doWebUserinfo() {
		//这个操作被定义用来呈现 管理中心导航菜单
				global $_GPC, $_W;
		load()->func('tpl');


		
	
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
	

		
			
		} elseif ($operation == 'display') {
			
			echo $_GPC['keyword'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_userinfo') .$condition ;

			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_userinfo') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
			foreach($list as $k=>$v)
			{
				$list[$k]['avatarUrl'] = tomedia($v['avatarUrl']);
			}
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_userinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_content', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('userinfo');
		
	}

		public function doWebComplain() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_content') . " WHERE id = :id", array(':id' => $id));
					
						
			}
			
			$pid = $_GPC['category']['parentid'];
			
			$sid = 0;

			
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'pid'=>$pid,
					'sid'=>$sid,
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_content', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_content', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('content', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			

			if($_GPC['status'] == 1)
					{

						          pdo_update('weixinmao_house_complain', array('status'=>1), array('id' => $_GPC['id']));

					}
			


		//	   $list  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weixinmao_house_comment') ." WHERE   uniacid=:weid  ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
	
			

			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weixinmao_house_complain') ." WHERE   uniacid=:weid  ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_complain') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
			foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
		
				$agentinfo = pdo_get('weixinmao_house_agent',array('id'=>$v['agentid']));
				$list[$k]['agentname'] = $agentinfo['name'];
				$list[$k]['houseplan'] = $houseplan['title'];

				
				
			}
		}
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_complain') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_complain', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('complain');
		
	}





 	public function doWebComment() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_content') . " WHERE id = :id", array(':id' => $id));
					
						
			}
			
			$pid = $_GPC['category']['parentid'];
			
			$sid = 0;

			
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'pid'=>$pid,
					'sid'=>$sid,
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_content', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_content', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('content', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
		
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			


	
			

			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weixinmao_house_comment') ." WHERE   uniacid=:weid  ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_comment') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
			foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				$housemsg = pdo_get('weixinmao_house_housemsg',array('id'=>$v['houseid']));
				$houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$housemsg['houseid']));
				$list[$k]['title'] = $houseinfo['housename'];
				$houseplan = pdo_get('weixinmao_house_house',array('id'=>$housemsg['toplistid']));
				$list[$k]['houseplan'] = $houseplan['title'];

				
				
			}
		}
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_comment') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_comment', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('comment');
		
	}
	

	public function doWebHousemsg() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_content') . " WHERE id = :id", array(':id' => $id));
					
						
			}
			
			$pid = $_GPC['category']['parentid'];
			
			$sid = 0;

			
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'pid'=>$pid,
					'sid'=>$sid,
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_content', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_content', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('content', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
	

			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weixinmao_house_housemsg') ." WHERE   uniacid=:weid  ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_housemsg') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
			foreach($list  as $k=>$v)
			{
				
				
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
					$orderinfo = pdo_get('weixinmao_house_order',array('pid'=>$v['id'],'type'=>'housemsg','uniacid' => $_W['uniacid']));


					if($orderinfo)
					{
					if($orderinfo['paid'] == 1)
						{
								$list[$k]['statusStr'] = '已支付【'.$orderinfo['title'].'】';
								$list[$k]['paymoney'] = $orderinfo['money'];

						}else{

								$list[$k]['statusStr'] = '未支付【'.$orderinfo['title'].'】';
								$list[$k]['paymoney'] = $orderinfo['money'];


						}

					}else{

						$list[$k]['statusStr'] = '未发起支付';
						$list[$k]['paymoney'] = '未发起支付';
						
					}
					
					
				if($v['type'] == 'newhouse')
				{

					$houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['houseid']));
					$list[$k]['title'] = $houseinfo['housename'];
					$houseplan = pdo_get('weixinmao_house_house',array('id'=>$v['toplistid']));

					$list[$k]['houseplan'] = $houseplan['title'];

				}elseif($v['type'] == 'lethouse')
					{
						$houseinfo = pdo_get('weixinmao_house_lethouseinfo',array('id'=>$v['houseid']));
				
                       	$list[$k]['title'] = $houseinfo['title'];

						$list[$k]['houseplan'] = '出租房';

					}else{

						$houseinfo = pdo_get('weixinmao_house_oldhouseinfo',array('id'=>$v['houseid']));
				
                       	$list[$k]['title'] = $houseinfo['title'];

						$list[$k]['houseplan'] = '二手房';

					}

				
				
			}
		}
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_housemsg') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_housemsg', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('housemsg');
		
	}


	
	
		public function doWebSalelist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$speciallist = array();
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
		
		
	
		} elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `tel` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_saleinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_saleinfo') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}

			if($list)
			{
				if($arealist)
				{
				foreach($arealist as $k=>$v)
						{
								$areainfo[$v['id']] = $v['name'];			
						}
				}
				$housetypeinfo= array(1=>'出售',2=>'出租',3=>'求购',4=>'求租');
				
				foreach($list as $k=>$v)
					{
						$list[$k]['areaname'] =  $areainfo[$v['houseareaid']];
						$list[$k]['type'] =  $housetypeinfo[$v['type']];
					}
				
				
			}
			
			
			
			
		} elseif($operation == 'done'){
			
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_saleinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			 pdo_update('weixinmao_house_saleinfo', array('ischeck'=>1), array('id' => $id));

			message('操作成功！', referer(), 'success');
			
		
		}elseif($operation == 'detail'){
			
			$id = intval($_GPC['id']);
			$saleinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_saleinfo') . " WHERE id = :id", array(':id' => $id));

			if (empty($saleinfo)) {
				message('抱歉，不存在或是已经被删除！');
			}
				$housetypeinfo= array(1=>'出售',2=>'出租',3=>'求购',4=>'求租');

			$saleinfo['type'] =  $housetypeinfo[$saleinfo['type']];
           
           $piclist1 = unserialize($saleinfo['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){

							foreach($piclist1 as $p){
								if($p)
								$piclist[] = tomedia($p);
							}
						}
		
		
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_saleinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_saleinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
		include $this->template('salelist');
		
	}
	
	
	
	
	public function doWebNewhouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
			$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
	
		$speciallist = array();
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_houseinfo') . " WHERE id = :id", array(':id' => $id));

				 		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
						$sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
                       $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

						$speciallist = explode(',',$item['productspecial']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
						
			}
			
		
			if (checksubmit('submit')) {
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
                    'bid'=>$_GPC['bid'],
					'housename'=>$_GPC['housename'],
					'companyname'=>$_GPC['companyname'],
					'housetype'=>$_GPC['housetype'],
					'houseprice'=>$_GPC['houseprice'],
					'houseareaid'=>$_GPC['houseareaid'],
					'houseaddress'=>$_GPC['houseaddress'],
					'housesaleaddress'=>$_GPC['housesaleaddress'],
					'houserate'=>$_GPC['houserate'],
					'housegreenrate'=>$_GPC['housegreenrate'],
					'housecovered'=>$_GPC['housecovered'],
					'buildarea'=>$_GPC['buildarea'],
					'opensaletime'=>$_GPC['opensaletime'],
					'staytime'=>$_GPC['staytime'],
					'productspecial'=>implode(',',$_GPC['productspecial']),
					'houseschool'=>$_GPC['houseschool'],
					'housebus'=>$_GPC['housebus'],
					'housestatus'=>$_GPC['housestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'tel'=>$_GPC['tel'],
					'fxmoney'=>$_GPC['fxmoney'],
                   'createtime' => TIMESTAMP,
				   'lng'=>$_GPC['location']['lng'],
				   'lat'=>$_GPC['location']['lat'],
                );
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_houseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_houseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('newhouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif ($operation == 'getcity') {
			$cityid = $_GPC['cityid'];
          $condition = ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ';
      		  $params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid);
			
			
			
			$sql = 'SELECT id,name FROM ' . tablename('weixinmao_house_area') .$condition ;
        
          $list = pdo_fetchall($sql, $params);
          
          echo json_encode(array('data'=>$list));
          
          exit;
			
		}elseif ($operation == 'getbuild') {
			$houseareaid = $_GPC['houseareaid'];
          $condition = ' WHERE `uniacid` = :uniacid AND `aid`=:aid ';
      		  $params = array(':uniacid' => $_W['uniacid'],':aid'=>$houseareaid);
			
			
			
			$sql = 'SELECT id,name FROM ' . tablename('weixinmao_house_buildarea') .$condition ;
        
          $list = pdo_fetchall($sql, $params);
          
          echo json_encode(array('data'=>$list));
          
          exit;
			
		} elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `housename` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_houseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_houseinfo') .$condition.$sort.'    LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}

			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}
				
				
			}
			
			
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_houseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_houseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
		include $this->template('newhouse');
		
	}


	public function doWebBusinesshouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

	
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_businesshouseinfo') . " WHERE id = :id", array(':id' => $id));
				 		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					    $speciallist = explode(',',$item['special']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				$perprice = round($_GPC['saleprice']*10000/$_GPC['area']);
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'saleprice'=>$_GPC['saleprice'],
					'perprice'=>$perprice,
					'housestyle'=>$_GPC['housestyle'],
					'housetype'=>$_GPC['housetype'],
					'houseareaid'=>$_GPC['houseareaid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_businesshouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_businesshouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('businesshouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_businesshouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_businesshouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_businesshouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_oldhouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('businesshouse');
		
	}
	
	
	public function doWebOldhouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

	
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_oldhouseinfo') . " WHERE id = :id", array(':id' => $id));
				 		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					   $sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
					    $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

					    $speciallist = explode(',',$item['special']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'money'=>$_GPC['money'],
					'dmoney'=>$_GPC['dmoney'],
					'saleprice'=>$_GPC['saleprice'],
					'perprice'=>$_GPC['perprice'],
                  'fxmoney'=>$_GPC['fxmoney'],
					'housestyle'=>$_GPC['housestyle'],
					'housetype'=>$_GPC['housetype'],
					'houseareaid'=>$_GPC['houseareaid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
					'bid'=>$_GPC['bid'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_oldhouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_oldhouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('oldhouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif($operation == 'done'){
			
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_oldhouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			 pdo_update('weixinmao_house_oldhouseinfo', array('ischeck'=>1), array('id' => $id));

			message('操作成功！', referer(), 'success');
			
		
		}elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_oldhouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
			
			
		} elseif ($operation == 'displaymember') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
		
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =1 AND uid>0 AND ischeck =1 ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_oldhouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];						
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
		
			
			
		} elseif ($operation == 'displaycheck') {
			
			//echo $_GPC['keyword'];
		$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =1 AND uid>0 AND ischeck =0';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_oldhouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));
						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
		
			
			
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_oldhouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_oldhouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('oldhouse');
		
	}
	
	
	public function doWebOldsalehouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

	
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_oldsalehouseinfo') . " WHERE id = :id", array(':id' => $id));
				 		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					   $sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
					    $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

					    $speciallist = explode(',',$item['special']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'money'=>$_GPC['money'],
					'dmoney'=>$_GPC['dmoney'],
					'saleprice'=>$_GPC['saleprice'],
					'perprice'=>$_GPC['perprice'],
					'housestyle'=>$_GPC['housestyle'],
					'housetype'=>$_GPC['housetype'],
					'houseareaid'=>$_GPC['houseareaid'],
					'roomid'=>$_GPC['roomid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
					'bid'=>$_GPC['bid'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_oldsalehouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_oldsalehouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('oldsalehouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_oldsalehouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_oldsalehouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_oldsalehouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_oldsalehouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('oldsalehouse');
		
	}


		public function doWebOldpayhouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

	
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_oldpayhouseinfo') . " WHERE id = :id", array(':id' => $id));
				 		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					   $sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
					    $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

					    $speciallist = explode(',',$item['special']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'money'=>$_GPC['money'],
					'dmoney'=>$_GPC['dmoney'],
					'saleprice'=>$_GPC['saleprice'],
					'perprice'=>$_GPC['perprice'],
					'housestyle'=>$_GPC['housestyle'],
					'housetype'=>$_GPC['housetype'],
					'houseareaid'=>$_GPC['houseareaid'],
					'roomid'=>$_GPC['roomid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
					'bid'=>$_GPC['bid'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_oldpayhouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_oldpayhouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('oldpayhouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_oldpayhouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_oldpayhouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));

						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					}	
			}
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_oldpayhouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_oldpayhouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('oldpayhouse');
		
	}
    

    public function doWebLetbusinesshouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		 $labellist = array();
		 $speciallist = array();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_letbusinesshouseinfo') . " WHERE id = :id", array(':id' => $id));

				 				$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					    

					     $sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
					    $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

					    $speciallist = explode(',',$item['special']);
						 $labellist = explode(',',$item['houselabel']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'price'=>$_GPC['price'],
					'money'=>$_GPC['money'],
					'dmoney'=>$_GPC['dmoney'],
					'roomid'=>$_GPC['roomid'],
					'roomtype'=>$_GPC['roomtype'],
					'housetype'=>$_GPC['housetype'],
					'letway'=>$_GPC['letway'],
					'payway'=>$_GPC['payway'],
					'houselabel'=>implode(',',$_GPC['houselabel']),
					'houseareaid'=>$_GPC['houseareaid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
					'bid'=>$_GPC['bid'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_letbusinesshouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_letbusinesshouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('letbusinesshouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';

			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_letbusinesshouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_letbusinesshouseinfo') .$condition.$sort.' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				$letwayinfo = array(1=>'整租',2=>'合租');
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));
						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					    $list[$k]['letwayname'] = $letwayinfo[$v['letway']];
					}
				
			}
			
		}  elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_letbusinesshouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_letbusinesshouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('letbusinesshouse');
		
	}
	
   


	
	public function doWebLethouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		 $labellist = array();
		 $speciallist = array();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
		
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_lethouseinfo') . " WHERE id = :id", array(':id' => $id));

				 				$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid ORDER BY `sort` DESC';
						$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$item['cityid']));
					    

					     $sql = 'SELECT * FROM ' . tablename('weixinmao_house_buildarea') . ' WHERE `uniacid` = :uniacid AND `aid`=:aid ORDER BY `sort` DESC';
					    $buildarealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':aid'=>$item['houseareaid']));

					    $speciallist = explode(',',$item['special']);
						 $labellist = explode(',',$item['houselabel']);
						$piclist1 = unserialize($item['thumb_url']);
						$piclist = array();
						if(is_array($piclist1)){
							foreach($piclist1 as $p){
								$piclist[] = is_array($p)?$p['attachment']:$p;
							}
						}
			}
			
		
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
			//	exit;
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'price'=>$_GPC['price'],
					'money'=>$_GPC['money'],
                  'fxmoney'=>$_GPC['fxmoney'],
					'dmoney'=>$_GPC['dmoney'],
					'roomid'=>$_GPC['roomid'],
					'roomtype'=>$_GPC['roomtype'],
					'housetype'=>$_GPC['housetype'],
					'letway'=>$_GPC['letway'],
					'payway'=>$_GPC['payway'],
					'houselabel'=>implode(',',$_GPC['houselabel']),
					'houseareaid'=>$_GPC['houseareaid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'source'=>$_GPC['source'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>implode(',',$_GPC['special']),
					'lng'=>$_GPC['location']['lng'],
					'lat'=>$_GPC['location']['lat'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'salestatus'=>$_GPC['salestatus'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'thumb_url'=>$thumb_data['thumb_url'],
					'video'=>$_GPC['video'],
					'isrecommand'=>$_GPC['isrecommand'],
					'status'=>$_GPC['status'],
					'bid'=>$_GPC['bid'],
                   'createtime' => TIMESTAMP,
                ); 
              // print_r($data);
			  // exit;
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_lethouseinfo', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_lethouseinfo', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('lethouse', array('op' => 'display')), 'success');
            }
			
			
			
			
		}elseif($operation == 'done'){
			
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_lethouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			 pdo_update('weixinmao_house_lethouseinfo', array('ischeck'=>1), array('id' => $id));

			message('操作成功！', referer(), 'success');
			
		
		} elseif ($operation == 'display') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =0';

			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_lethouseinfo') .$condition.$sort.' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				$letwayinfo = array(1=>'整租',2=>'合租');
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));
						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					    $list[$k]['letwayname'] = $letwayinfo[$v['letway']];
					}
				
			}
			
		}elseif ($operation == 'displaymember') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =1 AND uid>0 AND ischeck =1 ';

			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_lethouseinfo') .$condition.$sort.' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
				
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
				$letwayinfo = array(1=>'整租',2=>'合租');
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));
						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					    $list[$k]['letwayname'] = $letwayinfo[$v['letway']];
					}
				
			}
			
		}elseif ($operation == 'displaycheck') {
			
			//echo $_GPC['keyword'];
			$sort ='  ORDER BY  sort DESC , createtime DESC  ';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid  AND ispub =1 AND uid>0 AND ischeck =0 ';

			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;
		
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_lethouseinfo') .$condition.$sort.'  LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				
				$pager = pagination($total, $pindex, $psize);
			}
			if($list)
			{
			
				$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
			    $letwayinfo = array(1=>'整租',2=>'合租');
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));
						$area_info = pdo_get('weixinmao_house_area',array('id'=>$v['houseareaid']));
						$list[$k]['cityname'] =  $city_info['name'];
						$list[$k]['areaname'] =  $area_info['name'];
						$list[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
					    $list[$k]['letwayname'] = $letwayinfo[$v['letway']];
					}
				
			}
			
		}  elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_lethouseinfo') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_lethouseinfo', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}
	
		include $this->template('lethouse');
		
	}
	
	
	
	
	
	
	
	
	public function doWebCase() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$teamlist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
	
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_case') . " WHERE id = :id", array(':id' => $id));
							
			}
			
		
			if (checksubmit('submit')) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'teamid'=>$_GPC['teamid'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'isrecommand'=>$_GPC['isrecommand'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_case', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_case', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('case', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
		//	echo $_GPC['keyword'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_case') .$condition ;
		//	echo $sql;
			
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_case') .$condition.' ORDER BY  `sort`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{
							$house_info = pdo_fetch("SELECT housename  FROM " . tablename('weixinmao_house_houseinfo') . " WHERE id = :id", array(':id' => $v['teamid']));

							$list[$k]['housetitle'] = $house_info['housename'];
						}
				$pager = pagination($total, $pindex, $psize);
			}

			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_case') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_case', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('case');
		
	}
	
	
	
	public function doWebhouse() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$teamlist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
	
		
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 
				 		$item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_house') . " WHERE id = :id", array(':id' => $id));
						
			}
			
		
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
					'teamid'=>$_GPC['teamid'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
					'isrecommand'=>$_GPC['isrecommand'],
					'money'=>$_GPC['money'],
					'dmoney'=>$_GPC['dmoney'],
                    'createtime' => TIMESTAMP,
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_house', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_house', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('house', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
		
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_house') .$condition ;
		
			
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_house') .$condition.' ORDER BY  `sort`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{
							$house_info = pdo_fetch("SELECT housename  FROM " . tablename('weixinmao_house_houseinfo') . " WHERE id = :id", array(':id' => $v['teamid']));

							$list[$k]['housetitle'] = $house_info['housename'];
						}
				$pager = pagination($total, $pindex, $psize);
			}

			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_house') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

			pdo_delete('weixinmao_house_house', array('id' => $id));

			message('删除成功！', referer(), 'success');
		} 
		include $this->template('house');
		
	}
	
	
	
	
	
	
	
	

	public function doWebAdv() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'toway'=>$_GPC['toway'],
					'appid'=>$_GPC['appid']
				);
				if (!empty($id)) {
					pdo_update('weixinmao_house_adv', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('weixinmao_house_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}
	


		public function doWebStorecate() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_storecate') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('weixinmao_house_storecate', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_storecate', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('storecate', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('weixinmao_house_storecate') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_storecate') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('storecate', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_storecate', array('id' => $id));
			message('删除成功！', $this->createWebUrl('storecate', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('storecate', TEMPLATE_INCLUDEPATH, true);
	}
	

	public function doWebNav() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_nav') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'cateid'=>$_GPC['cateid'],
					'innerurl'=>$_GPC['innerurl'],
					'appid'=>$_GPC['appid']
				);
				if (!empty($id)) {
					pdo_update('weixinmao_house_nav', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_nav', $data);
					$id = pdo_insertid();
				}
				message('更新导航成功！', $this->createWebUrl('nav', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('weixinmao_house_nav') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_nav') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，导航不存在或是已经被删除！', $this->createWebUrl('nav', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_nav', array('id' => $id));
			message('导航删除成功！', $this->createWebUrl('nav', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('nav', TEMPLATE_INCLUDEPATH, true);
	}






	public function doWebMessage() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_message') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY createtime DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'companyname' => $_GPC['companyname'],
					'createtime' => TIMESTAMP
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_message', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_message', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('message', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('weixinmao_house_message') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_message') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('message', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_message', array('id' => $id));
			message('删除成功！', $this->createWebUrl('message', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('message', TEMPLATE_INCLUDEPATH, true);
	}
	
	


public function doWebCity() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_city') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'firstname' => $_GPC['firstname'],
					'sort' => $_GPC['displayorder'],
					'enabled'=>1,
					'ison'=>$_GPC['ison'],
					'ishot'=>$_GPC['ishot'],
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_city', $data, array('id' => $id));


				} else {
					pdo_insert('weixinmao_house_city', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('city', array('op' => 'display')), 'success');
			}
			$area = pdo_fetch("select * from " . tablename('weixinmao_house_city') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$area = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_city') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($area)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('city', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_city', array('id' => $id));
			message('删除成功！', $this->createWebUrl('city', array('op' => 'display')), 'success');
		} elseif ($operation == 'donedate') {
				$cityinfo = pdo_get('weixinmao_house_city',array("uniacid" => $_W['uniacid'],'ison'=>1));
				pdo_update('weixinmao_house_houseinfo',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));
				pdo_update('weixinmao_house_lethouseinfo',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));
				pdo_update('weixinmao_house_oldhouseinfo',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));
				pdo_update('weixinmao_house_agent',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));
				pdo_update('weixinmao_house_active',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));
				pdo_update('weixinmao_house_saleinfo',array('cityid'=>$cityinfo['id']),array('uniacid' => $_W['uniacid']));

				pdo_query("ALTER TABLE ".tablename('weixinmao_house_houseinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_oldhouseinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_lethouseinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_agent')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_active')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_saleinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_area')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_oldhouseinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_houseinfo')." modify column  `cityid` int(10) ;");
			    pdo_query("ALTER TABLE ".tablename('weixinmao_house_oldhouseinfo')." modify column  `cityid` int(10) ;");

				message('操作完成', $this->createWebUrl('city', array('op' => 'display')), 'success');



		} else {
			message('请求方式不存在');
		}
		include $this->template('city', TEMPLATE_INCLUDEPATH, true);
	}
	




	
	public function doWebArea() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$citylist = pdo_fetchall("select id, name from " . tablename('weixinmao_house_city') . " where  uniacid=:uniacid ", array( ":uniacid" => $_W['uniacid']));
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		}elseif($operation == 'buildarea'){

	$pid = intval($_GPC['id']);


			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_buildarea') . " WHERE uniacid = '{$_W['uniacid']}' AND aid='{$pid}' ORDER BY sort DESC");



		} elseif($operation == 'addbuildarea'){


			$pid = intval($_GPC['pid']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
				 	'aid' => $pid,
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_buildarea', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_buildarea', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('area', array('op' => 'buildarea','id'=>$pid)), 'success');
			}

			$houseprice = pdo_fetch("select * from " . tablename('weixinmao_house_buildarea') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));



			
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'sort' => $_GPC['sort'],
					'cityid' => $_GPC['cityid'],
					'enabled'=>1
					);
					
				if (!empty($id)) {
					pdo_update('weixinmao_house_area', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_area', $data);
					$id = pdo_insertid();
				}
				message('更新区域成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
			}
			$area = pdo_fetch("select * from " . tablename('weixinmao_house_area') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$area = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_area') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($area)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('area', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_area', array('id' => $id));
			message('删除成功！', $this->createWebUrl('area', array('op' => 'display')), 'success');
		}elseif($operation == 'deletebuildarea'){
			$id = intval($_GPC['id']);
			$area = pdo_fetch("SELECT id,aid FROM " . tablename('weixinmao_house_buildarea') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			$pid = $area['aid'];
			if (empty($area)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('area', array('op' => 'buildarea')), 'error');
			}
			pdo_delete('weixinmao_house_buildarea', array('id' => $id));
			message('删除成功！', $this->createWebUrl('area', array('op' => 'buildarea','id'=>$pid)), 'success');


		} else {
			message('请求方式不存在');
		}
		include $this->template('area', TEMPLATE_INCLUDEPATH, true);
	}
	
	

	public function doWebMsgtpl() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_msgtpl') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
					
				if (!empty($id)) {
					pdo_update('weixinmao_house_msgtpl', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_msgtpl', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('msgtpl', array('op' => 'display')), 'success');
			}
			$area = pdo_fetch("select * from " . tablename('weixinmao_house_msgtpl') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$area = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_msgtpl') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($area)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('msgtpl', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_msgtpl', array('id' => $id));
			message('删除成功！', $this->createWebUrl('msgtpl', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('msgtpl', TEMPLATE_INCLUDEPATH, true);
	}
	





	public function doWebOldhouseprice() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_oldhouseprice') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
				    'beginprice'=>$_GPC['beginprice'],
					'endprice'=>$_GPC['endprice'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_oldhouseprice', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_oldhouseprice', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('oldhouseprice', array('op' => 'display')), 'success');
			}

			$oldhouseprice = pdo_fetch("select * from " . tablename('weixinmao_house_oldhouseprice') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_oldhouseprice') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('oldhouseprice', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_oldhouseprice', array('id' => $id));
			message('删除成功！', $this->createWebUrl('oldhouseprice', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('oldhouseprice', TEMPLATE_INCLUDEPATH, true);
	}

	
	
	
		public function doWebLethouseprice() {
		global $_W, $_GPC;
	    load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_lethouseprice') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
				    'beginprice'=>$_GPC['beginprice'],
					'endprice'=>$_GPC['endprice'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_lethouseprice', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_lethouseprice', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('lethouseprice', array('op' => 'display')), 'success');
			}

			$lethouseprice = pdo_fetch("select * from " . tablename('weixinmao_house_lethouseprice') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_lethouseprice') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('lethouseprice', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_lethouseprice', array('id' => $id));
			message('删除成功！', $this->createWebUrl('lethouseprice', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('lethouseprice', TEMPLATE_INCLUDEPATH, true);
	}
	
	
	
	
public function doWebToplist() {
		global $_W, $_GPC;
	    load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_toplist') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'money' => $_GPC['money'],
				    'days'=>$_GPC['days'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_toplist', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_toplist', $data);
					$id = pdo_insertid();
				}
				message('更新区域成功！', $this->createWebUrl('toplist', array('op' => 'display')), 'success');
			}

			$toplist = pdo_fetch("select * from " . tablename('weixinmao_house_toplist') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_toplist') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('toplist', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_toplist', array('id' => $id));
			message('删除成功！', $this->createWebUrl('toplist', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('toplist', TEMPLATE_INCLUDEPATH, true);
	}
	
	
	
	
	public function doWebHouseprice() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_houseprice') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY sort DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
				    'beginprice'=>$_GPC['beginprice'],
					'endprice'=>$_GPC['endprice'],
					'sort' => $_GPC['sort'],
					'enabled'=>1
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_houseprice', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_houseprice', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('houseprice', array('op' => 'display')), 'success');
			}

			$houseprice = pdo_fetch("select * from " . tablename('weixinmao_house_houseprice') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_houseprice') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('houseprice', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_houseprice', array('id' => $id));
			message('删除成功！', $this->createWebUrl('houseprice', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('houseprice', TEMPLATE_INCLUDEPATH, true);
	}
	
	public function doWebAgent() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		//$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		//$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if ($operation == 'display') {
			
			
				$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_agent') .$condition ;
		
			
			$total = pdo_fetchcolumn($sql, $params);

			$today = strtotime(date('Y-m-d'));
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_agent') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{

							$list[$k]['housetitle'] = $house_info['housename'];

							$oldhouseinfo = pdo_getall('weixinmao_house_oldhouseinfo',array('uniacid'=>$_W['uniacid'],'tel'=>$v['tel']));
							$lethouseinfo = pdo_getall('weixinmao_house_lethouseinfo',array('uniacid'=>$_W['uniacid'],'tel'=>$v['tel']));
							$list[$k]['oldhousecount'] = count(	$oldhouseinfo );
							$list[$k]['lethousecount'] = count(	$lethouseinfo );
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));

                           	$list[$k]['cityname'] =  $city_info['name'];

								$sql = 'SELECT id FROM  ' . tablename('weixinmao_house_oldhouseinfo') .' WHERE uniacid = '.$_W['uniacid'].' AND createtime > '.$today .' AND tel ='.$v['tel'];
								$todayoldhouseinfo = pdo_fetchall($sql);

									$list[$k]['todayoldhousecount'] = count(	$todayoldhouseinfo );



								$sql = 'SELECT id FROM  ' . tablename('weixinmao_house_lethouseinfo') .' WHERE uniacid = '.$_W['uniacid'].' AND createtime > '.$today .' AND tel ='.$v['tel'];
								$todaylethouseinfo = pdo_fetchall($sql);

								$list[$k]['todaylethousecount'] = count(	$todaylethouseinfo );


						}
				$pager = pagination($total, $pindex, $psize);
			}
			
			
			
			
			
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
				    'thumb'=>$_GPC['thumb'],
					'tel'=>$_GPC['tel'],
					'qq' => $_GPC['qq'],
					'address' => $_GPC['address'],
					'intro' => $_GPC['intro'],
					'content' => ihtmlspecialchars($_GPC['content']),
					'enabled'=> $_GPC['enabled'],
					'sort'=>$_GPC['sort'],
					'createtime' => TIMESTAMP
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_agent', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_agent', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('agent', array('op' => 'display')), 'success');
			}

			$agent = pdo_fetch("select * from " . tablename('weixinmao_house_agent') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_agent') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('agent', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_agent', array('id' => $id));
			message('删除成功！', $this->createWebUrl('agent', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('agent', TEMPLATE_INCLUDEPATH, true);
	}
	



	public function doWebCoupon() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			
			
				$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_coupon') .$condition ;
		
			
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_coupon') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{

							$list[$k]['housetitle'] = $house_info['housename'];
						}
				$pager = pagination($total, $pindex, $psize);
			}
			
		} elseif ($operation == 'fxmessage') {	

			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_fxmessage') .$condition ;
		
			
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_fxmessage') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{
                            $houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['pid']));
							$list[$k]['housetitle'] = $houseinfo['housename'];
							$coupon = pdo_get('weixinmao_house_coupon',array('uid'=>$v['tid']));
							$list[$k]['couponname'] = $coupon['name'];
						}
				$pager = pagination($total, $pindex, $psize);
			}

       } elseif ($operation == 'fxrecord') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_fxrecord') .$condition ;
		
			
			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_fxrecord') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				foreach($list as $k=>$v)
						{
                           // $houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['pid']));
							//$list[$k]['housetitle'] = $houseinfo['housename'];
							$coupon = pdo_get('weixinmao_house_coupon',array('uid'=>$v['uid']));
							$list[$k]['couponname'] = $coupon['name'];
							$list[$k]['coupontel'] = $coupon['tel'];

						}
				$pager = pagination($total, $pindex, $psize);
			}
			
			
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
		
			if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel'=>$_GPC['tel'],
					'weixin' => $_GPC['weixin'],
					'status'=> $_GPC['status'],
					'createtime' => TIMESTAMP
					);
				if (!empty($id)) {
					pdo_update('weixinmao_house_coupon', $data, array('id' => $id));
				} else {
					pdo_insert('weixinmao_house_coupon', $data);
					$id = pdo_insertid();
				}
				message('更新成功！', $this->createWebUrl('coupon', array('op' => 'display')), 'success');
			}

			$agent = pdo_fetch("select * from " . tablename('weixinmao_house_coupon') . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $id, ":uniacid" => $_W['uniacid']));
		
		} elseif ($operation == 'donedeal') {
          	$id = intval($_GPC['id']);
          	//$fxmessage = pdo_get('weixinmao_house_fxmessage',array('uniacid'=>$_W['uniacid'],'id'=>$id));
                pdo_update('weixinmao_house_fxmessage', array('status'=>1), array('id' => $id));
                pdo_update('weixinmao_house_fxrecord', array('status'=>1), array('orderid' => $id));
               $fxrecord = pdo_getall('weixinmao_house_fxrecord', array('orderid' => $id));
               foreach($fxrecord as $k=>$v)
               		{
                              $userinfo = pdo_get('weixinmao_house_userinfo',array('uid'=>$v['uid']));
               				  $score = $userinfo['score'] + $v['money'];

				       			pdo_update('weixinmao_house_userinfo',array('score' => $score), array('uid'=>$v['uid']));

				       			$data = array(
											'uniacid' => $_W['uniacid'],
											'uid'=>$v['uid'],
											'score'=>$v['money'],
											'totalscore'=>$score,
											'content' =>'获得佣金，来源：'.$v['content'] ,
										    'type'=> 0,//消费金额
											'createtime'=>TIMESTAMP
										);

								pdo_insert('weixinmao_house_scorerecord', $data);
								$id = pdo_insertid();


               		}
              

            	message('处理完成！', $this->createWebUrl('coupon', array('op' => 'fxmessage')), 'success');



		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_coupon') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('coupon', array('op' => 'display')), 'error');
			}
			pdo_delete('weixinmao_house_coupon', array('id' => $id));
			message('删除成功！', $this->createWebUrl('coupon', array('op' => 'display')), 'success');
		}elseif ($operation == 'delfxmessage') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_fxmessage') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('coupon', array('op' => 'fxmessage')), 'error');
			}
			pdo_delete('weixinmao_house_fxmessage', array('id' => $id));
			message('删除成功！', $this->createWebUrl('coupon', array('op' => 'fxmessage')), 'success');
		}elseif ($operation == 'delfxrecord') {
			$id = intval($_GPC['id']);
			$oldhouseprice = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_fxrecord') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
			if (empty($oldhouseprice)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('coupon', array('op' => 'fxrecord')), 'error');
			}
			pdo_delete('weixinmao_house_fxrecord', array('id' => $id));
			message('删除成功！', $this->createWebUrl('coupon', array('op' => 'fxrecord')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('coupon', TEMPLATE_INCLUDEPATH, true);
	}
	








	
	public function doWebActive() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_city') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		$citylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
	
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'post') {
			
			$id = $_GPC['id'];
	
			 if (!empty($id)) {
				 $item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_active') . " WHERE id = :id", array(':id' => $id));		
			}
		    $newhouselist = pdo_fetchall("SELECT id,housename  FROM " . tablename('weixinmao_house_houseinfo') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));		
			
			if (checksubmit('submit')) {
				if(is_array($_GPC['thumbs'])){
					$thumb_data['thumb_url'] = serialize($_GPC['thumbs']);
				}
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
                    'content' => ihtmlspecialchars($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
					'money'=>$_GPC['money'],
					'pid'=>$_GPC['pid']
                );
               
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_active', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_active', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('active', array('op' => 'display')), 'success');
            }
			
			
			
			
		} elseif ($operation == 'display') {
			
		
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_active') .$condition ;

			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_active') .$condition.' ORDER BY  `sort`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}
		if($list)
			{
				
				
				foreach($list as $k=>$v)
					{
						$city_info = pdo_get('weixinmao_house_city',array('id'=>$v['cityid']));

						$list[$k]['cityname'] =  $city_info['name'];
					}
				
				
			}
			
			
		}elseif($operation == 'baoming'){
			
			
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE b.uniacid = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND a.title LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			
			
			$sqlcount = 'SELECT  COUNT(*)  ';
		
			
			
			$sql = " FROM " . tablename('weixinmao_house_baoming') . " AS b ";
			
			$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_houseinfo') . " as h ON h.id = b.pid ";
			
			$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_active') . " as a ON a.id = b.aid ";
			
			//$sql .= " WHERE r.paperid = :paperid AND r.weid = :weid AND m.weid = :weid AND did = 1";
			$total = pdo_fetchcolumn($sqlcount.$sql.$condition, $params);
			
			if (!empty($total)) {
				
				
				
				$sqlall = 'SELECT b.createtime AS createtime,  b.name as name, b.tel as tel,h.housename AS housename,a.title AS title ,b.id AS id' . $sql .$condition.' ORDER BY  b.createtime  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				
				
				$list = pdo_fetchall($sqlall, $params);
			
				$pager = pagination($total, $pindex, $psize);
			}
			
			
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_active') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			pdo_delete('weixinmao_house_active', array('id' => $id));

			message('删除成功！', referer(), 'success');
		}elseif ($operation == 'deleteactive'){
			
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_baoming') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			pdo_delete('weixinmao_house_baoming', array('id' => $id));

			message('删除成功！', referer(), 'success');
			
		}
		include $this->template('active');
		
	}
	
	    public function doWebOrder()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		if ($operation == 'post') {
			$id = $_GPC['id'];
			if (!empty($id)) {
				 $item = pdo_fetch("SELECT *  FROM " . tablename('weixinmao_house_order') . " WHERE id = :id", array(':id' => $id));			
			}
			if (checksubmit('submit')) {
				//print_r($_GPC);
				//exit;
                $data = array(
                    'uniacid' => $_W['uniacid'],
					'title'=>$_GPC['title'],
                    'content' => htmlspecialchars_decode($_GPC['content']),
					'sort'=>$_GPC['sort'],
					'thumb'=>$_GPC['thumb'],
                    'createtime' => TIMESTAMP,
                );
                if (!empty($id)) {
                    unset($data['createtime']);
                    pdo_update('weixinmao_house_order', $data, array('id' => $id));
                } else {
                    pdo_insert('weixinmao_house_order', $data);
                    $id = pdo_insertid();
                }
                message('更新成功！', $this->createWebUrl('order', array('op' => 'display')), 'success');
            }
		} elseif($operation == 'done'){
			
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			 pdo_update('weixinmao_house_order', array('status'=>2), array('id' => $id));

			message('操作成功！', referer(), 'success');
			
		
		}elseif ($operation == 'display') {
			$status = $_GPC['status'];
			if(!isset($status))
					$status = -1;
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `uniacid` = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			
			if (!empty($_GPC['member'])) {
				$condition .= ' AND `tel` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['member']) . '%';
			}
			if($status ==0)
			{
				$condition .= ' AND `paid` = 0 ';
			}elseif($status ==1)
			{
					$condition .= ' AND `paid` = 1 AND status =1 ';
			}elseif($status == 2){
				
				$condition .= ' AND `paid` = 1 AND status =2 ';
			}elseif($status ==3){
				
				$condition .= ' AND `paid` = 1 AND status =3 ';
			}
			
			$sql = 'SELECT COUNT(*) FROM ' . tablename('weixinmao_house_order') .$condition ;

			$total = pdo_fetchcolumn($sql, $params);
			
			if (!empty($total)) {
				$sql = 'SELECT * FROM  ' . tablename('weixinmao_house_order') .$condition.' ORDER BY  `createtime`  DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
				if($list)
				{
						foreach($list as $k=>$v)
						{
							if($v['couponid']>0)
							{
								$coupon_order = pdo_fetch("SELECT title FROM " . tablename('weixinmao_house_order') . " WHERE id = :id", array(':id' => $v['couponid']));
								//print_r($coupon_order);
								$list[$k]['coupon'] = $coupon_order['title'];
							}else{
								
								$list[$k]['coupon'] = '';
							}
						}
					
				}
				
			}
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，订单不存在或是已经被删除！');
			}
			pdo_delete('weixinmao_house_order', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('order');
		
	}
	
	
	
	

}