<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

	if( $_GPC['op'] == 'info' && $_W['ispost'] ) {
		
		$card = model_card::getCard( $_GPC['cid'] );
		if( empty( $card ) || $card['status'] == 1 ) $this->result(1, '卡券不存在');

		$card['content'] = htmlspecialchars_decode( $card['content'] );
		$card['thumb'] = tomedia( $card['thumb'] );
		$card['start'] = date( 'Y.m.d',$card['start'] );
		$card['end'] = date( 'Y.m.d',$card['end'] );

		$data['card'] = $card;

		$data['set'] = array(
			'cardname' => $this->module['config']['cardname'],
			'cardimg' => tomedia( $this->module['config']['cardimg'] ),
			'cardtel' => $this->module['config']['cardtel'],
			'cardaddress' => $this->module['config']['cardaddress'],
			'cardlat' => $this->module['config']['cardlat'],
			'cardlng' => $this->module['config']['cardlng'],
			'cardbg' => $this->module['config']['cardbg'],
		);

		// 当前对此券的状态
		$where = array('uniacid'=>$_W['uniacid'],'cardid'=>$card['id'],'openid'=>$_W['openid']);
		$allgeted = pdo_getall('zxbddiy_sitetemp_cardlog',$where);

		$geted = $canuse = 0;
		if( !empty( $allgeted ) ) {
			foreach ( $allgeted as $v ) {
				$geted ++;
				if( $v['status'] == 0 && $v['end'] > TIMESTAMP ) {
					$canuse ++;
					$data['cardlogid'] = $v['id'];
				}
			}
		}
		$data['status'] = 1; // 可以领取
		if( $geted >= $card['limittime'] && $card['limittime'] > 0 ) $data['status'] = 2; // 不能再领取
		if( $canuse > 0 ) $data['status'] = 3; // 还没使用


		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'getcard' && $_W['ispost'] ) {

		$card = model_card::getCard( $_GPC['cid'] );
		if( empty( $card ) || $card['status'] == 1 ) $this->result(1, '卡券不存在');
		if( $card['start'] >= TIMESTAMP ) $this->result(1, '卡券未到可领取时间');
		if( $card['end'] <= TIMESTAMP ) $this->result(1, '卡券已过期');
		if( $card['stock'] <= 0 ) $this->result(1, '卡券已经被领完了');

		$where = array('uniacid'=>$_W['uniacid'],'cardid'=>$card['id'],'openid'=>$_W['openid']);
		$allgeted = pdo_getall('zxbddiy_sitetemp_cardlog',$where);

		$geted = $canuse = 0;
		if( !empty( $allgeted ) ) {
			foreach ( $allgeted as $v ) {
				$geted ++;
				if( $v['status'] == 0 && $v['end'] > TIMESTAMP ) {
					$canuse ++;
				}
			}
		}

		if( $card['limittime'] > 0 && $geted >= $card['limittime'] ) $this->result(1, '你已经领取过了，不能再领取'); 
		if( $canuse > 0 ) $this->result(1, '你还有卡券没有使用，不能领取'); 
	 	
		$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'cardid' => $card['id'],
			'end' => TIMESTAMP + $card['usetime']*3600*24,
			'createtime' => TIMESTAMP,
			'usetype' => $card['usetype'],
		);

		$res = pdo_insert('zxbddiy_sitetemp_cardlog',$data);
		$cardlogid = pdo_insertid();

		if( $res ) {
			Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_card',array('stock'=>-1,'taked'=>1),$card['id']);
			Util::deleteCache('card',$card['id']);
		}

		$this->result(0,'',$cardlogid);

	}elseif( $_GPC['op'] == 'hexiaoinfo' && $_W['ispost'] ) {

		$cardlog = pdo_get('zxbddiy_sitetemp_cardlog',array('id'=>$_GPC['id']));
		if( empty( $cardlog ) ) $this->result(1, '未找到领取记录');
		if( $cardlog['end'] <= TIMESTAMP  ) $this->result(1, '卡券已过期');
		if( $cardlog['status'] != 0 ) $this->result(1, '此券已使用');

		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>1));
		if( empty( $isadmin ) ) $this->result(1, '你不是核销员');

		$card = model_card::getCard( $cardlog['cardid'] );
		if( empty( $card ) ) $this->result(1, '无卡券数据');
		if( $card['usetype'] != 0 ) $this->result(1, '卡券不能核销');

		$card['thumb'] = tomedia( $card['thumb'] );
		$card['url'] = '/zxbddiy_sitetemp/pages/card/info?cid='.$card['id'];

		$data['card'] = $card;

		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'hexiao' && $_W['ispost'] ) {

		$cardlog = pdo_get('zxbddiy_sitetemp_cardlog',array('id'=>$_GPC['id']));
		if( empty( $cardlog ) ) $this->result(1, '未找到领取记录');
		if( $cardlog['end'] <= TIMESTAMP  ) $this->result(1, '卡券已过期');
		if( $cardlog['status'] != 0 ) $this->result(1, '此券已使用');

		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>1));
		if( empty( $isadmin ) ) $this->result(1, '你不是核销员');

		$res = pdo_update('zxbddiy_sitetemp_cardlog',array('status'=>1,'usetime'=>TIMESTAMP,'hexiaoer'=>$_W['openid']),array('id'=>$cardlog['id']));

		if( $res ) {
			Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_card',array('used'=>1),$cardlog['cardid']);
			Util::deleteCache('card',$cardlog['cardid']);
		}

		$this->result(0, '');
		
	}elseif( $_GPC['op'] == 'mycard' ) {

		$where = array('uniacid' => $_W['uniacid'],'openid'=>$_W['openid']);

		$order = ' `id` DESC ';

		if( $_GPC['type'] == 0 ) {
			$where['status'] = 0;
			$where['end>'] = TIMESTAMP;
		}
		if( $_GPC['type'] == 1 ) $where['status'] = 1;
		if( $_GPC['type'] == 2 ) {
			$where['end<'] = TIMESTAMP;
			$where['status'] = 0;
		}

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_cardlog',$where,$_GPC['page'],10,$order,false,false,' openid,cardid,status,end ');
		$list = $info[0];
				
		if( !empty( $list ) ) {
			foreach ($list as $k => &$v) {
				unset( $v['openid'] );
				$v['url'] = '/zxbddiy_sitetemp/pages/card/info?cid='.$v['cardid'];
				$card = model_card::getCard( $v['cardid'] );
				if( empty( $card ) ){
					unset( $list[$k] );
					continue;
				}
				$v['name'] = $card['name'];
				$v['value'] = $card['value'];
				$v['useleast'] = $card['useleast'];
				$v['type'] = $card['type'];

				if( $v['status'] == 0 && $v['end'] > TIMESTAMP ) $v['status'] = 0;
				if( $v['status'] == 1 ) $v['status'] = 1;
				if( $v['status'] == 0 && $v['end'] <= TIMESTAMP ) $v['status'] = 2;
				

				$v['time'] = date('Y-m-d',$v['end']);
			}
		}
		
		$data['list'] = $list;

		$this->result(0, '',$data);


	}elseif( $_GPC['op'] == 'list' ) {

		$where = array('uniacid' => $_W['uniacid'],'status'=>0,'end>'=>TIMESTAMP,'stock>'=>1);


		$order = ' `id` DESC ';

		if( $_GPC['type'] == 1 ) {
			$where['type'] = 0;
		}
		if( $_GPC['type'] == 2 ) {
			$where['type'] = 1;
		}

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_card',$where,$_GPC['page'],10,$order,false,false,' id,start,end,type,value,useleast,name ');
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as $k => &$v) {
				unset( $v['openid'] );
				$v['url'] = '/zxbddiy_sitetemp/pages/card/info?cid='.$v['id'];
				$v['time'] = date('Y.m.d',$v['start']).'-'.date('Y.m.d',$v['end']);
			}
		}
		
		$data['list'] = $list;

		$this->result(0, '',$data);

	}

	