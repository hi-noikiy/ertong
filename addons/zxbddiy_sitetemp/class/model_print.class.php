<?php
	//global $_W;
	
class model_print {
	

	static function getPrint( $type ){
		global $_W;

		$cache = Util::getCache('print',$type);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'status'=>0,'plug'=>$type);
			$cache = pdo_getall('zxbddiy_sitetemp_print',$where);
			
			if( !empty( $cache ) ) {
				Util::setCache('print',$type,$cache);
			}
		}
		return $cache;
	}
	
	
	static function printOrder( $orderid,$set,$plug=0 ){

		$print = self::getPrint( $plug );
		$result = array();
		
		if( !empty( $print ) ) {

			if( $orderid == -1 ) { // 测试
				$order = array('title'=>'测试订单','备注留言','type'=>0,'taketype'=>2,'address'=>array('name'=>'张三','tel'=>'13111111111','address'=>'广东省深圳市南山大道1号'),'tel'=>'13111111111','fee'=>66.6);

			}else{
				$order = pdo_get('zxbddiy_sitetemp_order',array('orderid'=>$orderid));
				$ordergood = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$order['orderid']));
			}



			$mess = empty( $order['mess'] ) ? '无' : $order['mess'];
			$sendtype = $order['type'] == 0 ? ( $order['taketype'] == 1 ? '快递配送' : '上店自提' ) : '送货上门';
			$time = date('Y-m-d H:i:s',TIMESTAMP);
			$paytype = $order['paytype'] == 0 ? '在线支付' :  ( $order['deskid'] <= 0 ? '货到付款' : '餐后付款' );

			
			if( $order['deskid'] > 0 ) {
				$desk = pdo_get('zxbddiy_sitetemp_desk',array('id'=>$order['deskid']));
			}

			$fymsgoods = $fgmsgoods = '';
			$express = 0; // 配送费用
			foreach ( (array)$ordergood as $v ) {
				$express += $v['costexpress'];
			}
		

			if( $order['taketype'] == 1 ){
				$add = iunserializer( $order['address'] );
				$address = $add['address'];
				$tel = $add['tel'];
				$name = $add['name'];

			}elseif( $order['taketype'] == 2 ){
				$address = '无';
				$tel = $order['tel'];
				$name = '无';
				$express = 0;
			}
			if( $order['type'] == 1 ) $express = $order['sendmoney'];

			if( $orderid == -1 ) { // 测试
				$fymsgoods .= ('西红柿炒蛋   ￥22.2   ×1'."\n");
				$fgmsgoods .= ('西红柿炒蛋   ￥22.2   ×1'."<BR>");

				$fymsgoods .= ('西红柿翻炒鸡蛋   ￥22.2   ×2'."\n");
				$fgmsgoods .= ('西红柿翻炒鸡蛋   ￥22.2   ×2'."<BR>");
			}else{
				foreach ( $ordergood as $v ) {
					$good = model_good::getGood( $v['gid'] );
					$fymsgoods .= ($good['title'].'('.$v['rulename'].')'.'   ￥'.$v['price'].'   ×'.$v['num']."\n");
					$fgmsgoods .= ($good['title'].'('.$v['rulename'].')'.'   ￥'.$v['price'].'   ×'.$v['num']."<BR>");
				}
			}


				if( $order['deskid'] <= 0 ) $botinfo = <<<div
支付方式：{$paytype}
收货方式：{$sendtype}
收货人名: {$name}
送货地址：{$address}
联系电话：{$tel}
订购时间：{$time}
div;

				if( $order['deskid'] > 0 ) $botinfo = <<<div
支付方式：{$paytype}
餐桌名称：{$desk['name']}
订购时间：{$time}
div;

			foreach ( $print as $k => $v ) {
				$times = intval( $v['times'] );
				$times = empty( $times ) ? 1 : $times;
				$msgDetail = '';

				$bs = '';
				$be = '';

				if( $v['isbig'] == 1 ){
					$bs = '<B>';
					$be = '</B>';
				}

				if( $v['type'] == 0 ){ // 飞印

					for ($i=0; $i < $times; $i++) { 
			$msgDetail .= <<<div
	{$set['shopname']}
条目      单价（元）   数量
------------------------------
{$fymsgoods}

配送费用：{$express}元
会员备注：{$mess}
------------------------------
合计：{$order['fee']}元 

{$botinfo}


+++++++++++++end++++++++++++++



div;
					}


				$msgNo = time()+$k+1;

				$msg = array(
					'memberCode'=>$v['code'], 
					'msgDetail'=> $msgDetail,
					'deviceNo'=>$v['devno'], 
					'msgNo'=>$msgNo,
				);

				$msg['reqTime'] = number_format(1000*time(), 0, '', '');
				$content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$v['key'];
				$msg['securityCode'] = md5($content);
				$msg['mode']=2;

				$res = Util::httpPost('http://my.feyin.net/api/sendMsg',$msg );

				if( $res == 0 ) {
					$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
				}else{

					switch ($res) {
						case -1:
							$error = 'IP地址不允许';
							break;
						case -2:
							$error = '关键参数为空或请求方式不对';
							break;
						case -3:
							$error = '客户编码不对';
							break;
						case -4:
							$error = '安全校验码不正确';
							break;
						case -5:
							$error = '请求时间失效';
							break;
						case -6:
							$error = '订单内容格式不对';
							break;
						case -7:
							$error = '重复的消息 （ msgNo 的值重复）';
							break;
						case -8:
							$error = '消息模式不对';
							break;
						case -9:
							$error = '服务器错误';
							break;
						case -10:
							$error = '服务器内部错误';
							break;
						case -111:
							$error = '打印终端不属于该账户';
							break;														
						default:
							break;
					}

					$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$error );
				}

				// 飞鹅
				}elseif( $v['type'] == 1 ){

					for ($i=0; $i < $times; $i++) { 
						$msgDetail .= <<<div
<CB>{$set['shopname']}</CB>
条目      单价（元）   数量
------------------------------
{$bs}{$fgmsgoods}{$be}
配送费用：{$express}元
会员备注：{$mess}
------------------------------
<B>合计：{$order['fee']}元</B>

{$botinfo}
+++++++++++++end++++++++++++++<BR><BR><BR><CUT>
div;
					}

				
					$time = time();

					$content = array(			
						'user'=>$v['code'],
						'stime'=>time(),
						'sig'=>sha1( $v['code'].$v['key'].$time ),
						'apiname'=>'Open_printMsg',

						'sn'=>$v['devno'],
						'content'=>$msgDetail,
					    'times'=>1//打印次数
					);

					$res = Util::httpPost('http://api.feieyun.cn/Api/Open/',$content );

					$res = json_decode($res,true);

					if( $res['ret'] == 0 ) {
						$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
					}else{
						$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$res['msg'] );
					}

				// 365	
				}elseif( $v['type'] == 2 ){

					for ($i=0; $i < $times; $i++) { 
						$msgDetail .= <<<div
      {$set['shopname']}
条目      单价（元）   数量
------------------------------
{$fymsgoods}
配送费用：{$express}元
会员备注：{$mess}
------------------------------
合计：{$order['fee']}元

{$botinfo}
+++++++++++++end++++++++++++++



div;
					}

				

					$selfMessage = array(
						'deviceNo'=>$v['devno'], 
						'printContent'=>$msgDetail,
						'key'=>$v['key'],
						'times'=>1
					);				
					$url = "http://open.printcenter.cn:8080/addOrder";
					$options = array(
						'http' => array(
							'header' => "Content-type: application/x-www-form-urlencoded ",
							'method'  => 'POST',
							'content' => http_build_query($selfMessage),
						),
					);
					$context  = stream_context_create($options);
					$res = file_get_contents($url, false, $context);

					$res = json_decode($res,true);

					if( $res['responseCode'] == 0 ) {
						$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
					}else{
						$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$res['msg'] );
					}

				}



			}
	
		}
		
		return $result;

	}


	static function printappOrder( $orderid,$set ){

		$print = self::getPrint( 1 );
		$result = array();

		if( !empty( $print ) ) {

			if( $orderid == -1 ) { // 测试
				$order = array('title'=>'测试订单','备注留言','type'=>0,'taketype'=>2,'address'=>array('name'=>'张三','tel'=>'13111111111','address'=>'广东省深圳市南山大道1号'),'tel'=>'13111111111','fee'=>66.6);

			}else{
				$order = pdo_get('zxbddiy_sitetemp_appointorder',array('orderid'=>$orderid));
			}

			$tel = empty( $order['tel'] ) ? '' : '联系电话： '.$order['tel'];
			$time = date('Y-m-d H:i:s',TIMESTAMP);
			$fee = $order['ispay'] == 1 ? $order['fee'].'元' : '无';

			$fymsgoods = $fgmsgoods = '';

			if( $orderid == -1 ) { // 测试
				$fymsgoods .= ('西红柿炒蛋   ￥22.2   ×1'."\n");
				$fgmsgoods .= ('西红柿炒蛋   ￥22.2   ×1'."<BR>");

				$fymsgoods .= ('西红柿翻炒鸡蛋   ￥22.2   ×2'."\n");
				$fgmsgoods .= ('西红柿翻炒鸡蛋   ￥22.2   ×2'."<BR>");
			}else{
				$form = iunserializer( $order['form'] );
				if( !empty( $form ) ){
					foreach ( $form as $v ) {
						if( is_array( $v['value'] ) ){
							$value = '';

							foreach ($v['value'] as $vv) {
								$value .= $vv.'，';
							}

							$fymsgoods .= ($v['name'].'： '.$value."\n");
							$fgmsgoods .= ($v['name'].'： '.$value."<BR>");
						}else{
							$fymsgoods .= ($v['name'].'： '.$v['value']."\n");
							$fgmsgoods .= ($v['name'].'： '.$v['value']."<BR>");
						}
					}
				}

			}

			foreach ( $print as $k => $v ) {

				$times = intval( $v['times'] );
				$times = empty( $times ) ? 1 : $times;
				$msgDetail = '';

				if( $v['type'] == 0 ){ // 飞印

					for ($i=0; $i < $times; $i++) { 
			$msgDetail .= <<<div
	      预约订单
名称     内容
------------------------------
{$tel}
{$fymsgoods}
------------------------------
预约项目：{$order['title']}
支付金额：{$fee}
提交时间：{$time}


+++++++++++++end++++++++++++++



div;
					}



				$msgNo = time()+$k+1;

				$msg = array(
					'memberCode'=>$v['code'], 
					'msgDetail'=> $msgDetail,
					'deviceNo'=>$v['devno'], 
					'msgNo'=>$msgNo,
				);

				$msg['reqTime'] = number_format(1000*time(), 0, '', '');
				$content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$v['key'];
				$msg['securityCode'] = md5($content);
				$msg['mode']=2;

				$res = Util::httpPost('http://my.feyin.net/api/sendMsg',$msg );

				if( $res == 0 ) {
					$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
				}else{

					switch ($res) {
						case -1:
							$error = 'IP地址不允许';
							break;
						case -2:
							$error = '关键参数为空或请求方式不对';
							break;
						case -3:
							$error = '客户编码不对';
							break;
						case -4:
							$error = '安全校验码不正确';
							break;
						case -5:
							$error = '请求时间失效';
							break;
						case -6:
							$error = '订单内容格式不对';
							break;
						case -7:
							$error = '重复的消息 （ msgNo 的值重复）';
							break;
						case -8:
							$error = '消息模式不对';
							break;
						case -9:
							$error = '服务器错误';
							break;
						case -10:
							$error = '服务器内部错误';
							break;
						case -111:
							$error = '打印终端不属于该账户';
							break;														
						default:
							break;
					}

					$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$error );
				}

				// 飞鹅
				}elseif( $v['type'] == 1 ){

					for ($i=0; $i < $times; $i++) { 
					$msgDetail .= <<<div
<CB>预约订单</CB>
名称     内容
------------------------------
{$tel}
{$fgmsgoods}
------------------------------
预约项目：{$order['title']}
支付金额：{$fee}
提交时间：{$time}

+++++++++++++end++++++++++++++<BR><BR><BR>
div;
					}


				
					$time = time();

					$content = array(			
						'user'=>$v['code'],
						'stime'=>time(),
						'sig'=>sha1( $v['code'].$v['key'].$time ),
						'apiname'=>'Open_printMsg',

						'sn'=>$v['devno'],
						'content'=>$msgDetail,
					    'times'=>1//打印次数
					);

					$res = Util::httpPost('http://api.feieyun.cn/Api/Open/',$content );

					$res = json_decode($res,true);

					if( $res['ret'] == 0 ) {
						$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
					}else{
						$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$res['msg'] );
					}

				// 365
				}elseif( $v['type'] == 2 ){

					for ($i=0; $i < $times; $i++) { 
						$msgDetail .= <<<div
    预约订单
名称     内容
------------------------------
{$tel}
{$fymsgoods}
------------------------------
预约项目：{$order['title']}
支付金额：{$fee}
提交时间：{$time}

+++++++++++++end++++++++++++++



div;
					}

				

					$selfMessage = array(
						'deviceNo'=>$v['devno'], 
						'printContent'=>$msgDetail,
						'key'=>$v['key'],
						'times'=>1
					);				
					$url = "http://open.printcenter.cn:8080/addOrder";
					$options = array(
						'http' => array(
							'header' => "Content-type: application/x-www-form-urlencoded ",
							'method'  => 'POST',
							'content' => http_build_query($selfMessage),
						),
					);
					$context  = stream_context_create($options);
					$res = file_get_contents($url, false, $context);

					$res = json_decode($res,true);

					if( $res['responseCode'] == 0 ) {
						$result[] = array( 'res'=>200,'msg'=>$v['name'].'打印成功' );
					}else{
						$result[] = array( 'res'=>201,'msg'=>$v['name'].'打印失败：'.$res['msg'] );
					}
					
				}



			}
	
		}
		
		return $result;

	}

}

