<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	if( $_GPC['op'] == 'info' ) {

		$plug = intval( $_GPC['plug'] );

		if( empty( $plug ) ) {

			$allsort = model_goodsort::getSort(0);
			
			// 分隔各个模板的分类
			$sort = array();
			if( $this->module['config']['iscutsort'] == 1 ){
				foreach ( $allsort as $v ) {
					if( !empty( $v['down'] ) ){
						foreach ($v['down'] as $vv) {
							if( $vv['tempid'] != $_GPC['tid'] ){
								continue;
							}
							$sort[] = $vv;
						}
					}
				}
			}else{
				foreach ( $allsort as $v ) {
					if( !empty( $v['down'] ) ){
						foreach ($v['down'] as $vv) {
							$sort[] = $vv;
						}
					}
				}
				$sort = $sort;
			}

			$allgood = pdo_getall('zxbddiy_sitetemp_good',array('uniacid'=>$_W['uniacid'],'status'=>0),array('id','price','isrule','rulearray','title','thumb','sorttwo','sales','stock','desc'));

			if( !empty( $sort ) ) {
				foreach ($sort as $k => &$v) {
					$list = array();
					foreach ( $allgood as $vv ) {

						if( $vv['sorttwo'] == $v['id'] ){
							$vv['thumb'] = tomedia( $vv['thumb'] );
							$vv['url'] = '/zxbddiy_sitetemp/pages/good/good?from=exbuy&gid='.$vv['id'];
							$vv['buynum'] = 0;
							if( $vv['isrule'] ) {
								$vv['rulearray'] = iunserializer( $vv['rulearray'] );
							}

							$list[] = $vv;
						}
						
					}
					$v['good'] = $list;
				}
			}
			
			$data['allsort'] = $sort;
			

			$set = $this->module['config'];
			$set['kefuimg'] = tomedia( $set['kefuimg'] );
			$set['shophead'] = tomedia( $set['shophead'] );
			$set['shoppic'] = iunserializer( $set['shoppic'] );
			if( !empty( $set['shoppic'] ) ) {
				foreach ($set['shoppic'] as &$v) {
					$v = tomedia( $v );
				}
			}

			unset( $set['frompass'],$set['mail'] );
			$data['set'] = $set;

			if( $_GPC['did'] > 0 ) {
				$desk = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['did']));
				if( empty( $desk ) ) $this->result(1, '餐桌不存在');
				
				$data['desk'] = $desk;
			}

		}elseif( $plug == 1 ){

			$allsort = model_goodsort::getSort(1);
			
			$sort = array();
			foreach ( $allsort as $v ) {
				if( !empty( $v['down'] ) ){
					foreach ($v['down'] as $vv) {
						$sort[] = $vv;
					}
				}
			}

			$allgood = pdo_getall('zxbddiy_sitetemp_good',array('uniacid'=>$_W['uniacid'],'status'=>0,'type'=>1),array('id','price','isrule','rulearray','title','thumb','sorttwo','sales','stock','desc'));

			if( !empty( $sort ) ) {
				foreach ($sort as $k => &$v) {
					$list = array();
					foreach ( $allgood as $vv ) {

						if( $vv['sorttwo'] == $v['id'] ){
							$vv['thumb'] = tomedia( $vv['thumb'] );
							$vv['url'] = '/zxbddiy_sitetemp/pages/deskorder/good?from=exbuy&gid='.$vv['id'];
							$vv['buynum'] = 0;
							if( $vv['isrule'] ) {
								$vv['rulearray'] = iunserializer( $vv['rulearray'] );
							}

							$list[] = $vv;
						}
						
					}
					$v['good'] = $list;
				}
			}
			
			$data['allsort'] = $sort;
			

			$set = $this->module['config'];
			$set['okefuimg'] = tomedia( $set['okefuimg'] );
			$set['oshophead'] = tomedia( $set['oshophead'] );
			$set['oshoppic'] = iunserializer( $set['oshoppic'] );
			if( !empty( $set['oshoppic'] ) ) {
				foreach ($set['oshoppic'] as &$v) {
					$v = tomedia( $v );
				}
			}

			unset( $set['frompass'],$set['mail'] );
			$data['set'] = $set;

			if( $_GPC['did'] > 0 ) {
				$desk = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['did']));
				if( empty( $desk ) ) $this->result(1, '餐桌不存在');
				
				$data['desk'] = $desk;
			}

		}


		$this->result(0, '',$data);
	
	}elseif( $_GPC['op'] == 'buy' ) {
		
		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
		
		$good = json_decode( htmlspecialchars_decode( $_GPC['good'] ),true);
		if( empty( $good ) || !is_array( $good ) ) $this->result(1, '请选择商品');

		$plug = intval( $_GPC['plug'] );

		if( empty( $plug ) ){

			//营业时间
			$islimit = model_order::istimeLimit( 1,$this->module['config'] );
						
			if( $islimit['status'] ) {
				$this->result(1, $islimit['res']);
			}

			$arr = array();
			foreach ( $good as $v ) {
				
				$good = model_good::getGood( $v['id'] );
				if( empty( $good ) ) $this->result(1, '未找到商品');
				if( $good['status'] == 1 ) $this->result(1, '商品已下架');

				if( empty( $v['id'] ) ) $this->result(1, '请选择商品');
				if( empty( $v['buynum'] ) ) $this->result(1, '请选择商品数量');
				
				// 判断规格
				if( $good['isrule'] )
				{
					if( empty( $v['ruleid'] ) ) $this->result(1, '请选择商品规格');
					$isin = 0;
					foreach ( $good['rulearray']['rulemap'] as $vv ) {
						if( $vv['id'] == $v['ruleid'] ){
							$isin = 1;
							if( $vv['stock'] < $v['buynum'] ) $this->result(1, '库存不足');
						}
					}

					if( $isin == 0 ) $this->result(1, '请选择商品规格');
				}else{

					if( $good['stock'] < $v['buynum'] ) $this->result(1, $good['title'].'库存不足');
				}
				$arr[] = array('gid'=>$v['id'],'num'=>$v['buynum'],'rule'=>$v['ruleid']);
			}
			
			Util::setCache('buydata',$_W['openid'],$arr);
			// 删除历史订单记录
			Util::deleteCache('oldpay',$_W['openid']);

		}elseif( $plug == 1 ){

			//营业时间
			$islimit = model_order::istimeLimit( 2,$this->module['config'] );
			if( $islimit['status'] ) {
				$this->result(1, $islimit['res']);
			}

			$arr = array();
			foreach ( $good as $v ) {
				
				$good = model_good::getGood( $v['id'] );
				if( empty( $good ) ) $this->result(1, '未找到商品');
				if( $good['status'] == 1 ) $this->result(1, '商品已下架');

				if( empty( $v['id'] ) ) $this->result(1, '请选择商品');
				if( empty( $v['buynum'] ) ) $this->result(1, '请选择商品数量');
				
				// 判断规格
				if( $good['isrule'] )
				{
					if( empty( $v['ruleid'] ) ) $this->result(1, '请选择商品规格');
					$isin = 0;
					foreach ( $good['rulearray']['rulemap'] as $vv ) {
						if( $vv['id'] == $v['ruleid'] ){
							$isin = 1;
							if( $vv['stock'] < $v['buynum'] ) $this->result(1, '商品库存不足');
						}
					}

					if( $isin == 0 ) $this->result(1, '请选择商品规格');
				}else{

					if( $good['stock'] < $v['buynum'] ) $this->result(1, '商品库存不足');
				}
				$arr[] = array('gid'=>$v['id'],'num'=>$v['buynum'],'rule'=>$v['ruleid']);
			}
			
			Util::setCache('buydata',$_W['openid'],$arr);
			// 删除历史订单记录
			Util::deleteCache('oldpay',$_W['openid']);

		}


		$this->result(0, '好');
		
	}

	