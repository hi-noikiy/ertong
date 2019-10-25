<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

	if( $_GPC['op'] == 'info' && $_W['ispost'] ) {
		
		$cart = new model_cart( $_W['openid'] );

		$data['cart'] = $cart->cartArray;

		if( !empty( $data['cart'] ) ){
			
			foreach ( $data['cart'] as &$v ) {
				$good = model_good::getGood( $v['gid'] );

				// 商品不存在失效
				if( empty( $good ) || $good['status'] == 1 ) {
					$v['fail'] == 1;
					continue;
				}
				$v['fail'] = 0;
				$v['minstock'] = 1;
				$v['title'] = $good['title'];
				$v['thumb'] = tomedia($good['thumb']);
				$v['oldprice'] = $good['oldprice'];
				$v['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$v['gid'];
				unset( $v['openid'] );

				if( $good['isrule'] ){
					
					$isin = 0;
					foreach ( $good['rulearray']['rulemap'] as $vv ) {
						if( $vv['id'] == $v['ruleid'] ){

							// 不够库存变为最大的
							if( $v['num'] > $vv['stock'] ){
								$v['num'] = $vv['stock'];
							}
							$v['stock'] = $vv['stock'];
							$v['price'] = $vv['nowprice'];
							$v['rulename'] = $vv['name'];
							$v['quantity'] = array('quantity'=>$v['num'],'min'=>1,'max'=>$vv['stock']);
							$isin = 1;
							break;
						}
					}

					if( $isin == 0 ){ // 不在规格内失效
						$v['fail'] = 1;
						continue;
					}

				}else{
					// 不够库存变为最大的
					if( $v['num'] > $good['stock'] ){
						$v['num'] = $good['stock'];
					}
					$v['price'] = $good['price'];
					$v['stock'] = $good['stock'];
					$v['quantity'] = array('quantity'=>$v['num'],'min'=>1,'max'=>$good['stock']);
				}
			}
		}
		

		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'delete' && $_W['ispost'] ) {

		pdo_delete('zxbddiy_sitetemp_cart',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'id'=>$_GPC['id']));
		Util::deleteCache('cart',$_W['openid']);

		$this->result(0, '');
	}elseif( $_GPC['op'] == 'deleteall' && $_W['ispost'] ) {

		pdo_delete('zxbddiy_sitetemp_cart',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']));
		Util::deleteCache('cart',$_W['openid']);
		$this->result(0, '');
		
	}

	