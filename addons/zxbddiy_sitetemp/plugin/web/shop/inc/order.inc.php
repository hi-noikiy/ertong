<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	


	if( checkSubmit('down') ){

		set_time_limit(0);

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>0);
		
		if( $_GPC['status'] == 1 ) $where['status'] = 0;
		if( $_GPC['status'] == 2 ) $where['status'] = 1;
		if( $_GPC['status'] == 3 ) $where['status'] = 2;
		if( $_GPC['status'] == 4 ) $where['status'] = 3;
		if( $_GPC['status'] == 5 ) $where['status'] = 4;

		if( $_GPC['sendtype'] == 1 ) $where['taketype'] = 2;
		if( $_GPC['sendtype'] == 2 ) $where['taketype'] = 1;

		$start = strtotime( $_GPC['rangetime']['start'] );
		$end = strtotime( $_GPC['rangetime']['end'] );
		$where['createtime>'] = $start;
		$andstr = ' AND createtime <= '.$end;

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_order',$where,1,999999,' `id` DESC ',false,false,' * ',$andstr);
		$list = $info[0];

		downLoadOrder( $list );

		die;
	}

	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_order');
		Util::deleteCache('alld0','all');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	$op = intval( $_GPC['op'] );
	if( $op > 0 ){	

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>0);
		$str = '';
		$where['status'] = $op - 1;
		
		if( $op == 1 ) $str = ' AND `paytype` = 0 ';
		if( $op == 2 ) {
			unset( $where['status'] );
			$str = ' AND ( ( `status` =  1 ) OR ( `status` = 0 AND `paytype` = 1 ) ) ';
		}

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_order',$where,$_GPC['page'],10,' `id` DESC ',false,true,' * ',$str);
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ){
			foreach ( $list as &$v ) {
				$v['ordergood'] = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$v['orderid']));
				if( !empty( $v['ordergood'] ) ){

					foreach ( $v['ordergood'] as $kk => $vv ) {
						$good = model_good::getGood( $vv['gid'] );

						$v['ordergood'][$kk]['title'] = $good['title'];
						$v['ordergood'][$kk]['thumb'] = tomedia( $good['thumb'] );
					}
					
				}
				
			}
		}

	}
	


	if($_GPC['op'] == 'info'){

		$id = intval($_GPC['id']);
		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$id));
		if( empty( $order ) ) itoast('未找到订单','','error');
		
		$order['address'] = iunserializer( $order['address'] );
		$order['formdata'] = iunserializer( $order['formdata'] );
		$order['progress'] = iunserializer( $order['progress'] );
		
		$ordergood = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$order['orderid']));

		if( !empty( $ordergood ) ) {
			foreach ( $ordergood as &$v ) {
				$good = model_good::getGood( $v['gid'] );
				$v['title'] = $good['title'];
				$v['thumb'] = tomedia( $good['thumb'] );
			}
		}

		$user = pdo_get('mc_mapping_fans',array('openid'=>$order['openid']));
		$tag = iunserializer( base64_decode( $user['tag'] ) );
		$user['headimgurl'] = $tag['headimgurl'];

		if( !empty( $order['deskid'] ) ){
			$desk = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$order['deskid']));
		}

	}
	
	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_order');
		Util::deleteCache('alld0','all');
		if($res) itoast('删除成功','','success');
	}
	
	if( $_GPC['op'] == 'down' ) {

		$rangetime['start'] = date('Y-m-d H:i:s',TIMESTAMP - 7*3600*24);
		$rangetime['end'] = date('Y-m-d H:i:s',TIMESTAMP);

	}


	//下载表格
	function downLoadOrder($list){
		
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF".$html; //添加BOM
		/* 输出表头 */		
		$html .= '数据id' . "\t,";	
		$html .= '订单编号' . "\t,";
		$html .= '订单类型' . "\t,";
		$html .= '订单标题' . "\t,";
		$html .= '订单金额' . "\t,";
		$html .= '收货方式' . "\t,";
		$html .= '支付方式' . "\t,";
		$html .= '订单状态' . "\t,";
		$html .= '收货人名' . "\t,";
		$html .= '收货电话' . "\t,";
		$html .= '收货地址' . "\t,";
		$html .= '快递名称' . "\t,";
		$html .= '快递编号' . "\t,";
		$html .= '核销员' . "\t,";
		$html .= "\n";
			
 		foreach((array)$list as $k => $v){	

 			$address = iunserializer( $v['address'] );

 			if( $v['status'] == 0 ){
 				$status = '未支付';

 			}elseif( $v['status'] == 1 ){
 				$status = $this->module['config']['lawyer'] == 1 ? '待履行' : '待发货';
 			}elseif( $v['status'] == 2 ){
 				$status = '已完成';
 			}elseif( $v['status'] == 3 ){
 				$status = $this->module['config']['lawyer'] == 1 ? '正在履行' : '已发货';
 			}elseif( $v['status'] == 4 ){
 				$status = '已退款';
 			}

 			if( $v['taketype'] == 1 ) $sendtype = '物流发货';
 			if( $v['taketype'] == 2 ) $sendtype = '上店自提';

 			$paytype = $v['taketype'] == 0 ? '在线支付' : '货到付款';

 			$type = $v['deskid'] > 0 ? '扫码点餐' : '普通订单';

 			if( !empty( $v['hexiaoer'] ) ) $hexiao = model_user::getSingleUser( $v['hexiaoer'] );

			$html .= $v['id'] . "\t,";
			$html .= $v['orderid'] . "\t,";
			$html .= $type . "\t,";
			$html .= $v['title'] . "\t,";
			$html .= $v['fee'] . "\t,";
			$html .= $sendtype . "\t,";
			$html .= $paytype . "\t,";
			
			$html .= $status . "\t,";
			$html .= $address['name'] . "\t,";
			$html .= $address['tel'] . "\t,";
			$html .= $address['address'] . "\t,";
			$html .= $v['expressname'] . "\t,";
			$html .= $v['expressnum'] . "\t,";
			$html .= $hexiao['nickname'] . "\t,";
			$html .= "\n";
			
		}
		/* 输出CSV文件 */
		//$realname=iconv('utf-8','gb2312',$row['realname']);
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=订单列表.csv");
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


	include $this->pTemplate('order');