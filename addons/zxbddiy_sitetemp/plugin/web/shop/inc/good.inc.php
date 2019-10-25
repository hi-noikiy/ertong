<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	

	//添加，编辑
	if(checksubmit('create')){
		$_GPC = Util::trimWithArray($_GPC);
		
		$data['title'] = $_GPC['title'];
		$data['desc'] = $_GPC['desc'];
		$data['number'] = intval( $_GPC['number'] );
		$data['uniacid'] = $_W['uniacid'];
		$data['sorttwo'] = intval( $_GPC['sortid'] );
		$data['thumb'] = $_GPC['thumb'];
		$data['pic'] = iserializer( $_GPC['pic'] );
		$data['isexpress'] = intval( $_GPC['isexpress'] );
		$data['expressmoney'] = sprintf('%.2f',$_GPC['expressmoney']);
		$data['isselftake'] = intval( $_GPC['isselftake'] );
		$data['shoptel'] = $_GPC['shoptel'];
		$data['shopaddress'] = $_GPC['shopaddress'];
		$data['stock'] = intval( $_GPC['stock'] );

		$data['oldprice'] = sprintf('%.2f',$_GPC['oldprice']);
		$data['price'] = sprintf('%.2f',$_GPC['price']);

		$data['content'] = str_replace('?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1', '', $_GPC['content']);
		$data['vurl'] = $_GPC['vurl'];
		$data['isrule'] = intval( $_GPC['isrule'] );
		$data['isaddex'] = intval( $_GPC['isaddex'] );

		$data['type'] = 0;
		$data['iscanbuy'] = intval( $_GPC['iscanbuy'] );
		$data['isprice'] = intval( $_GPC['isprice'] );
		$data['sales'] = intval( $_GPC['sales'] );
		
		// 验证
		if( empty( $data['title'] ) ) itoast('未填写标题','','error');

		//if( $data['oldprice'] < 0.01 ) message('原价必须大于0.01');
		if( $data['isrule'] == 0 && $data['price'] < 0.01 ) itoast('现价必须大于0.01','','error');

		// 一级分类
		$sort = pdo_get('zxbddiy_sitetemp_goodsort',array('id'=>$data['sorttwo']));
		$data['sortone'] = $sort['parent'];

		//处理规格
		if($data['isrule'] == 1){
			$rulearray['rule'] = json_decode(htmlspecialchars_decode($_GPC['rule']),true);
			$rulearray['rulemap'] = json_decode(htmlspecialchars_decode($_GPC['rulemap']),true);
			
			foreach($rulearray['rulemap'] as &$v){
				$v['nowprice'] = sprintf('%.2f',$v['nowprice']);

				if( $v['nowprice'] < 0.01 ) itoast('规格内的价格必须大于0','','error');

				$v['stock'] = intval($v['stock']);
				$setnowprice[] = $v['nowprice'];
				$setstock[] = $v['stock'];
			}
			unset($v); //删除链接
			
			$data['stock'] = max($setstock);
			$data['price'] = min($setnowprice);
			
			$data['rulearray'] = iserializer($rulearray);
		}

		// 标签
		$icon = array();
		foreach ((array)$_GPC['iconname'] as $k => $v) {
			$paramsitem = array();
			$paramsitem['name'] = $_GPC['iconname'][$k];
			$paramsitem['desc'] = $_GPC['icondesc'][$k];
			$icon[] = $paramsitem;
		}
		$data['icon'] = iserializer( $icon );


		if(!empty($_GPC['id'])){
			$id = intval($_GPC['id']);
			$res = pdo_update('zxbddiy_sitetemp_good',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id));	
		}else{
			$data['createtime'] = TIMESTAMP;

			$res = Util::inserData('zxbddiy_sitetemp_good',$data);
			$id = pdo_insertid();
		}
		Util::deleteCache('good',$id);
		itoast('操作成功',self::pwUrl('shop','good',array('op'=>'list','page'=>$_GPC['page'])),'success');
	}
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_good');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	//批量下架
	if(checksubmit('downall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('先选择操作的商品','','error');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_good',array('status'=>1),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('good',$v);
		}
		message('操作完成',referer(),'success');
	}

	//批量上架
	if(checksubmit('upall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('先选择操作的商品','','error');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_good',array('status'=>0),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('good',$v);
		}
		itoast('操作完成','','success');
	}	


	$goodsort = model_goodsort::getSort(0);
	
	if($_GPC['op'] == 'list'){	

		$topbar = topbal::goodList();

		$where = array('uniacid'=>$_W['uniacid'],'type'=>0);
		$where['status'] = intval( $_GPC['status'] );

		if( !empty( $_GPC['title'] ) ) $where['title@'] = $_GPC['title'];

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_good',$where,$_GPC['page'],10,' `id` DESC ');
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ) {

			foreach ( $list as &$v ) {
				$v['urlimg'] = $this->createWebUrl('img',array('op'=>'good','gid'=>$v['id']));

			}

		}

	}
	
	if($_GPC['op'] == 'edit'){
		$id = intval($_GPC['id']);
		$info = model_good::getGood( $id );
		
		if( empty( $info ) ) itoast('未找到商品','','error');
	}
	

	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_good');
		if($res) itoast('删除成功','','success');
	}
	
	if($_GPC['op'] == 'down'){
		pdo_update('zxbddiy_sitetemp_good',array('status'=>1),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('good',$_GPC['id']);

		itoast('已下架','','success');
	}	
	
	if($_GPC['op'] == 'up'){
		pdo_update('zxbddiy_sitetemp_good',array('status'=>0),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('good',$_GPC['id']);

		itoast('已下架','','success');
	}	
	

	include $this->pTemplate('good');
