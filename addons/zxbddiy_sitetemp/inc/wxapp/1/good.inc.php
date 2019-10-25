<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	if( $_GPC['op'] == 'info' && $_W['ispost'] ) {
		

		$good = model_good::getGood( $_GPC['gid'] );
		if( empty( $good ) ) $this->result(1, '商品不存在');

		$good['thumb'] = tomedia( $good['thumb'] );
		$good['content'] = htmlspecialchars_decode( $good['content'] );

		if( !empty( $good['pic'] ) ) {
			foreach ($good['pic'] as &$v) {
				$v = tomedia( $v );
			}
		}

		$data['good'] = $good;
		
		$set = Util::getModuleConfig();
		$set['kefuimg'] = tomedia( $set['kefuimg'] );
		$set['okefuimg'] = tomedia( $set['okefuimg'] );
		unset( $set['frompass'],$set['mail'] );
		$data['set'] = $set;
		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'buy' && $_W['ispost'] ){

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
		
		$good = json_decode( htmlspecialchars_decode( $_GPC['good'] ),true);

		if( empty( $good ) || !is_array( $good ) ) $this->result(1, '请选择商品');

		//营业时间
		$islimit = model_order::istimeLimit( 0,$this->module['config'] );
		if( $islimit['status'] ) {
			$this->result(1, $islimit['res']);
		}

		$arr = array();
		foreach ( $good as $v ) {
			
			$good = model_good::getGood( $v['gid'] );
			if( empty( $good ) ) $this->result(1, '未找到商品');
			if( $good['status'] == 1 ) $this->result(1, '商品已下架');

			if( empty( $v['gid'] ) ) $this->result(1, '请选择商品');
			if( empty( $v['num'] ) ) $this->result(1, '请选择商品数量');
			
			// 判断规格
			if( $good['isrule'] )
			{
				if( empty( $v['map'] ) ) $this->result(1, '请选择商品规格');
				$isin = 0;
				foreach ( $good['rulearray']['rulemap'] as $vv ) {
					if( $vv['id'] == $v['map'] ){
						$isin = 1;
						if( $vv['stock'] < $v['num'] ) $this->result(1, '商品库存不足');
					}
				}

				if( $isin == 0 ) $this->result(1, '请选择商品规格');
			}else{

				if( $good['stock'] < $v['num'] ) $this->result(1, '商品库存不足');
			}
			$arr[] = array('gid'=>$v['gid'],'num'=>$v['num'],'rule'=>$v['map'],'cartid'=>$v['cartid']);
		}
		
		Util::setCache('buydata',$_W['openid'],$arr);
		// 删除历史订单记录
		Util::deleteCache('oldpay',$_W['openid']);

		$this->result(0, '好');
	
	}elseif( $_GPC['op'] == 'cart' && $_W['ispost'] ){

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

		if( empty( $_GPC['gid'] ) ) $this->result(1, '请选择商品');
		if( empty( $_GPC['num'] ) ) $this->result(1, '请选择商品数量');

		$good = model_good::getGood( $_GPC['gid'] );

		if( empty( $good ) ) $this->result(1, '未找到商品');
		if( $good['status'] == 1 ) $this->result(1, '商品已下架');

		// 判断规格
		if( $good['isrule'] )
		{	
			if( empty( $_GPC['map'] ) ) $this->result(1, '请选择商品规格');
			$isin = 0;
			foreach ( $good['rulearray']['rulemap'] as $v ) {
				if( $v['id'] == $_GPC['map'] ){
					$isin = 1;
					if( $v['stock'] < $_GPC['num'] ) $this->result(1, '商品库存不足');
				}
			}

			if( $isin == 0 ) $this->result(1, '请选择商品规格');
		}else{

			if( $good['stock'] < $_GPC['num'] ) $this->result(1, '商品库存不足');
		}
		
		$cart = new model_cart( $_W['openid'] );
		$cart->addCart($good['id'],$_GPC['num'],$_GPC['map']);
		
		$this->result(0, '已加入');

	}

	