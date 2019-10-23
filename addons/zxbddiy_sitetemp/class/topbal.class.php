<?php 



	class topbal extends plugin
	{
		

		static function goodsortList(){
			global $_W,$_GPC;
			

			$arr = array(
				'tempid' => array(
					array('value'=>0,'name'=>'未设置所属模板','url'=>self::pwUrl('shop','goodsort',array('op'=>'list','tempid'=>0)) ),
				),
			);

			$alltemp = pdo_getall('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'issystem'=>0));
			if( !empty( $alltemp ) ) {
				foreach ( $alltemp as $v ) {
					$arr['tempid'][] = array('value'=>$v['id'],'name'=>$v['name'],'url'=>self::pwUrl('shop','goodsort',array('op'=>$_GPC['op'],'tempid'=>$v['id'])) );
				}
			}

			return $arr;
		}

		static function tempList(){
			global $_W;
			
			$tempsort = model_tempsort::getSort();
			$status = array( array('value'=>'','name'=>'全部模板','url'=>self::pwUrl('site','temp',array('op'=>'sys','sort'=>$v['id'])) ) ); 

			if( !empty( $tempsort ) ) {
				foreach ($tempsort as $v) {
					array_push($status, array('value'=>$v['id'],'name'=>$v['name'],'url'=>self::pwUrl('site','temp',array('op'=>'sys','sort'=>$v['id']))) );
				}
			}
			return array(
				'sort' =>$status,
			);

		}

		static function wxappList(){
			global $_W,$_GPC;
			return array(
				'search' => array(
					array(
						'do'=>'copyright',
						'op' => $_GPC['op'],
						'p' => $_GPC['p'],
						'for' => 'name',
						'placeholder' => '输入小程序名称'
					),
				)
			);
		}

		static function mytempList(){
			global $_W,$_GPC;
			return array(
				'search' => array(
					array(
						'do'=>'temp',
						'op' => $_GPC['op'],
						'p' => $_GPC['p'],
						'for' => 'tname',
						'placeholder' => '输入模板名称'
					),
				)
			);
		}
		

		static function goodList(){
			global $_W,$_GPC;
			return array(
				'status' => array(
					array( 'value'=>'','name'=>'上架中','url'=>self::pwUrl('shop','good',array('status'=>'')) ),
					array('value'=>1,'name'=>'已下架','url'=>self::pwUrl('shop','good',array('status'=>'1')) ),
				),
				'search' => array(
					array(
						'do'=>'good',
						'op' => $_GPC['op'],
						'p' => $_GPC['p'],
						'for' => 'title',
						'placeholder' => '输入商品标题'
					),
				)
			);
		}

		static function cardList(){
			global $_W,$_GPC;
			
			$arr = array(
				'status' => array(
					array( 'value'=>'','name'=>'上架中','url'=>self::pwUrl('card','card',array('status'=>'')) ),
					array('value'=>1,'name'=>'已下架','url'=>self::pwUrl('card','card',array('status'=>'1')) ),
				),
				'type' => array(
					array( 'value'=>'','name'=>'全部类型','url'=>self::pwUrl('card','card',array('type'=>'')) ),
					array('value'=>1,'name'=>'代金券','url'=>self::pwUrl('card','card',array('type'=>'1')) ),
					array('value'=>2,'name'=>'折扣券','url'=>self::pwUrl('card','card',array('type'=>'2')) ),
				),
				'use' => array(
					array( 'value'=>'','name'=>'使用场景','url'=>self::pwUrl('card','card',array('use'=>'')) ),
					array('value'=>1,'name'=>'官网系统','url'=>self::pwUrl('card','card',array('use'=>'1')) ),
					/*array('value'=>2,'name'=>'商城系统','url'=>self::pwUrl('card','card',array('use'=>'2')) ),
					array('value'=>3,'name'=>'预约系统','url'=>self::pwUrl('card','card',array('use'=>'3')) ),*/
				),				
				'search' => array(
					array(
						'do'=>'card',
						'op' => $_GPC['op'],
						'p' => $_GPC['p'],
						'for' => 'title',
						'placeholder' => '输入卡券名称'
					),
				)
			);
			$auth = model_auth::authList();
			if( empty($auth['isshop']) ) unset( $arr['use'][2] );
			if( empty($auth['isappoint']) ) unset( $arr['use'][3] );

			return $arr;

		}

		static function orderList(){
			global $_W,$_GPC;

			return array(
				'status' => array(
					array('value'=>'','name'=>'状态筛选','url'=>WebCommon::logUrl('status','')),
					array('value'=>1,'name'=>'未支付','url'=>WebCommon::logUrl('status','1')),
					array('value'=>2,'name'=>'已支付','url'=>WebCommon::logUrl('status','2')),
					array('value'=>3,'name'=>'已完成','url'=>WebCommon::logUrl('status','3')),
				),
				'search' => array(
					array(
						'do'=>'data',
						'op' => $_GPC['op'],
						'for' => 'orderid',
						'placeholder' => '输入订单编号'
					),
					array(
						'do'=>'data',
						'op' => $_GPC['op'],
						'for' => 'uorderid',
						'placeholder' => '输入微信订单编号'
					),

				)
			);
		}


		static function userList(){
			global $_W;

			return array(
				'status' => array(
					array('value'=>'','name'=>'会员状态','url'=>WebCommon::logUrl('status','')),
					array('value'=>1,'name'=>'正常','url'=>WebCommon::logUrl('status','1')),
					array('value'=>2,'name'=>'黑名单','url'=>WebCommon::logUrl('status','2')),
				),
				'search' => array(
					array(
						'do'=>'data',
						'op' => 'user',
						'for' => 'nick',
						'placeholder' => '输入会员昵称'
					),
					array(
						'do'=>'data',
						'op' => 'user',
						'for' => 'user',
						'placeholder' => '输入会员id'
					),		
				),
			);
		}



	}


