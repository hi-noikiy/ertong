<?php 


class model_temp {


	static function getTemp( $tid = 'all' ){
		global $_W;
		$tid = empty( $tid ) ? 'all' : $tid;
		
		$cache = Util::getCache('temp',$tid);

		if( empty( $cache ) ){
			if( $tid == 'all' ) {
				$where = array('uniacid'=>$_W['uniacid'],'isact'=>1);
			}else{
				$where = array('id'=>$tid);
			}

			$cache = pdo_get('zxbddiy_sitetemp_temp',$where,array('id','name'));
							
			if( empty( $cache ) ) {
				$where = array('uniacid'=>$_W['uniacid']);
				$cache = pdo_get('zxbddiy_sitetemp_temp',$where,array('id','name'));
			}

			if( !empty( $cache ) ){
				Util::setCache('temp',$tid,$cache);
			}
		}
		return $cache;
	}
	
	
	static function getBar( $tid = 'all' ){
		global $_W;
		$tid = empty( $tid ) ? 'all' : $tid;

		$cache = Util::getCache('botbar',$tid);
		
		if( empty( $cache ) ){
			$temp = self::getTemp( $tid );
			if( !empty( $temp['id'] ) ) {

				$cache = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$temp['id']));
				if( !empty( $cache ) ) {
					$cache['data'] = iunserializer( $cache['data'] );
				}
			}
			
			Util::setCache('botbar',$tid,$cache);
		}
		return $cache;
	}
	
	// 插入模板
	static function initTemp(){
		global $_W;

		// 电镀厂
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'电镀厂系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '电镀厂系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/dd_thumb.png',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertddPage( $tid );
		}

		// 互联网
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'网络公司系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '网络公司系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/wl_thumb.png',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertwlPage( $tid );
		}

		// 普通商城
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'普通商城系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '普通商城系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/sc_thumb.jpg',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertshopPage( $tid );
		}		

		// 点餐
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'在线点餐系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '在线点餐系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/exbuy.jpg',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertexbuyPage( $tid );
		}

		// 广告招牌
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'广告招牌系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '广告招牌系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/ad.jpg',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertadPage( $tid );
		}		


		// 印刷
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'印刷公司系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '印刷公司系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/yinshua/bg.jpg',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertyinshuaPage( $tid );
		}	

		//美发预约
		$isset = pdo_get('zxbddiy_sitetemp_temp',array('name'=>'美发预约系统模板','issystem'=>1,'uniacid'=>0));
		if( empty( $isset ) ) {

			$tdata = array(
				'uniacid' => 0,
				'name' => '美发预约系统模板',
				'number' => 1,
				'img' => '/addons/zxbddiy_sitetemp/public/images/meifa/bg.png',
				'issystem' => 1,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();
			self::insertmeifaPage( $tid );
		}	


	}

	static function insertmeifaPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$mf1 = 'http://opw8efxun.bkt.clouddn.com/1500482387.jpg';
			$mf2 = 'http://opw8efxun.bkt.clouddn.com/1500482449.jpg';
			$mf3 = 'http://opw8efxun.bkt.clouddn.com/1500482459.jpg';

			$mf4 = 'http://opw8efxun.bkt.clouddn.com/1500483495.JPG';
			$mf5 = 'http://opw8efxun.bkt.clouddn.com/1500527944.JPG';
			$mf6 = 'http://opw8efxun.bkt.clouddn.com/1500527953.JPG';
			$mf7 = 'http://opw8efxun.bkt.clouddn.com/1500529363.JPG';

			$mf8 = 'http://opw8efxun.bkt.clouddn.com/1500483591.JPG';
			$mf9 = 'http://opw8efxun.bkt.clouddn.com/1500527970.JPG';
			$mf10 = 'http://opw8efxun.bkt.clouddn.com/1500527976.JPG';

			$mf11 = 'http://opw8efxun.bkt.clouddn.com/1500883722.JPG';
			$mf12 = 'http://opw8efxun.bkt.clouddn.com/1500883733.JPG';
			$mf13 = 'http://opw8efxun.bkt.clouddn.com/1500883738.JPG';

			$mf14 = 'http://opw8efxun.bkt.clouddn.com/1502958559.jpg';
			$mf15 = 'http://opw8efxun.bkt.clouddn.com/1502958605.jpg';
			$mf16 = 'http://opw8efxun.bkt.clouddn.com/1502958636.jpg';			

			$loc = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/loc.png';


			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/index1.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/index2.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/zuopin1.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/zuopin2.png';
			$foot5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/my1.png';
			$foot6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/meifa/my2.png';


			
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '预约',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"预约";s:5:"title";s:6:"预约";s:10:"sharetitle";s:6:"预约";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#99499c";s:6:"pagebg";s:7:"#eeeeee";s:8:"topcolor";s:7:"#ffffff";}s:4:"data";a:4:{i:0;a:3:{s:2:"id";s:14:"m1512996245525";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#dddddd";s:8:"actcolor";s:7:"#400080";s:9:"showpoint";i:0;s:4:"data";a:3:{i:0;a:3:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$mf1.'";s:4:"type";s:3:"url";}i:1;a:3:{s:2:"id";s:14:"g1512996251148";s:3:"img";s:80:"'.$mf2.'";s:4:"type";s:3:"url";}i:2;a:3:{s:2:"id";s:14:"g1512996287869";s:3:"img";s:80:"'.$mf3.'";s:4:"type";s:3:"url";}}}}i:1;a:3:{s:2:"id";s:14:"m1513003065070";s:4:"name";s:6:"toshop";s:6:"params";a:11:{s:4:"name";s:18:"来美发创意园";s:7:"address";s:33:"广东省深圳市南山路111号";s:7:"bgcolor";s:7:"#ffffff";s:7:"padding";i:10;s:4:"type";s:3:"map";s:8:"topcolor";s:7:"#333333";s:8:"botcolor";s:7:"#999999";s:3:"loc";s:61:"'.$loc.'";s:3:"lng";d:113.80874633789062;s:3:"lat";d:23.039929622645793;s:7:"addname";s:18:"来美发创意园";}}i:2;a:3:{s:2:"id";s:14:"m1512996316965";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#f3f4f5";}}i:3;a:3:{s:2:"id";s:14:"m1512997182174";s:4:"name";s:7:"appoint";s:6:"params";a:12:{s:7:"bgcolor";s:7:"#eeeeee";s:5:"style";i:1;s:7:"botfont";s:0:"";s:8:"paddingv";i:5;s:7:"botbord";s:7:"#333333";s:8:"botcolor";s:7:"#333333";s:10:"topbgcolor";s:7:"#f7edec";s:8:"topcolor";s:7:"#9a4e5a";s:10:"pricecolor";s:7:"#f15445";s:11:"fontbgcolor";s:7:"#98499c";s:9:"fontcolor";s:7:"#ffffff";s:4:"data";a:3:{i:0;a:10:{s:2:"id";s:14:"g1512997186359";s:5:"thumb";s:52:"";s:4:"name";s:18:"辉仔（总监）";s:4:"desc";s:27:"总监发型师，日韩风";s:3:"num";s:3:"337";s:5:"price";s:5:"68.00";s:5:"ispay";s:1:"1";s:3:"img";s:80:"http://127.0.0.6/attachment/images/59/2017/12/DHYZW7WLwzdH5o5cwlu4YZdlhOL9cy.jpg";s:5:"title";s:18:"辉仔（总监）";s:3:"gid";s:1:"6";}i:1;a:10:{s:2:"id";s:14:"g1512997187214";s:5:"thumb";s:52:"images/59/2017/12/p2C4CqlRr3mRRwvc2y2lv4rQlrhQLM.jpg";s:4:"name";s:6:"阿伊";s:4:"desc";s:15:"总监设计师";s:3:"num";s:3:"321";s:5:"price";s:5:"68.00";s:5:"ispay";s:1:"1";s:3:"img";s:80:"http://127.0.0.6/attachment/images/59/2017/12/p2C4CqlRr3mRRwvc2y2lv4rQlrhQLM.jpg";s:5:"title";s:6:"阿伊";s:3:"gid";s:1:"7";}i:2;a:10:{s:2:"id";s:14:"g1512997188038";s:5:"thumb";s:52:"images/59/2017/12/HV9DKid5DdDKD5OQduH22OmiduSmUD.jpg";s:4:"name";s:6:"阿达";s:4:"desc";s:6:"总监";s:3:"num";s:3:"123";s:5:"price";s:5:"68.00";s:5:"ispay";s:1:"1";s:3:"img";s:80:"http://127.0.0.6/attachment/images/59/2017/12/HV9DKid5DdDKD5OQduH22OmiduSmUD.jpg";s:5:"title";s:6:"阿达";s:3:"gid";s:1:"8";}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$productid = pdo_insertid();

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '作品',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"作品";s:5:"title";s:6:"作品";s:10:"sharetitle";s:6:"作品";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#99499c";s:6:"pagebg";s:7:"#eeeeee";s:8:"topcolor";s:7:"#ffffff";}s:4:"data";a:5:{i:0;a:3:{s:2:"id";s:14:"m1512996343580";s:4:"name";s:5:"image";s:6:"params";a:8:{s:7:"padding";i:10;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:7:"#ffffff";s:7:"fonttop";i:3;s:4:"data";a:2:{i:0;a:6:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$mf4.'";s:3:"url";s:0:"";s:5:"title";s:18:"男士时尚发型";s:4:"type";s:6:"images";s:10:"imagesdata";a:4:{i:0;a:2:{s:2:"id";s:15:"i01512997340727";s:3:"url";s:80:"'.$mf4.'";}i:1;a:2:{s:2:"id";s:15:"i11512997340727";s:3:"url";s:80:"'.$mf5.'";}i:2;a:2:{s:2:"id";s:15:"i21512997340727";s:3:"url";s:80:"'.$mf6.'";}i:3;a:2:{s:2:"id";s:15:"i31512997340727";s:3:"url";s:80:"'.$mf7.'";}}}i:1;a:6:{s:2:"id";s:15:"m01512996345068";s:3:"img";s:80:"'.$mf8.'";s:4:"type";s:6:"images";s:3:"url";s:0:"";s:5:"title";s:18:"气质美女发型";s:10:"imagesdata";a:3:{i:0;a:2:{s:2:"id";s:15:"i01512997357094";s:3:"url";s:80:"'.$mf8.'";}i:1;a:2:{s:2:"id";s:15:"i01512997379271";s:3:"url";s:80:"'.$mf9.'";}i:2;a:2:{s:2:"id";s:15:"i11512997379271";s:3:"url";s:80:"'.$mf10.'";}}}}}}i:1;a:3:{s:2:"id";s:14:"m1512996428036";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:10;s:7:"bgcolor";s:7:"#f3f4f5";}}i:2;a:3:{s:2:"id";s:14:"m1512996439188";s:4:"name";s:5:"image";s:6:"params";a:8:{s:7:"padding";i:10;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:7:"fonttop";i:3;s:4:"data";a:2:{i:0;a:6:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$mf11.'";s:3:"url";s:0:"";s:5:"title";s:12:"美女造型";s:4:"type";s:6:"images";s:10:"imagesdata";a:3:{i:0;a:2:{s:2:"id";s:15:"i01512997399173";s:3:"url";s:80:"'.$mf11.'";}i:1;a:2:{s:2:"id";s:15:"i01512997411148";s:3:"url";s:80:"'.$mf12.'";}i:2;a:2:{s:2:"id";s:15:"i11512997411148";s:3:"url";s:80:"'.$mf13.'";}}}i:1;a:6:{s:2:"id";s:15:"m01512996447780";s:3:"img";s:80:"'.$mf14.'";s:4:"type";s:6:"images";s:3:"url";s:0:"";s:5:"title";s:12:"韩式造型";s:10:"imagesdata";a:1:{i:0;a:2:{s:2:"id";s:15:"i01512997422653";s:3:"url";s:80:"'.$mf14.'";}}}}}}i:3;a:3:{s:2:"id";s:14:"m1512996476148";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#f3f4f5";}}i:4;a:3:{s:2:"id";s:14:"m1512996489316";s:4:"name";s:5:"image";s:6:"params";a:8:{s:7:"padding";i:10;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:7:"fonttop";i:3;s:4:"data";a:2:{i:0;a:6:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$mf15.'";s:3:"url";s:0:"";s:5:"title";s:6:"长发";s:4:"type";s:6:"images";s:10:"imagesdata";a:1:{i:0;a:2:{s:2:"id";s:15:"i01512997431165";s:3:"url";s:80:"'.$mf15.'";}}}i:1;a:6:{s:2:"id";s:15:"m01512996490452";s:3:"img";s:80:"'.$mf16.'";s:4:"type";s:6:"images";s:3:"url";s:0:"";s:5:"title";s:6:"颜色";s:10:"imagesdata";a:1:{i:0;a:2:{s:2:"id";s:15:"i01512997449861";s:3:"url";s:80:"'.$mf16.'";}}}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$contactid = pdo_insertid();

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:8:{s:3:"num";s:1:"3";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#999";s:8:"actcolor";s:7:"#99499c";s:10:"paddingtop";s:1:"5";s:10:"paddingbot";s:1:"3";s:4:"data";a:3:{i:0;a:8:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"预约";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=455";s:4:"type";s:3:"url";s:6:"pageid";s:3:"455";s:7:"urlname";s:6:"预约";}i:1;a:8:{s:2:"id";s:8:"00000002";s:4:"name";s:6:"作品";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=456";s:4:"type";s:3:"url";s:6:"pageid";s:3:"456";s:7:"urlname";s:6:"作品";}i:2;a:8:{s:2:"id";s:8:"00000003";s:4:"name";s:6:"我的";s:3:"img";s:80:"'.$foot5.'";s:6:"actimg";s:80:"'.$foot6.'";s:3:"url";s:43:"/zxbddiy_sitetemp/pages/appoint/olist?tid=162";s:4:"type";s:3:"url";s:6:"pageid";s:2:"-1";s:7:"urlname";s:12:"我的预约";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}


	static function insertyinshuaPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$ad1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/b1.png';
			$ad2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/a1.png';
			$ad3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/a2.png';
			$ad4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/a3.png';
			$ad5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/a4.png';

			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/1.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/2.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/3.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/yinshua/4.png';

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '首页',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"首页";s:5:"title";s:6:"首页";s:10:"sharetitle";s:6:"首页";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:6:"pagebg";s:7:"#eeeeee";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:7:{i:0;a:3:{s:2:"id";s:14:"m1511768575417";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#dddddd";s:8:"actcolor";s:7:"#585656";s:9:"showpoint";i:0;s:4:"data";a:2:{i:0;a:3:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad1.'";s:4:"type";s:3:"url";}i:1;a:3:{s:2:"id";s:14:"g1511768599890";s:3:"img";s:80:"'.$ad1.'";s:4:"type";s:3:"url";}}}}i:1;a:3:{s:2:"id";s:14:"m1511768607048";s:4:"name";s:5:"title";s:6:"params";a:15:{s:7:"content";s:12:"公司简介";s:8:"paddingv";i:10;s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:6:"center";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"url";s:3:"url";s:0:"";}}i:2;a:3:{s:2:"id";s:14:"m1511770929432";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:2;s:7:"bgcolor";s:7:"#f3f4f5";}}i:3;a:3:{s:2:"id";s:14:"m1511770906802";s:4:"name";s:5:"title";s:6:"params";a:15:{s:7:"content";s:157:"专业印刷无碳联单 ，表格 ，彩卡/吊牌 不干胶标签 ，宣传册，说明书，公司简介。如有需要请联系：15013704507　刘先生";s:8:"paddingv";i:0;s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"url";s:3:"url";s:0:"";}}i:4;a:3:{s:2:"id";s:14:"m1511770968376";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:9;s:7:"bgcolor";s:7:"#ffffff";}}i:5;a:3:{s:2:"id";s:14:"m1511768921278";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad2.'";s:3:"url";s:0:"";s:5:"title";s:12:"不干标签";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511768922798";s:3:"img";s:80:"'.$ad3.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:12:"表格印刷";}}}}i:6;a:3:{s:2:"id";s:14:"m1511768939022";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad4.'";s:3:"url";s:0:"";s:5:"title";s:18:"无碳复写联单";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511768940310";s:3:"img";s:80:"'.$ad5.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:9:"说明书";}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$productid = pdo_insertid();

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '预约',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"预约";s:5:"title";s:6:"预约";s:10:"sharetitle";s:6:"预约";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:6:"pagebg";s:7:"#eeeeee";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:3:{i:0;a:3:{s:2:"id";s:14:"m1511771415893";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:32;s:7:"bgcolor";s:7:"#f3f4f5";}}i:1;a:3:{s:2:"id";s:14:"m1511771272245";s:4:"name";s:4:"form";s:6:"params";a:5:{s:7:"bgcolor";s:7:"#ffffff";s:7:"padding";i:5;s:5:"btnbg";s:4:"#333";s:8:"btncolor";s:4:"#fff";s:4:"data";a:4:{i:0;a:5:{s:2:"id";s:8:"00000001";s:4:"type";s:5:"input";s:4:"name";s:6:"姓名";s:3:"pla";s:0:"";s:6:"ismust";i:0;}i:1;a:5:{s:2:"id";s:14:"m1511771296014";s:4:"type";s:5:"input";s:4:"name";s:12:"联系电话";s:3:"pla";s:0:"";s:4:"data";a:0:{}}i:2;a:5:{s:2:"id";s:14:"m1511771298398";s:4:"type";s:5:"input";s:4:"name";s:9:"微信号";s:3:"pla";s:0:"";s:4:"data";a:0:{}}i:3;a:5:{s:2:"id";s:14:"m1511771300943";s:4:"type";s:4:"time";s:4:"name";s:12:"预约时间";s:3:"pla";s:12:"选择时间";s:4:"data";a:0:{}}}}}i:2;a:3:{s:2:"id";s:14:"m1511771392013";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:26;s:7:"bgcolor";s:7:"#ffffff";}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$contactid = pdo_insertid();

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:8:{s:3:"num";s:1:"4";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:8:"actcolor";s:4:"#333";s:10:"paddingtop";s:1:"6";s:10:"paddingbot";s:1:"3";s:4:"data";a:4:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"首页";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot1.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=392";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:50";s:6:"pageid";s:3:"392";s:7:"urlname";s:6:"首页";}i:1;a:8:{s:2:"id";s:8:"00000002";s:4:"name";s:6:"电话";s:3:"img";s:80:"'.$foot2.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:0:"";s:4:"type";s:3:"tel";s:9:"$$hashKey";s:9:"object:51";s:3:"tel";s:11:"13111112222";}i:2;a:9:{s:2:"id";s:15:"m01511770538621";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot3.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=393";s:4:"name";s:6:"预约";s:4:"type";s:3:"url";s:9:"$$hashKey";s:10:"object:103";s:6:"pageid";s:3:"393";s:7:"urlname";s:6:"预约";}i:3;a:9:{s:2:"id";s:15:"m11511770538621";s:3:"img";s:80:"'.$foot4.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:0:"";s:4:"name";s:6:"导航";s:4:"type";s:3:"map";s:9:"$$hashKey";s:10:"object:104";s:3:"lng";s:18:"113.75450134277344";s:3:"lat";s:18:"22.924247230648326";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}


	static function insertadPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$ad1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/123123_02.jpg';
			$ad2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/123_04.jpg';
			$ad3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/123123_07.jpg';
			$ad4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/123123_09.jpg';

			$ad5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/321321123_03.jpg';
			$ad6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/321321123_05.jpg';
			$ad7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/321321123_09.jpg';
			$ad8 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/321321123_10.jpg';
			$ad9 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/gewgwe32_03.jpg';
			$ad10 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/gewgwe32_05.jpg';

			$ad11 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/wqrwqrq_02.jpg';
			$ad12 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/wqrwqrq_05.jpg';
			$ad13 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/wqrwqrq_08.jpg';
			$ad14 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/423141241_02.jpg';
			$ad15 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/23131_02.jpg';


			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/ad_2.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/ad_1.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/ad_4.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ad/ad_3.png';


			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '业务展示',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"业务展示";s:5:"title";s:12:"曙光广告";s:10:"sharetitle";s:12:"曙光广告";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:6:"pagebg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:12:{i:0;a:3:{s:2:"id";s:14:"m1511755322279";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad1.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:1;a:3:{s:2:"id";s:14:"m1511762869041";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad2.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:2;a:3:{s:2:"id";s:14:"m1511762886280";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad3.'";s:3:"url";s:0:"";s:5:"title";s:18:"精品led发光字";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511762889273";s:3:"img";s:80:"'.$ad4.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:18:"亚克力发光字";}}}}i:3;a:3:{s:2:"id";s:14:"m1511762937472";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad5.'";s:3:"url";s:0:"";s:5:"title";s:12:"广告立牌";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511762940360";s:3:"img";s:80:"'.$ad6.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:12:"招牌安装";}}}}i:4;a:3:{s:2:"id";s:14:"m1511762951976";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad7.'";s:3:"url";s:0:"";s:5:"title";s:12:"亮化工程";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511762953417";s:3:"img";s:80:"'.$ad8.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:15:"展架易拉宝";}}}}i:5;a:3:{s:2:"id";s:14:"m1511762969592";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:4;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad9.'";s:3:"url";s:0:"";s:5:"title";s:12:"高空安装";s:4:"type";s:3:"url";}i:1;a:5:{s:2:"id";s:15:"m01511762972736";s:3:"img";s:80:"'.$ad10.'";s:4:"type";s:3:"url";s:3:"url";s:0:"";s:5:"title";s:15:"各种标志牌";}}}}i:6;a:3:{s:2:"id";s:14:"m1511763378790";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad11.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:7;a:3:{s:2:"id";s:14:"m1511763416406";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad12.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:8;a:3:{s:2:"id";s:14:"m1511763444236";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad13.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:9;a:3:{s:2:"id";s:14:"m1511763546932";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:13;s:7:"bgcolor";s:7:"#f9f9f9";}}i:10;a:3:{s:2:"id";s:14:"m1511763498212";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad14.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}i:11;a:3:{s:2:"id";s:14:"m1511763875826";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$ad15.'";s:3:"url";s:0:"";s:5:"title";s:0:"";s:4:"type";s:3:"url";}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$productid = pdo_insertid();

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '联系我们',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:9:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"联系我们";s:5:"title";s:12:"联系我们";s:10:"sharetitle";s:12:"联系我们";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";s:6:"pagebg";s:7:"#ffffff";}s:4:"data";a:5:{i:0;a:3:{s:2:"id";s:14:"m1511764304775";s:4:"name";s:5:"title";s:6:"params";a:15:{s:7:"content";s:19:"联系人：X先生";s:8:"paddingv";s:2:"10";s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"url";s:3:"url";s:0:"";}}i:1;a:3:{s:2:"id";s:14:"m1511764337990";s:4:"name";s:5:"title";s:6:"params";a:16:{s:7:"content";s:28:"联系电话：0755-88888888";s:8:"paddingv";s:2:"10";s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"tel";s:3:"url";s:0:"";s:3:"tel";s:13:"0755-88888888";}}i:2;a:3:{s:2:"id";s:14:"m1511764361582";s:4:"name";s:5:"title";s:6:"params";a:16:{s:7:"content";s:26:"手机号码：13111111111";s:8:"paddingv";s:2:"10";s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"tel";s:3:"url";s:0:"";s:3:"tel";s:11:"13111111111";}}i:3;a:3:{s:2:"id";s:14:"m1511764384303";s:4:"name";s:5:"title";s:6:"params";a:17:{s:7:"content";s:51:"联系地址：深圳市南山区南山大道111号";s:8:"paddingv";s:2:"10";s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:14;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:4:"type";s:3:"map";s:3:"url";s:0:"";s:3:"lng";d:113.78677368164062;s:3:"lat";d:22.92930645370021;}}i:4;a:3:{s:2:"id";s:14:"m1511764423198";s:4:"name";s:3:"btn";s:6:"params";a:12:{s:3:"mbg";s:7:"#ffffff";s:7:"padding";i:0;s:7:"bgcolor";s:7:"#ed414a";s:5:"color";s:7:"#ffffff";s:6:"height";i:39;s:5:"width";i:96;s:4:"size";i:14;s:6:"radius";i:5;s:4:"type";s:3:"map";s:4:"text";s:18:"点击查看地图";s:3:"lng";d:113.7249755859375;s:3:"lat";d:22.925512054125306;}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$contactid = pdo_insertid();

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:8:{s:3:"num";s:1:"2";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ed2323";s:5:"color";s:7:"#ffffff";s:8:"actcolor";s:7:"#ede123";s:10:"paddingtop";s:1:"5";s:10:"paddingbot";s:1:"3";s:4:"data";a:2:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:12:"业务展示";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=373";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:50";s:6:"pageid";s:3:"373";s:7:"urlname";s:12:"业务展示";}i:1;a:9:{s:2:"id";s:8:"00000002";s:4:"name";s:12:"联系我们";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid=372";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:51";s:6:"pageid";s:3:"372";s:7:"urlname";s:1:"1";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}


	static function insertexbuyPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cl.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cm.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cn.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/co.png';

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:8:{s:3:"num";s:1:"2";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#999";s:8:"actcolor";s:7:"#ed414a";s:10:"paddingtop";s:1:"5";s:10:"paddingbot";s:1:"3";s:4:"data";a:2:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"主页";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:33:"/zxbddiy_sitetemp/pages/deskorder/exbuy";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:50";s:6:"pageid";s:2:"-1";s:7:"urlname";s:12:"点餐页面";}i:1;a:9:{s:2:"id";s:15:"m01511426822797";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:41:"/zxbddiy_sitetemp/pages/deskorder/orderlist";s:4:"name";s:12:"我的订单";s:4:"type";s:3:"url";s:9:"$$hashKey";s:10:"object:100";s:6:"pageid";s:2:"-1";s:7:"urlname";s:12:"订单列表";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);

		}

	}


	static function insertshopPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$dd1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/stemp.png';


			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cl.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cm.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/fenlei_unselected.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/fenlei_selected.png';
			$foot5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cj.png';
			$foot6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/ck.png';
			$foot7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/cn.png';
			$foot8 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/co.png';

			$good = pdo_getall('zxbddiy_sitetemp_good',array('uniacid'=>$_W['uniacid'],'status'=>0));

			$params = 'a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"主页";s:5:"title";s:6:"主页";s:10:"sharetitle";s:6:"主页";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#eeeeee";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:3:{i:0;a:3:{s:2:"id";s:14:"m1510909675805";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#dddddd";s:8:"actcolor";s:7:"#ff0080";s:9:"showpoint";i:0;s:4:"data";a:1:{i:0;a:3:{s:2:"id";s:8:"00000001";s:3:"img";s:63:"'.$dd1.'";s:4:"type";s:3:"url";}}}}i:1;a:3:{s:2:"id";s:14:"m1510918851588";s:4:"name";s:11:"searchgoods";s:6:"params";a:4:{s:7:"bgcolor";s:7:"#eeeeee";s:8:"paddingv";i:10;s:8:"paddingh";i:10;s:3:"pla";s:12:"搜索商品";}}i:2;a:3:{s:2:"id";s:14:"m1510925874306";s:4:"name";s:5:"goods";s:6:"params";a:6:{s:7:"bgcolor";s:4:"#eee";s:5:"style";i:2;s:7:"botfont";s:0:"";s:8:"paddingv";i:0;s:8:"paddings";i:5;s:4:"data";a:2:{i:0;a:7:{s:2:"id";s:14:"g1510925889802";s:5:"thumb";s:85:"http://img.alicdn.com/imgextra/i1/12573474/TB2Bo0cXaLR5uJjSZFjXXaYTFXa_!!12573474.jpg";s:5:"title";s:76:"分期0首付 Apple/苹果 iPhone 8 Plus 苹果8p 手机国行原封现货 X";s:5:"price";s:4:"1.00";s:8:"oldprice";s:7:"4957.58";s:5:"sales";s:4:"2603";s:3:"gid";s:3:"160";}i:1;a:7:{s:2:"id";s:14:"g1510925890425";s:5:"thumb";s:89:"http://img.alicdn.com/imgextra/i1/2996023380/TB1LX7vc0rJ8KJjSspaXXXuKpXa_!!0-item_pic.jpg";s:5:"title";s:90:"体温计电子温度计家用儿童精准婴儿宝宝体温表红外线耳温枪额温枪";s:5:"price";s:5:"79.00";s:8:"oldprice";s:6:"158.00";s:5:"sales";s:5:"22920";s:3:"gid";s:3:"158";}}}}}}';

			$arr = iunserializer( $params );
						
			if( count($good) >= 2 ) {
				
				foreach ( $arr['data'][2]['params']['data'] as $k => &$v ) {
					if( $k > 1 ) break;
					$v['gid'] = $good[$k]['id'];
					$v['title'] = $good[$k]['title'];
					$v['thumb'] = tomedia( $good[$k]['thumb'] );
					$v['price'] = $good[$k]['price'];
					$v['oldprice'] = $good[$k]['oldprice'];
					$v['sales'] = $good[$k]['sales'];
				}

			}else{
				unset( $arr['data'][2] );
			}
			$params = iserializer( $arr );

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '主页',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$indexid = pdo_insertid();

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:6:{s:3:"num";s:1:"4";s:7:"padding";s:1:"8";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#999";s:8:"actcolor";s:7:"#ed414a";s:4:"data";a:4:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"主页";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:39:"/zxbddiy_sitetemp/pages/page/page?pid='.$indexid.'";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:49";s:6:"pageid";s:3:"'.$indexid.'";s:7:"urlname";s:6:"主页";}i:1;a:8:{s:2:"id";s:8:"00000002";s:4:"name";s:6:"分类";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:33:"/zxbddiy_sitetemp/pages/gsort/gsort";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:50";s:7:"urlname";s:12:"商品分类";}i:2;a:8:{s:2:"id";s:15:"m01510908374151";s:3:"img";s:80:"'.$foot5.'";s:6:"actimg";s:80:"'.$foot6.'";s:3:"url";s:31:"/zxbddiy_sitetemp/pages/cart/cart";s:4:"name";s:9:"购物车";s:4:"type";s:3:"url";s:9:"$$hashKey";s:10:"object:101";s:7:"urlname";s:9:"购物车";}i:3;a:8:{s:2:"id";s:15:"m01510908374742";s:3:"img";s:80:"'.$foot7.'";s:6:"actimg";s:80:"'.$foot8.'";s:3:"url";s:31:"/zxbddiy_sitetemp/pages/user/user";s:4:"name";s:12:"个人中心";s:4:"type";s:3:"url";s:9:"$$hashKey";s:10:"object:135";s:7:"urlname";s:12:"个人中心";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}



	static function insertddPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$dd1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_1.jpg';
			$dd2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_2.jpg';
			$dd3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_3.jpg';
			$dd4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_4.jpg';
			$dd5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_5.jpg';
			$dd6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_6.jpg';
			$dd7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_7.jpg';

			$ddn1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_n1.png';
			$ddn2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_n2.png';
			$ddn3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_n3.png';
			$ddn4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/dd_n4.png';

			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/home1.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/home2.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/tel1.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/tel2.png';
			$foot5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/location1.png';
			$foot6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/location2.png';
			$foot7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/share1.png';
			$foot8 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/share2.png';


			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '关于我们',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"关于我们";s:5:"title";s:12:"关于我们";s:10:"sharetitle";s:12:"关于我们";s:8:"shareimg";s:80:"'.$dd4.'";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:1:{i:0;a:3:{s:2:"id";s:14:"m1507454374359";s:4:"name";s:4:"text";s:6:"params";a:4:{s:7:"bgcolor";s:7:"#ffffff";s:6:"margin";i:0;s:7:"padding";i:5;s:7:"content";s:4692:"%3Cdiv%20style%3D%22font-size%3A14px%3Bline-height%3A26px%3B%22%3E%0A%09%E4%B8%9C%E8%8E%9E%E5%B8%82%E7%9B%88%E5%B3%B0%E6%96%B0%E6%9D%90%E6%96%99%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8%E4%BD%8D%E4%BA%8E%E4%B8%9C%E8%8E%9E%E5%B8%82%E8%99%8E%E9%97%A8%E9%95%87%E3%80%82%E4%B8%BB%E8%A6%81%E6%98%AF%E4%BB%A5ABS%E5%A1%91%E8%83%B6%E6%B0%B4%E7%94%B5%E9%95%80%E3%80%81%E7%9C%9F%E7%A9%BAUV%E7%94%B5%E9%95%80%EF%BC%8C%E6%A8%A1%E5%85%B7%E5%BC%80%E5%8F%91%EF%BC%8C%E6%B3%A8%E5%A1%91%E5%8A%A0%E5%B7%A5%E6%88%90%E5%9E%8B%E4%B8%BA%E4%B8%80%E4%BD%93%E7%9A%84%E6%9C%8D%E5%8A%A1%E5%85%AC%E5%8F%B8%EF%BC%8C%E7%8E%B0%E6%8B%A5%E6%9C%89%E5%9B%BD%E5%86%85%E5%A4%96%E5%85%88%E8%BF%9B%E7%9A%84%E8%87%AA%E5%8A%A8%E7%94%B5%E9%95%80%E7%94%9F%E4%BA%A7%E7%BA%BF%EF%BC%8C%E7%94%9F%E4%BA%A7%E8%BD%A6%E9%97%B4%E5%8D%A0%E5%9C%B0%E9%9D%A2%E7%A7%AF%E4%B8%80%E5%8D%83%E5%A4%9A%E5%B9%B3%E7%B1%B3%EF%BC%8C%E6%8A%80%E6%9C%AF%E4%BA%BA%E5%91%98%E4%BA%8C%E5%8D%81%E5%A4%9A%E4%BA%BA%EF%BC%8C%E5%B9%B6%E9%80%9A%E8%BF%87ISO%3A14000%E8%AE%A4%E8%AF%81%E4%BC%81%E4%B8%9A%EF%BC%8C%20%E4%BB%A5%E5%8F%8ATS6949%E8%B4%A8%E9%87%8F%E7%AE%A1%E7%90%86%E4%BD%93%E7%B3%BB%E7%9A%84%E8%AE%A4%E8%AF%81%EF%BC%8C%E6%9C%89%E5%85%A8%E8%87%AA%E5%8A%A8%E5%92%8C%E5%A1%91%E8%83%B6%E6%B0%B4%E7%94%B5%E9%95%80%E7%94%9F%E4%BA%A7%E7%BA%BF%E5%90%84%E4%B8%80%E6%9D%A1%EF%BC%8C%E5%85%A8%E8%87%AA%E5%8A%A8%E5%92%8C%E9%95%AD%E9%9B%95%E6%9C%BA%E3%80%82%E5%8F%AF%E4%B8%BA%E5%AE%A2%E6%88%B7%E6%8F%90%E4%BE%9B%EF%BC%9A%E6%A8%A1%E5%85%B7%E5%BC%80%E5%8F%91%E3%80%81%E6%B3%A8%E5%A1%91%E3%80%81%E7%94%B5%E9%95%80%EF%BC%8C%E9%95%AD%E9%9B%95%E7%AD%89%E4%BA%A7%E5%93%81%E7%9A%84%E6%9C%8D%E5%8A%A1%E3%80%82%3Cbr%2F%3E%E5%85%AC%E5%8F%B8%E6%9C%AC%E7%9D%80%E9%AB%98%E6%95%88%EF%BC%8C%E8%8A%82%E8%83%BD%EF%BC%8C%E7%8E%AF%E4%BF%9D%EF%BC%8C%E5%85%B1%E8%B5%A2%E7%9A%84%E5%8F%91%E5%B1%95%E6%96%B9%E9%92%88%EF%BC%8C%E6%8B%A5%E6%9C%89%E8%AF%B8%E5%A4%9A%E7%9A%84%E5%A1%91%E8%83%B6%E7%94%B5%E9%95%80%E4%B8%93%E4%B8%9A%E4%BA%BA%E6%89%8D%E3%80%82%E4%BA%A7%E5%93%81%E5%AE%8C%E5%85%A8%E6%BB%A1%E8%B6%B3%E5%AE%A2%E6%88%B7%E7%9A%84%E5%93%81%E8%B4%A8%E5%8F%8A%E7%8E%AF%E4%BF%9D%E8%A6%81%E6%B1%82%E3%80%82%3Cbr%2F%3E%3Ccenter%3E%3Cimg%20src%3D%22http%3A%2F%2Flogin.114my.cn%2Fmemberpic%2Fyingfengdg10060wap%2Fuploadfile%2Fimage%2F20170904%2F20170904104606_20202.jpg%22%20alt%3D%22%22%2F%3E%3C%2Fcenter%3E%3Cbr%2F%3E%E7%9B%88%E5%B3%B0%E5%8A%A0%E5%B7%A5%E9%95%80%E7%A7%8D%E4%BB%A5%EF%BC%9A%E5%85%89%E9%93%AC%E3%80%81%E9%93%B6%E8%89%B2%E3%80%8124K%E9%87%91%E8%89%B2%E3%80%81%E9%98%B2%E9%87%91%E3%80%81%E4%B8%89%E4%BB%B7%E7%8E%AF%E4%BF%9D%E9%93%AC%E3%80%81%E4%B8%89%E4%BB%B7%E9%BB%91%E9%93%AC%E3%80%81%E6%9E%AA%E8%89%B2%E3%80%81%E7%8F%8D%E7%8F%A0%E9%93%AC%E3%80%81%E4%BB%A3%E9%93%AC%E3%80%81%E5%8D%8A%E5%85%89%E9%93%AC%E3%80%81%E7%BA%A2%E5%8F%A4%E9%93%9C%E3%80%81%E9%9D%92%E5%8F%A4%E9%93%9C%E3%80%81%E6%8B%89%E4%B8%9D%E5%96%B7%E6%B2%B9%E3%80%81%E7%AD%89%E3%80%82%3Cbr%2F%3E%E4%B8%BB%E8%A6%81%E7%94%B5%E9%95%80%E5%8A%A0%E5%B7%A5%E4%BA%A7%E5%93%81%E5%8C%85%E6%8B%AC%E6%B1%BD%E8%BD%A6%E5%86%85%E5%A4%96%E9%A5%B0%E4%BB%B6%E4%BA%A7%E5%93%81%E3%80%81%E7%94%B5%E5%AD%90%E4%BA%A7%E5%93%81%E3%80%81%E7%94%B5%E5%99%A8%E4%BA%A7%E5%93%81%E3%80%81%E6%95%B0%E7%A0%81%E4%BA%A7%E5%93%81%E9%85%8D%E4%BB%B6%E3%80%81%E6%B8%B8%E6%88%8F%E6%9C%BA%E5%A4%96%E5%A3%B3%E3%80%81%E5%8D%AB%E6%B5%B4%E4%BA%A7%E5%93%81%E3%80%81%E7%AE%B1%E5%8C%85%E9%A5%B0%E6%89%A3%E9%85%8D%E4%BB%B6%E3%80%81LED%E7%81%AF%E9%A5%B0%E9%85%8D%E4%BB%B6%E3%80%81%E5%B7%A5%E8%89%BA%E9%A5%B0%E5%93%81%E7%B1%BB%E3%80%81%E5%A4%A7%E5%9E%8B%E5%B9%BF%E5%91%8A%E6%A0%87%E7%89%8C(2.0%E7%B1%B3%E4%BB%A5%E5%86%85%E4%BA%A7%E5%93%81)%E7%AD%89%E7%AD%89%E3%80%82%3Cbr%2F%3E%E7%9B%88%E5%B3%B0%E6%8B%A5%E6%9C%89%E5%AE%8C%E6%95%B4%E3%80%81%E7%A7%91%E5%AD%A6%E7%9A%84%E8%B4%A8%E9%87%8F%E7%AE%A1%E7%90%86%E4%BD%93%E7%B3%BB%E3%80%82%E5%85%AC%E5%8F%B8%E5%AF%B9%E6%B1%BD%E8%BD%A6%E5%86%85%E5%A4%96%E9%A5%B0%E4%BA%A7%E5%93%81%E6%9C%89%E5%BE%88%E5%BC%BA%E7%9A%84%E6%B3%A8%E5%A1%91%E5%92%8C%E7%94%B5%E9%95%80%E5%88%B6%E9%80%A0%E8%83%BD%E5%8A%9B%E3%80%82%E8%AF%9A%E4%BF%A1%E3%80%81%E5%AE%9E%E5%8A%9B%E5%92%8C%E4%BA%A7%E5%93%81%E8%B4%A8%E9%87%8F%E8%8E%B7%E5%BE%97%E4%B8%9A%E7%95%8C%E7%9A%84%E8%AE%A4%E5%8F%AF%E3%80%82%E5%9D%9A%E6%8C%81%E2%80%9C%E5%AE%A2%E6%88%B7%E7%AC%AC%E4%B8%80%E2%80%9D%E7%9A%84%E5%8E%9F%E5%88%99%E4%B8%BA%E5%B9%BF%E5%A4%A7%E5%AE%A2%E6%88%B7%E6%8F%90%E4%BE%9B%E4%BC%98%E8%B4%A8%E7%9A%84%E6%9C%8D%E5%8A%A1%E3%80%82%E6%9C%AC%E5%8E%82%E8%B7%9D%E7%A6%BB%E5%B9%BF%E6%B7%B1%E9%AB%98%E9%80%9F%E7%BA%A6%E5%8D%81%E4%BA%94%E5%88%86%E9%92%9F%E8%B7%AF%E7%A8%8B%EF%BC%8C%E4%BA%A4%E9%80%9A%E4%BE%BF%E5%88%A9%EF%BC%8C%E6%AC%A2%E8%BF%8E%E4%BD%A0%E5%89%8D%E6%9D%A5%E5%85%89%E4%B8%B4%E6%8C%87%E5%AF%BC%E6%AC%A2%E8%BF%8E%E5%B9%BF%E5%A4%A7%E5%AE%A2%E6%88%B7%E6%83%A0%E9%A1%BE%EF%BC%81%3C%2Fdiv%3E";}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$aboutid = pdo_insertid();

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '产品中心',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"产品中心";s:5:"title";s:12:"产品中心";s:10:"sharetitle";s:12:"产品中心";s:8:"shareimg";s:80:"'.$dd4.'";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:3:{i:0;a:3:{s:2:"id";s:14:"m1507454443511";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:7:"#ffffff";s:4:"data";a:2:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$dd4.'";s:3:"url";s:0:"";s:5:"title";s:12:"五金电镀";}i:1;a:4:{s:2:"id";s:15:"m01507454445150";s:3:"img";s:80:"'.$dd5.'";s:3:"url";s:0:"";s:5:"title";s:12:"五金电镀";}}}}i:1;a:3:{s:2:"id";s:14:"m1507454544375";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";}}i:2;a:3:{s:2:"id";s:14:"m1507454534446";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$dd6.'";s:3:"url";s:0:"";s:5:"title";s:15:"塑胶水电镀";}i:1;a:4:{s:2:"id";s:15:"m01507454535871";s:3:"img";s:80:"'.$dd7.'";s:3:"url";s:0:"";s:5:"title";s:15:"塑胶水电镀";}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$productid = pdo_insertid();

			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '联系我们',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"联系我们";s:5:"title";s:12:"联系我们";s:10:"sharetitle";s:12:"联系我们";s:8:"shareimg";s:0:"";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:1:{i:0;a:3:{s:2:"id";s:14:"m1507454630359";s:4:"name";s:4:"text";s:6:"params";a:4:{s:7:"bgcolor";s:7:"#ffffff";s:6:"margin";i:0;s:7:"padding";i:5;s:7:"content";s:543:"%3Cdiv%20style%3D%22font-size%3A14px%3Bline-height%3A28px%3B%22%3E%0A%09%E4%B8%9C%E8%8E%9E%E5%B8%82%E7%9B%88%E5%B3%B0%E6%96%B0%E6%9D%90%E6%96%99%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8%3Cbr%2F%3E%E5%9C%B0%E5%9D%80%EF%BC%9A%E5%B9%BF%E4%B8%9C%E7%9C%81%E4%B8%9C%E8%8E%9E%E5%B8%82%E8%99%8E%E9%97%A8%E9%95%87%E5%8D%97%E6%A0%85%E7%AC%AC%E5%9B%9B%E5%B7%A5%E4%B8%9A%E5%8C%BA%E5%86%9C%E6%9E%97%E8%B7%AF1-3%E5%8F%B7%3Cbr%2F%3E%E7%94%B5%E8%AF%9D%EF%BC%9A0769-85164020%3Cbr%2F%3E%E6%89%8B%E6%9C%BA%EF%BC%9A%E5%90%B4%E5%85%88%E7%94%9F%2013798706262%3C%2Fdiv%3E";}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$contactid = pdo_insertid();

			$next = $contactid + 1;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '首页',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => 'a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"首页";s:5:"title";s:36:"东莞市盈峰新材料有限公司";s:10:"sharetitle";s:36:"东莞市盈峰新材料有限公司";s:8:"shareimg";s:80:"'.$dd4.'";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:6:{i:0;a:3:{s:2:"id";s:14:"m1507450675290";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#ffffff";s:8:"actcolor";s:7:"#008040";s:9:"showpoint";i:0;s:4:"data";a:3:{i:0;a:2:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$dd1.'";}i:1;a:2:{s:2:"id";s:14:"g1507450677803";s:3:"img";s:80:"'.$dd2.'";}i:2;a:2:{s:2:"id";s:14:"g1507450678658";s:3:"img";s:80:"'.$dd3.'";}}}}i:1;a:3:{s:2:"id";s:14:"m1507450938368";s:4:"name";s:3:"nav";s:6:"params";a:6:{s:3:"num";i:4;s:6:"radius";i:100;s:7:"padding";i:10;s:7:"bgcolor";s:7:"#ffffff";s:9:"fontcolor";s:4:"#000";s:4:"data";a:5:{i:0;a:5:{s:2:"id";s:8:"00000001";s:5:"title";s:12:"关于我们";s:3:"img";s:80:"'.$ddn1.'";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid='.$aboutid.'";s:7:"urlname";s:12:"关于我们";}i:1;a:5:{s:2:"id";s:8:"00000002";s:5:"title";s:12:"产品中心";s:3:"img";s:80:"'.$ddn2.'";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid='.$productid.'";s:7:"urlname";s:12:"产品中心";}i:2;a:5:{s:2:"id";s:8:"00000003";s:5:"title";s:12:"新闻资讯";s:3:"img";s:80:"'.$ddn3.'";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid='.$next.'";s:7:"urlname";s:6:"首页";}i:3;a:5:{s:2:"id";s:8:"00000004";s:5:"title";s:12:"联系我们";s:3:"img";s:80:"'.$ddn4.'";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid='.$contactid.'";s:7:"urlname";s:12:"联系我们";}i:4;a:4:{s:2:"id";s:8:"00000005";s:5:"title";s:12:"导航名称";s:3:"img";s:63:"http://127.0.0.5//addons/zxbddiy_sitetemp/public/images/thank.png";s:3:"url";s:0:"";}}}}i:2;a:3:{s:2:"id";s:14:"m1507450943768";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:8;s:7:"bgcolor";s:7:"#f3f4f5";}}i:3;a:3:{s:2:"id";s:14:"m1507450724594";s:4:"name";s:5:"title";s:6:"params";a:14:{s:7:"content";s:12:"产品列表";s:8:"paddingv";i:5;s:8:"paddingh";s:2:"10";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:7:"#000000";s:4:"size";i:18;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:3:"url";s:0:"";}}i:4;a:3:{s:2:"id";s:14:"m1507450790602";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:8;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$dd4.'";s:3:"url";s:0:"";s:5:"title";s:18:"五金电镀加工";}i:1;a:4:{s:2:"id";s:15:"m01507450791867";s:3:"img";s:80:"'.$dd5.'";s:3:"url";s:0:"";s:5:"title";s:18:"东莞五金电镀";}}}}i:5;a:3:{s:2:"id";s:14:"m1507450863369";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:8;s:4:"type";i:2;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:2:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"'.$dd6.'";s:3:"url";s:0:"";s:5:"title";s:18:"加工五金电镀";}i:1;a:4:{s:2:"id";s:15:"m01507450865137";s:3:"img";s:80:"'.$dd7.'";s:3:"url";s:0:"";s:5:"title";s:18:"五金电镀加工";}}}}}}'
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$indexid = pdo_insertid();

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => 'a:6:{s:3:"num";s:1:"4";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#999";s:8:"actcolor";s:7:"#ed414a";s:4:"data";a:4:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"首页";s:3:"img";s:80:"'.$foot1.'";s:6:"actimg";s:80:"'.$foot2.'";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid='.$indexid.'";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:20";s:6:"pageid";s:2:"'.$indexid.'";s:7:"urlname";s:6:"首页";}i:1;a:8:{s:2:"id";s:8:"00000002";s:4:"name";s:6:"电话";s:3:"img";s:80:"'.$foot3.'";s:6:"actimg";s:80:"'.$foot4.'";s:3:"url";s:0:"";s:4:"type";s:3:"tel";s:9:"$$hashKey";s:9:"object:21";s:3:"tel";s:11:"13112345678";}i:2;a:9:{s:2:"id";s:15:"m01507451962587";s:3:"img";s:80:"'.$foot5.'";s:6:"actimg";s:80:"'.$foot6.'";s:3:"url";s:0:"";s:4:"name";s:6:"地图";s:4:"type";s:3:"map";s:9:"$$hashKey";s:9:"object:51";s:3:"lng";s:18:"114.05121803283691";s:3:"lat";s:18:"22.561628756102063";}i:3;a:7:{s:2:"id";s:15:"m11507451962587";s:3:"img";s:80:"'.$foot7.'";s:6:"actimg";s:80:"'.$foot8.'";s:3:"url";s:0:"";s:4:"name";s:6:"分享";s:4:"type";s:5:"share";s:9:"$$hashKey";s:9:"object:52";}}}'
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}

	
	static function insertwlPage( $tid ){
		global $_W;
		$tid = intval( $tid );

		if( $tid > 0 ){

			$dd1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_as1.jpg';
			$dd2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_s4.jpg';
			$dd3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_as3.jpg';

			$fx1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_f1.jpg';
			$fx2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_f2.jpg';
			$fx3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_f3.jpg';
			$fx4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_f4.jpg';

			$m1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_m1.jpg';
			$m2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_m2.jpg';
			$m3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_m3.jpg';
			$m4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_m4.jpg';
			$m5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_m5.jpg';

			$xcx1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_x1.jpg';
			$xcx2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_d2.jpg';
			$xcx3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_d3.jpg';
			$xcx4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_d4.jpg';
			$xcx5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_d5.jpg';
			$xcx6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_d6.jpg';

			$index1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_s1.jpg';
			$index2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_s2.jpg';
			$index3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_s3.png';
			$index4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_s4.png';

			$index5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_n1.png';
			$index6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_n2.png';
			$index7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_n3.png';
			$index8 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_n4.png';

			$index9 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p1.png';
			$index10 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p2.png';
			$index11 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p3.png';
			$index12 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p4.png';
			$index13 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p5.png';
			$index14 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_p6.png';			

			$index15 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_ma1.jpg';
			$index16 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/wl_ma2.jpg';			

			$foot1 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/home1.png';
			$foot2 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/home2.png';
			$foot3 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/contact1.png';
			$foot4 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/contact2.png';
			$foot5 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/tel1.png';
			$foot6 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/tel2.png';
			$foot7 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/about1.png';
			$foot8 = $_W['siteroot'].'/addons/zxbddiy_sitetemp/public/images/about2.png';

			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"关于我们";s:5:"title";s:12:"关于我们";s:10:"sharetitle";s:12:"关于我们";s:8:"shareimg";s:80:"{$dd1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:2:{i:0;a:3:{s:2:"id";s:14:"m1507475611097";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#dddddd";s:8:"actcolor";s:7:"#585656";s:9:"showpoint";i:0;s:4:"data";a:3:{i:0;a:2:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$dd1}";}i:1;a:2:{s:2:"id";s:14:"g1507475612985";s:3:"img";s:80:"{$dd2}";}i:2;a:2:{s:2:"id";s:14:"g1507475613569";s:3:"img";s:80:"{$dd3}";}}}}i:1;a:3:{s:2:"id";s:14:"m1507475647563";s:4:"name";s:4:"text";s:6:"params";a:4:{s:7:"bgcolor";s:7:"#ffffff";s:6:"margin";i:0;s:7:"padding";i:5;s:7:"content";s:8716:"%3Cdiv%20class%3D%22wpb_text_column%20wpb_content_element%20zoomIn%20animate-element%22%3E%3Cdiv%20class%3D%22wpb_wrapper%22%3E%3Cp%3E%3Cspan%20style%3D%22font-size%3A%2016px%3B%20color%3A%20%23666666%3B%20line-height%3A%2035px%3B%22%3E%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%E6%B7%B1%E5%9C%B3%E5%B8%82%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8(%E4%BB%A5%E4%B8%8B%E7%AE%80%E7%A7%B0%3Cspan%20style%3D%22color%3A%20rgb(102%2C%20102%2C%20102)%3B%20line-height%3A%2035px%3B%22%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%3C%2Fspan%3E)%20%E4%B8%93%E6%B3%A8%E4%BA%8E%E7%A7%BB%E5%8A%A8%E4%BA%92%E8%81%94%E7%BD%91%2C%E6%8F%90%E4%BE%9B%E5%9F%BA%E4%BA%8E%E7%A7%BB%E5%8A%A8%E4%BA%92%E8%81%94%E7%BD%91%E4%BA%A7%E5%93%81%E7%AD%96%E5%88%92%E3%80%81%E6%96%B9%E6%A1%88%E3%80%81%E8%AE%BE%E8%AE%A1%E3%80%81%E5%BC%80%E5%8F%91%E3%80%81%E6%8E%A8%E5%B9%BF%E4%B8%80%E7%AB%99%E5%BC%8F%E5%BC%80%E5%8F%91%E6%9C%8D%E5%8A%A1%E5%95%86%EF%BC%8C%E4%B8%BB%E8%A6%81%E4%B8%9A%E5%8A%A1%E8%8C%83%E7%95%B4%E5%8C%85%E6%8B%AC%EF%BC%9AAPP%E5%BC%80%E5%8F%91%E3%80%81%E5%BE%AE%E4%BF%A1%E5%BC%80%E5%8F%91%E3%80%81%E5%A4%96%E8%B4%B8%E7%BD%91%E7%AB%99%E8%A7%A3%E5%86%B3%E6%96%B9%E6%A1%88%E3%80%81%E7%A7%BB%E5%8A%A8%E6%94%AF%E4%BB%98%E3%80%81%E8%BD%AF%E4%BB%B6%E5%AE%9A%E5%88%B6%E3%80%81%E6%99%BA%E6%85%A7%E7%A4%BE%E5%8C%BA%E3%80%81%E6%99%BA%E8%83%BD%E5%8C%96%E5%AE%B6%E5%B1%85%E7%AD%89%E3%80%82%3C%2Fspan%3E%3Cbr%2F%3E%20%3Cspan%20style%3D%22font-size%3A%2016px%3B%20color%3A%20%23666666%3B%20line-height%3A%2035px%3B%22%3E%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%E5%9C%A8%E2%80%9D%E4%BA%92%E8%81%94%E7%BD%91%2B%20%E2%80%9C%E7%9A%84%E5%85%A8%E6%96%B0%E6%97%B6%E4%BB%A3%EF%BC%8C%3Cspan%20style%3D%22color%3A%20rgb(102%2C%20102%2C%20102)%3B%20line-height%3A%2035px%3B%22%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%3C%2Fspan%3E%E8%AF%BA%E5%87%AD%E5%80%9F%E4%B8%93%E4%B8%9A%E3%80%81%E6%8A%80%E6%9C%AF%E3%80%81%E5%BE%AE%E5%88%9B%E6%96%B0%E7%9A%84%E7%90%86%E5%BF%B5%EF%BC%8C%E4%B8%BA%E4%BC%81%E4%B8%9A%E6%8F%90%E4%BE%9B%E4%B8%93%E4%B8%9A%E7%9A%84%E5%AE%9A%E5%88%B6%E5%BC%80%E5%8F%91%E6%9C%8D%E5%8A%A1%E5%8F%8A%E4%BC%81%E4%B8%9A%E7%A7%BB%E5%8A%A8%E5%8C%96%E8%A7%A3%E5%86%B3%E6%96%B9%E6%A1%88%E3%80%82%E4%BD%9C%E4%B8%BA%E7%A7%BB%E5%8A%A8%E4%BA%92%E8%81%94%E7%BD%91%E9%A2%86%E5%9F%9F%E6%8F%90%E4%BE%9B%E4%B8%80%E7%AB%99%E5%BC%8F%E7%9A%84%E5%A4%9A%E5%B9%B3%E5%8F%B0%E8%A7%A3%E5%86%B3%E6%96%B9%E6%A1%88%E9%A2%86%E5%AF%BC%E8%80%85%EF%BC%8C%E6%9C%8D%E5%8A%A1%E8%B6%85%E8%BF%87200%E4%BD%8D%E6%B5%B7%E5%86%85%E5%A4%96%E5%AE%A2%E6%88%B7%EF%BC%8C%E5%BD%93%E4%B8%AD%E5%8C%85%E6%8B%AC%E4%BC%97%E5%A4%9A%E7%9F%A5%E5%90%8D%E5%93%81%E7%89%8C%E3%80%81%E5%9B%BD%E9%99%85%E5%93%81%E7%89%8C%E3%80%82%3C%2Fspan%3E%3Cspan%20style%3D%22font-size%3A%2016px%3B%20color%3A%20%23666666%3B%20line-height%3A%2035px%3B%22%3E%3Cbr%2F%3E%20%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%E5%85%AC%E5%8F%B8%E6%8B%A5%E6%9C%89%E4%B8%80%E6%89%B9%E5%85%85%E6%BB%A1%E6%A2%A6%E6%83%B3%E5%92%8C%E5%B9%B4%E8%BD%BB%E7%9A%84%E6%8A%80%E6%9C%AF%E5%BC%80%E5%8F%91%E5%9B%A2%E9%98%9F%EF%BC%8C%E6%A0%B8%E5%BF%83%E5%9B%A2%E9%98%9F%E6%9D%A5%E5%9D%87%E8%87%AA%E4%BA%8E%E5%9B%BD%E5%86%85%E7%9F%A5%E5%90%8D%E8%BD%AF%E4%BB%B6%E4%BC%81%E4%B8%9A%EF%BC%8C%E5%8A%9B%E4%BA%89%E6%89%93%E9%80%A0%E4%B8%AD%E5%9B%BD%E5%85%B7%E6%9C%89%E7%AB%9E%E4%BA%89%E5%8A%9B%E7%9A%84%E7%A7%BB%E5%8A%A8%E4%BA%92%E8%81%94%E7%BD%91%E7%A7%91%E6%8A%80%E4%BC%81%E4%B8%9A%E3%80%82%E6%88%91%E4%BB%AC%E4%B9%9F%E8%AE%B8%E6%98%AF%E6%82%A8%E6%9C%80%E5%80%BC%E5%BE%97%E4%BF%A1%E8%B5%96%E7%9A%84%E8%BD%AF%E4%BB%B6%E5%BC%80%E5%8F%91%E6%9C%8D%E5%8A%A1%E5%95%86%EF%BC%81%3C%2Fspan%3E%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22font-size%3A%2016px%3B%20color%3A%20%23666666%3B%20line-height%3A%2035px%3B%22%3E%3Cbr%2F%3E%3C%2Fspan%3E%3C%2Fp%3E%3C%2Fdiv%3E%3C%2Fdiv%3E%3Cdiv%20class%3D%22item-right%22%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%233f3f3f%3B%22%3E%3Cstrong%3E%E4%B8%93%E4%B8%9A%E7%AD%96%E5%88%92%EF%BC%9A%3C%2Fstrong%3E%3C%2Fspan%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E8%AE%BE%E6%9C%89%E4%B8%93%E4%B8%9A%E7%9A%84%E7%BD%91%E7%AB%99%E7%AD%96%E5%88%92%E9%83%A8%EF%BC%8C%E9%80%9A%E8%BF%87PM%EF%BC%88%E9%A1%B9%E7%9B%AE%E7%BB%8F%E7%90%86%EF%BC%89%E7%90%86%E8%A7%A3%E5%AE%A2%E6%88%B7%E7%9A%84%E5%95%86%E4%B8%9A%E9%9C%80%E6%B1%82%E5%90%8E%EF%BC%8C%E4%B8%8E%E7%BD%91%E7%AB%99%E7%AD%96%E5%88%92%E4%BA%BA%E5%91%98%E5%85%B1%E5%90%8C%E7%AD%96%E5%88%92%E6%96%B9%E6%A1%88%EF%BC%8C%E7%A1%AE%E4%BF%9D%E4%B8%BA%E5%AE%A2%E6%88%B7%E6%8F%90%E4%BE%9B%E6%9C%80%E4%BC%98%E7%A7%80%E3%80%81%E7%8B%AC%E7%89%B9%E3%80%81%E5%85%85%E5%88%86%E4%B8%94%E7%BB%8F%E6%B5%8E%E7%9A%84%E7%BD%91%E7%AB%99%E5%BB%BA%E8%AE%BE%E6%96%B9%E6%A1%88%E3%80%82%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%23888888%3B%22%3E%3Cbr%2F%3E%3C%2Fspan%3E%3C%2Fp%3E%3Cp%3E%3Cstrong%3E%3Cspan%20style%3D%22color%3A%20%233f3f3f%3B%22%3E%E8%A7%86%E8%A7%89%E8%AE%BE%E8%AE%A1%EF%BC%9A%3C%2Fspan%3E%20%3C%2Fstrong%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E6%8B%A5%E6%9C%89%E5%A4%9A%E5%90%8D%E4%BC%98%E7%A7%80%E7%9A%84%E7%BE%8E%E5%B7%A5%E8%AE%BE%E8%AE%A1%E5%B8%88%EF%BC%8C%E5%9C%A8%E8%A7%86%E8%A7%89%E8%AE%BE%E8%AE%A1%E6%96%B9%E9%9D%A2%EF%BC%8C%E6%88%91%E4%BB%AC%E4%B9%9F%E8%AE%B8%E6%98%AF%E6%9C%80%E8%82%AF%E4%B8%8B%E5%B7%A5%E5%A4%AB%E7%9A%84%E5%9B%A2%E9%98%9F%EF%BC%8C%E5%9C%A8%E7%AD%96%E5%88%92%E9%98%B6%E6%AE%B5%EF%BC%8C%E8%AE%BE%E8%AE%A1%E4%BA%BA%E5%91%98%E5%8D%B3%E5%8F%82%E4%B8%8E%E8%A7%84%E5%88%92%EF%BC%8C%E7%94%84%E9%80%89%E5%9B%BD%E5%A4%96%E4%BC%98%E7%A7%80%E7%B2%BE%E5%93%81%E7%BD%91%E7%AB%99%E4%BD%9C%E4%B8%BA%E5%8F%82%E8%80%83%E5%AF%B9%E8%B1%A1%EF%BC%8C%E8%AE%BE%E8%AE%A1%E9%98%B6%E6%AE%B5%EF%BC%8C%E4%BA%A6%E8%83%BD%E5%AA%B2%E7%BE%8E%E5%B9%B6%E8%B6%85%E8%B6%8A%E5%9B%BD%E5%A4%96%E7%B2%BE%E5%93%81%E7%BD%91%E7%AB%99%E6%B0%B4%E5%B9%B3%E3%80%82%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%23888888%3B%22%3E%3Cbr%2F%3E%3C%2Fspan%3E%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%233f3f3f%3B%22%3E%3Cstrong%3E%E5%8A%9F%E8%83%BD%E5%BC%80%E5%8F%91%3A%20%3C%2Fstrong%3E%3C%2Fspan%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E7%9B%B8%E4%BF%A1%EF%BC%8C%E4%B8%AA%E6%80%A7%E5%8C%96%E3%80%81%E5%A4%9A%E6%A0%B7%E5%8C%96%E7%9A%84%E7%BD%91%E7%AB%99%E5%8A%9F%E8%83%BD%E6%89%8D%E6%98%AF%E5%B8%AE%E5%8A%A9%E5%AE%A2%E6%88%B7%E5%AE%9E%E7%8E%B0%E5%95%86%E4%B8%9A%E7%9B%AE%E7%9A%84%E7%9A%84%E6%9C%89%E5%8A%9B%E6%B8%A0%E9%81%93%EF%BC%8C%E8%80%8C%E5%B9%BF%E4%B8%BA%E7%BD%91%E7%BB%9C%E5%85%B7%E6%9C%89%E4%B8%B0%E5%AF%8C%E7%BC%96%E7%A8%8B%E5%BC%80%E5%8F%91%E7%BB%8F%E9%AA%8C%E7%9A%84%E5%BC%80%E5%8F%91%E4%BA%BA%E5%91%98%EF%BC%8C%E4%BF%9D%E9%9A%9C%E4%BA%86%E6%82%A8%E7%8B%AC%E7%89%B9%E7%9A%84%E4%B8%9A%E5%8A%A1%E9%9C%80%E6%B1%82%E5%9D%87%E8%83%BD%E6%BB%A1%E8%B6%B3%E3%80%82%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%23888888%3B%22%3E%3Cbr%2F%3E%3C%2Fspan%3E%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%233f3f3f%3B%22%3E%3Cstrong%3E%E6%8E%A8%E5%B9%BF%E8%BF%90%E8%90%A5%3A%20%3C%2Fstrong%3E%3C%2Fspan%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E5%B9%B6%E9%9D%9E%E5%8D%95%E7%BA%AF%E7%9A%84%E5%BB%BA%E7%AB%99%E5%85%AC%E5%8F%B8%EF%BC%8C%E6%88%91%E4%BB%AC%E7%9A%84%E5%8F%A3%E5%8F%B7%E6%98%AF%E2%80%9D%E6%82%A8%E7%9A%84%E7%94%B5%E5%95%86%E4%BC%B4%E4%BE%A3%E2%80%9D%EF%BC%8C%E5%9B%A0%E6%AD%A4%EF%BC%8C%E5%90%8E%E6%9C%9F%E6%9C%8D%E5%8A%A1%E4%B9%9F%E6%98%AF%E6%88%91%E4%BB%AC%E7%9A%84%E4%BC%98%E5%8A%BF%E4%B9%8B%E4%B8%80%E3%80%82%E6%88%91%E4%BB%AC%E4%B8%BA%E5%AE%A2%E6%88%B7%E6%8F%90%E4%BE%9B%E5%8C%85%E6%8B%AC%E7%BD%91%E7%AB%99%E5%9F%BA%E7%A1%80%E7%BB%B4%E6%8A%A4%E6%9C%8D%E5%8A%A1%E3%80%81SEO%E8%90%A5%E9%94%80%E6%8E%A8%E5%B9%BF%E6%9C%8D%E5%8A%A1%E4%BB%A5%E5%8F%8A%E6%95%B4%E7%AB%99%E7%BB%BC%E5%90%88%E8%BF%90%E8%90%A5%E6%9C%8D%E5%8A%A1%EF%BC%8C%E7%A1%AE%E4%BF%9D%E6%82%A8%E7%9A%84%E7%BD%91%E7%AB%99%E5%9C%A8%E5%BB%BA%E7%AB%99%E5%90%8E%E5%85%B7%E6%9C%89%E7%94%9F%E5%91%BD%E5%8A%9B%E4%B8%8E%E7%AB%9E%E4%BA%89%E5%8A%9B%E3%80%82%3C%2Fp%3E%3Cp%3E%3Cspan%20style%3D%22color%3A%20%23888888%3B%22%3E%3Cbr%2F%3E%3C%2Fspan%3E%3C%2Fp%3E%3Cp%3E%3Cstrong%3E%3Cspan%20style%3D%22color%3A%20%233f3f3f%3B%22%3E%E5%93%81%E8%B4%A8%E7%9B%91%E6%8E%A7%3A%3C%2Fspan%3E%20%3C%2Fstrong%3E%E4%BC%97%E6%83%A0%E4%BA%92%E8%81%94%E6%8B%A5%E6%9C%89%E5%8D%93%E8%B6%8A%E7%9A%84%E7%AE%A1%E7%90%86%E5%9B%A2%E9%98%9F%EF%BC%8C%E6%A0%B8%E5%BF%83%E7%AE%A1%E7%90%86%E4%BA%BA%E5%91%98%E5%9D%87%E5%87%BA%E8%87%AA%E5%8D%8E%E4%B8%BA%E3%80%81%E5%BE%AE%E8%BD%AF%E3%80%81%E5%AE%9D%E6%B4%81%E7%AD%89%E7%9F%A5%E5%90%8D%E4%BC%81%E4%B8%9A%E3%80%82%E5%90%8C%E6%97%B6%EF%BC%8C%E5%B9%BF%E4%B8%BA%E7%BD%91%E7%BB%9C%E5%BB%BA%E7%AB%8B%E4%BA%86%E5%AE%8C%E6%95%B4%E7%9A%84%E7%BD%91%E7%AB%99%E5%93%81%E8%B4%A8%E7%9B%91%E6%8E%A7%E6%B5%81%E7%A8%8B%EF%BC%8C%E5%9C%A8%E6%AF%8F%E4%B8%80%E4%B8%AA%E7%BB%86%E8%8A%82%E4%B8%8A%E4%B8%BA%E5%AE%A2%E6%88%B7%E4%BF%9D%E9%9A%9C%E4%BC%98%E7%A7%80%E7%9A%84%E7%BD%91%E7%AB%99%E5%93%81%E8%B4%A8%E3%80%82%3C%2Fp%3E%3C%2Fdiv%3E";}}}}
div;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '关于我们',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$aboutid = pdo_insertid();


			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"联系我们";s:5:"title";s:12:"联系我们";s:10:"sharetitle";s:12:"联系我们";s:8:"shareimg";s:80:"{$dd1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:1:{i:0;a:3:{s:2:"id";s:14:"m1507476281518";s:4:"name";s:4:"text";s:6:"params";a:4:{s:7:"bgcolor";s:7:"#ffffff";s:6:"margin";i:0;s:7:"padding";i:5;s:7:"content";s:391:"%3Cp%3E%E8%81%94%E7%B3%BB%E5%9C%B0%E5%9D%80%EF%BC%9A%E6%B7%B1%E5%9C%B3%E5%B8%82%E9%BE%99%E5%8D%8E%E6%96%B0%E5%8C%BA%E8%A7%82%E6%BE%9C%E8%A1%97%E9%81%93%E7%99%BE%E4%B8%BD%E5%90%8D%E8%8B%9110%E6%A0%8B%3C%2Fp%3E%3Cp%3E%E8%81%94%E7%B3%BB%E7%94%B5%E8%AF%9D%EF%BC%9A0755-36341133%3C%2Fp%3E%3Cp%3E%E8%81%94%E7%B3%BB%E4%BA%BA%EF%BC%9A%E5%94%90%E5%85%88%E7%94%9F%3C%2Fp%3E%3Cp%3E%3Cbr%2F%3E%3C%2Fp%3E";}}}}
div;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '联系我们',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$contactid = pdo_insertid();


			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"分销商城";s:5:"title";s:12:"分销商城";s:10:"sharetitle";s:12:"分销商城";s:8:"shareimg";s:80:"{$fx1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:4:{i:0;a:3:{s:2:"id";s:14:"m1507474874120";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$fx1}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:1;a:3:{s:2:"id";s:14:"m1507474918278";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$fx2}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:2;a:3:{s:2:"id";s:14:"m1507475044822";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$fx3}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:3;a:3:{s:2:"id";s:14:"m1507474931990";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$fx4}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}}}
div;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '分销商城',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$fenxiaoid = pdo_insertid();


			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:12:"码上点餐";s:5:"title";s:12:"码上点餐";s:10:"sharetitle";s:12:"码上点餐";s:8:"shareimg";s:80:"{$m1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:6:{i:0;a:3:{s:2:"id";s:14:"m1507475110157";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$m1}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:1;a:3:{s:2:"id";s:14:"m1507475115621";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$m2}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:2;a:3:{s:2:"id";s:14:"m1507475132981";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$m3}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:3;a:3:{s:2:"id";s:14:"m1507475143156";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$m4}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:4;a:3:{s:2:"id";s:14:"m1507475159772";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$m5}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:5;a:3:{s:2:"id";s:14:"m1507475232915";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:62;s:7:"bgcolor";s:7:"#f3f4f5";}}}}
div;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '码上点餐',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$diancanid = pdo_insertid();


			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:15:"小程序定制";s:5:"title";s:15:"小程序定制";s:10:"sharetitle";s:15:"小程序定制";s:8:"shareimg";s:80:"{$xcx1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:6:{i:0;a:3:{s:2:"id";s:14:"m1507475259956";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$xcx1}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:1;a:3:{s:2:"id";s:14:"m1507475265267";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$xcx2}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:2;a:3:{s:2:"id";s:14:"m1507475276612";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$xcx3}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:3;a:3:{s:2:"id";s:14:"m1507475285324";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$xcx4}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:4;a:3:{s:2:"id";s:14:"m1507475296238";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:1;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$xcx5}";s:3:"url";s:0:"";s:5:"title";s:0:"";}}}}i:5;a:3:{s:2:"id";s:14:"m1507475321132";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:77;s:7:"bgcolor";s:7:"#f3f4f5";}}}}
div;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '小程序定制',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$xcxid = pdo_insertid();



			$params = <<<div
a:2:{s:5:"basic";a:8:{s:2:"id";s:7:"0000000";s:4:"name";s:6:"首页";s:5:"title";s:6:"首页";s:10:"sharetitle";s:6:"首页";s:8:"shareimg";s:80:"{$index1}";s:5:"isbar";i:0;s:5:"topbg";s:7:"#ffffff";s:8:"topcolor";s:7:"#000000";}s:4:"data";a:14:{i:0;a:3:{s:2:"id";s:14:"m1507473035317";s:4:"name";s:5:"slide";s:6:"params";a:7:{s:8:"ischange";i:0;s:10:"changetime";i:3;s:10:"changelast";i:500;s:10:"pointcolor";s:7:"#dddddd";s:8:"actcolor";s:7:"#585656";s:9:"showpoint";i:0;s:4:"data";a:4:{i:0;a:2:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$index1}";}i:1;a:2:{s:2:"id";s:14:"g1507473042061";s:3:"img";s:80:"{$index2}";}i:2;a:2:{s:2:"id";s:14:"g1507473042788";s:3:"img";s:80:"{$index3}";}i:3;a:2:{s:2:"id";s:14:"g1507473043501";s:3:"img";s:80:"{$index4}";}}}}i:1;a:3:{s:2:"id";s:14:"m1507473098267";s:4:"name";s:3:"nav";s:6:"params";a:6:{s:3:"num";i:4;s:6:"radius";i:0;s:7:"padding";i:5;s:7:"bgcolor";s:7:"#ffffff";s:9:"fontcolor";s:4:"#000";s:4:"data";a:5:{i:0;a:5:{s:2:"id";s:8:"00000001";s:5:"title";s:12:"分销商城";s:3:"img";s:80:"{$index5}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$fenxiaoid}";s:7:"urlname";s:12:"分销商城";}i:1;a:5:{s:2:"id";s:8:"00000002";s:5:"title";s:12:"码上点餐";s:3:"img";s:80:"{$index6}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$diancanid}";s:7:"urlname";s:12:"码上点餐";}i:2;a:5:{s:2:"id";s:8:"00000003";s:5:"title";s:15:"小程序定制";s:3:"img";s:80:"{$index7}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$xcxid}";s:7:"urlname";s:15:"小程序定制";}i:3;a:4:{s:2:"id";s:8:"00000004";s:5:"title";s:15:"公众号开发";s:3:"img";s:80:"{$index8}";s:3:"url";s:0:"";}i:4;a:4:{s:2:"id";s:8:"00000005";s:5:"title";s:12:"导航名称";s:3:"img";s:63:"http://127.0.0.5//addons/zxbddiy_sitetemp/public/images/thank.png";s:3:"url";s:0:"";}}}}i:2;a:3:{s:2:"id";s:14:"m1507474824943";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#f3f4f5";}}i:3;a:3:{s:2:"id";s:14:"m1507473185626";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:3;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:3:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$index9}";s:3:"url";s:0:"";s:5:"title";s:12:"微信商城";}i:1;a:4:{s:2:"id";s:15:"m01507473187506";s:3:"img";s:80:"{$index10}";s:3:"url";s:0:"";s:5:"title";s:15:"微酒店餐饮";}i:2;a:4:{s:2:"id";s:15:"m11507473187506";s:3:"img";s:80:"{$index11}";s:3:"url";s:0:"";s:5:"title";s:15:"微服务预约";}}}}i:4;a:3:{s:2:"id";s:14:"m1507473228883";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:3;s:6:"istext";i:1;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:3:{i:0;a:4:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$index12}";s:3:"url";s:0:"";s:5:"title";s:15:"小程序官网";}i:1;a:4:{s:2:"id";s:15:"m01507473230763";s:3:"img";s:80:"{$index13}";s:3:"url";s:0:"";s:5:"title";s:15:"小程序分销";}i:2;a:4:{s:2:"id";s:15:"m11507473230763";s:3:"img";s:80:"{$index14}";s:3:"url";s:0:"";s:5:"title";s:15:"小程序点餐";}}}}i:5;a:3:{s:2:"id";s:14:"m1507473271841";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#f3f4f5";}}i:6;a:3:{s:2:"id";s:14:"m1507473295922";s:4:"name";s:5:"title";s:6:"params";a:14:{s:7:"content";s:12:"旗舰产品";s:8:"paddingv";i:10;s:8:"paddingh";i:5;s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:18;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:3:"url";s:0:"";}}i:7;a:3:{s:2:"id";s:14:"m1507473341738";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$index15}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$contactid}";s:5:"title";s:0:"";s:7:"urlname";s:12:"联系我们";}}}}i:8;a:3:{s:2:"id";s:14:"m1507473354434";s:4:"name";s:5:"title";s:6:"params";a:14:{s:7:"content";s:63:"小程序分销商城功能上线（秒杀、拼团、分销）";s:8:"paddingv";i:1;s:8:"paddingh";i:5;s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:7:"#999999";s:4:"size";i:13;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:3:"url";s:0:"";}}i:9;a:3:{s:2:"id";s:14:"m1507473437585";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";s:2:"10";s:7:"bgcolor";s:7:"#f3f4f5";}}i:10;a:3:{s:2:"id";s:14:"m1507473445768";s:4:"name";s:5:"title";s:6:"params";a:14:{s:7:"content";s:12:"代理加盟";s:8:"paddingv";s:2:"10";s:8:"paddingh";i:5;s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#333";s:4:"size";i:18;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:3:"url";s:0:"";}}i:11;a:3:{s:2:"id";s:14:"m1507473479505";s:4:"name";s:5:"image";s:6:"params";a:7:{s:7:"padding";i:5;s:4:"type";i:1;s:6:"istext";i:0;s:8:"fontsize";i:14;s:9:"fontcolor";s:4:"#333";s:7:"bgcolor";s:4:"#fff";s:4:"data";a:1:{i:0;a:5:{s:2:"id";s:8:"00000001";s:3:"img";s:80:"{$index16}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$contactid}";s:5:"title";s:0:"";s:7:"urlname";s:12:"联系我们";}}}}i:12;a:3:{s:2:"id";s:14:"m1507473486481";s:4:"name";s:5:"title";s:6:"params";a:14:{s:7:"content";s:55:"2017，微信小程序元年，千亿市场等你挖掘";s:8:"paddingv";i:2;s:8:"paddingh";i:5;s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:7:"#999999";s:4:"size";i:13;s:3:"pos";s:4:"left";s:8:"lefticon";i:0;s:7:"leftimg";s:0:"";s:6:"lwidth";i:20;s:9:"righticon";i:0;s:8:"rightimg";s:0:"";s:6:"rwidth";i:20;s:3:"url";s:0:"";}}i:13;a:3:{s:2:"id";s:14:"m1507473528945";s:4:"name";s:5:"space";s:6:"params";a:2:{s:6:"height";i:60;s:7:"bgcolor";s:7:"#f3f4f5";}}}}
div;
			$next = $contactid + 1;
			$pdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '首页',
				'createtime' => TIMESTAMP,
				'tempid' => $tid,
				'params' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_page',$pdata);
			$indexid = pdo_insertid();

			$params = <<<div
a:6:{s:3:"num";s:1:"4";s:7:"padding";s:1:"5";s:7:"bgcolor";s:7:"#ffffff";s:5:"color";s:4:"#999";s:8:"actcolor";s:7:"#ed414a";s:4:"data";a:4:{i:0;a:9:{s:2:"id";s:8:"00000001";s:4:"name";s:6:"首页";s:3:"img";s:80:"{$foot1}";s:6:"actimg";s:80:"{$foot2}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$indexid}";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:20";s:6:"pageid";s:2:"{$indexid}";s:7:"urlname";s:6:"首页";}i:1;a:7:{s:2:"id";s:8:"00000002";s:4:"name";s:6:"咨询";s:3:"img";s:80:"{$foot3}";s:6:"actimg";s:80:"{$foot4}";s:3:"url";s:0:"";s:4:"type";s:4:"kefu";s:9:"$$hashKey";s:9:"object:21";}i:2;a:9:{s:2:"id";s:15:"m01507472225651";s:3:"img";s:80:"{$foot5}";s:6:"actimg";s:80:"{$foot6}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$contactid}";s:4:"name";s:12:"联系我们";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:54";s:6:"pageid";s:2:"{$contactid}";s:7:"urlname";s:12:"联系我们";}i:3;a:9:{s:2:"id";s:15:"m11507472225651";s:3:"img";s:80:"{$foot7}";s:6:"actimg";s:80:"{$foot8}";s:3:"url";s:38:"/zxbddiy_sitetemp/pages/page/page?pid={$aboutid}";s:4:"name";s:12:"关于我们";s:4:"type";s:3:"url";s:9:"$$hashKey";s:9:"object:55";s:6:"pageid";s:2:"{$aboutid}";s:7:"urlname";s:12:"关于我们";}}}
div;
			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'tempid' => $tid,
				'createtime' => TIMESTAMP,
				'data' => $params,
			);
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);


		}

	}



	static function loadTemp( $data ){
		global $_W;

		if( !empty( $data['temp'] ) ) {
			$tdata = array(
				'uniacid' => $_W['uniacid'],
				'createtime' => TIMESTAMP,
				'name' => $data['temp']['name'],
				'type' => $data['temp']['type'],
				'img' => $data['temp']['img'],
				'showqr' => $data['temp']['showqr'],
			);

			if( !empty( $data['temp']['img'] ) ) {
				$url = self::saveImage( $data['temp']['img'] );
				if( $url ) $tdata['img'] = $url;
			}
			if( !empty( $data['temp']['showqr'] ) ) {
				$url = self::saveImage( $data['temp']['showqr'] );
				if( $url ) $tdata['showqr'] = $url;
			}

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			if( $res ) {
				$tid = pdo_insertid();
			}else{
				//message('导入失败');
				return false;
			}

		}else{
			return false;
		}


		if( !empty( $data['bar'] ) ) {

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'createtime' => TIMESTAMP,
				'data' => $data['bar']['data'],
				'tempid' => $tid,
			);
			if( !empty( $bdata['data'] ) ) {
				$bardata = iunserializer( $bdata['data'] );
				if( !empty( $bardata ) ) {
					foreach ( $bardata['data'] as &$v ) {

						if( !empty( $v['img'] ) ) {
							$url = self::saveImage( $v['img'] );
							if( $url ) $v['img'] = $url;
						}
						if( !empty( $v['actimg'] ) ) {
							$url = self::saveImage( $v['actimg'] );
							if( $url ) $v['actimg'] = $url;
						}

					}
					unset( $v );
					$bdata['data'] = iserializer( $bardata );
				}
			}
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);
		}
		
		if( !empty( $data['page'] ) ) {
			$pbox = array();

			foreach ($data['page'] as $v) {
				
				$pdata = array(
					'uniacid' => $_W['uniacid'],
					'createtime' => TIMESTAMP,
					'name' => $v['name'],
					'tempid' => $tid,
				);

				if( !empty( $v['params'] ) ) {
					$params = iunserializer( $v['params'] );
					if( !empty( $params ) ) {

						if( !empty( $params['basic'] ) ) {
							if( !empty( $params['basic']['shareimg'] ) ) {
								$url = self::saveImage( $params['basic']['shareimg'] );
								if( $url ) $params['basic']['shareimg'] = $url;
							}

							if( !empty( $params['basic']['bgimg'] ) ) {
								$url = self::saveImage( $params['basic']['bgimg'] );
								if( $url ) $params['basic']['bgimg'] = $url;
							}

							if( !empty( $params['basic']['fimg'] ) ) {
								$url = self::saveImage( $params['basic']['fimg'] );
								if( $url ) $params['basic']['fimg'] = $url;
							}

							if( !empty( $params['basic']['fimagesdata'] ) ) {

								foreach ($params['basic']['fimagesdata'] as &$vv) {
									if( !empty( $vv['url'] ) ) {
										$url = self::saveImage( $vv['url'] );
										if( $url ) $vv['url'] = $url;
									}
								}
								unset( $vv );
							}
						}

						if( !empty( $params['data'] ) ) {
							
							foreach ($params['data'] as &$vv) {
								
								// 数组图片
								if( in_array($vv['name'], array('nav','slide','image','list')) ) {
									if( !empty( $vv['params']['data'] ) ) {
										foreach ($vv['params']['data'] as $kkk => $vvv) {
											
											if( !empty( $vvv['img'] ) ) {
												$url = self::saveImage( $vvv['img'] );
												if( $url ) $vv['params']['data'][$kkk]['img'] = $url;
											}


											// 打开图片
											if( !empty( $vvv['imagesdata'] ) ) {
												foreach ($vvv['imagesdata'] as $kkkk => $vvvv) {
													if( !empty( $vvvv['url'] ) ) {
														$url = self::saveImage( $vvvv['url'] );
														if( $url ) $vv['params']['data'][$kkk]['imagesdata'][$kkkk]['url'] = $url;
													}
												}
											}

										}
									}
								}

								// 背景图片
								if( in_array($vv['name'], array('article','image','nav','card','title','form','btn','toshop','appoint')) ) {

									if( !empty( $vv['params']['bgimg'] ) ) {
										$url = self::saveImage( $vv['params']['bgimg'] );
										if( $url ) $vv['params']['bgimg'] = $url;
									}
								}

								// 打开图片
								if( in_array($vv['name'], array('card','title','fix','toshop','btn')) ) {
									if( !empty( $vv['params']['imagesdata'] ) ) {
										foreach ($vv['params']['imagesdata'] as $kkk => $vvv) {
											if( !empty( $vvv['url'] ) ) {
												$url = self::saveImage( $vvv['url'] );
												if( $url ) $vv['params']['imagesdata'][$kkk]['url'] = $url;
											}
										}
									}

									if( $vv['name'] == 'btn' ) {
										if( !empty( $vv['params']['data'][0] ) ) {
											foreach ($vv['params']['data'][0] as $vvvv) {
												if( !empty( $vvvv['url'] ) ) {
													$url = self::saveImage( $vvvv['url'] );
													if( $url ) $vv['params']['data'][0]['url'] = $url;
												}
											}
										}
									}
								}


								if( $vv['name'] == 'title' ) {
									if( !empty( $vv['params']['leftimg'] ) ) {
										$url = self::saveImage( $vv['params']['leftimg'] );
										if( $url ) $vv['params']['leftimg'] = $url;
									}
									if( !empty( $vv['params']['fontimg'] ) ) {
										$url = self::saveImage( $vv['params']['fontimg'] );
										if( $url ) $vv['params']['fontimg'] = $url;
									}
									if( !empty( $vv['params']['rightimg'] ) ) {
										$url = self::saveImage( $vv['params']['rightimg'] );
										if( $url ) $vv['params']['rightimg'] = $url;
									}									
								}

								if( $vv['name'] == 'video' ) {
									if( !empty( $vv['params']['thumb'] ) ) {
										$url = self::saveImage( $vv['params']['thumb'] );
										if( $url ) $vv['params']['thumb'] = $url;
									}
								}

								if( $vv['name'] == 'fix' ) {
									if( !empty( $vv['params']['img'] ) ) {
										$url = self::saveImage( $vv['params']['img'] );
										if( $url ) $vv['params']['img'] = $url;
									}
								}

							}
							unset( $vv );
						}

					}
				}
				$pdata['params'] = iserializer( $params );
				pdo_insert('zxbddiy_sitetemp_page',$pdata);
				$pid = pdo_insertid();
				$pbox[] = array('name'=>$v['name'],'id'=>$pid);

			}
		}

		// 重置链接
		self::setTempUrl($tid,$pbox,true);

		return true;
	}

	static function saveImage($url){
		global $_W;

		load()->func('file');
		load()->func('communication');
		load()->model('material');

		$material = material_network_to_local($url, $_W['uniacid'], $_W['uid'], 'image');
		if (!is_error($material)) {
			return $material['url'];
		}else{
			return false;
		}
	}

	// 重置页面链接
	static function setTempUrl($tid,$pbox,$isfirstbar=false){
		global $_W;

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$tid));
		if( !empty( $pbox ) && !empty( $bar ) ) {
			$bardata = iunserializer( $bar['data'] );			
			if( !empty( $bardata ) ) {
				foreach ($pbox as $v) {
					if( $isfirstbar && !empty( $bardata['data'][0]['urlname'] ) && $v['name'] == $bardata['data'][0]['urlname'] ){
						$bardata['data'][0]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
						$bardata['data'][0]['pageid'] = $v['id'];
					}
					if( !empty( $bardata['data'][1]['urlname'] ) && $v['name'] == $bardata['data'][1]['urlname'] ){
						$bardata['data'][1]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
						$bardata['data'][1]['pageid'] = $v['id'];
					}
					if( !empty( $bardata['data'][2]['urlname'] ) && $v['name'] == $bardata['data'][2]['urlname'] ){
						$bardata['data'][2]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
						$bardata['data'][2]['pageid'] = $v['id'];
					}							
					if( !empty( $bardata['data'][3]['urlname'] ) && $v['name'] == $bardata['data'][3]['urlname'] ){
						$bardata['data'][3]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
						$bardata['data'][3]['pageid'] = $v['id'];
					}
					if( !empty( $bardata['data'][4]['urlname'] ) && $v['name'] == $bardata['data'][4]['urlname'] ){
						$bardata['data'][4]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
						$bardata['data'][4]['pageid'] = $v['id'];
					}
				}
				
				$bardata = iserializer( $bardata );
				pdo_update('zxbddiy_sitetemp_bar',array('data'=>$bardata),array('id'=>$bar['id']));
			}
		}

		
		$allpage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$tid));
		if( !empty( $pbox ) && !empty( $allpage ) ) {
			foreach ($allpage as $kk => $vv) {
				$params = iunserializer( $vv['params'] );

				foreach ($pbox as $v) {
					if( !empty( $params['basic']['furlname'] ) && $params['basic']['furlname'] == $v['name'] ){
						$params['basic']['furl'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
					}

					if( !empty( $params['data'] ) ) {	
						foreach ($params['data'] as $kkk => $vvv) {
								
							if(  !empty($vvv['params']['data']) && in_array($vvv['name'], array('slide','image','nav','list','btn')) ) {
								foreach ($vvv['params']['data'] as $kkkk => $vvvv) {
									if( !empty( $vvvv['urlname'] ) && $vvvv['urlname'] == $v['name'] ){
											
										$params['data'][$kkk]['params']['data'][$kkkk]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
									}
								}
							}

							if( in_array($vvv['name'], array('card','title','btn','fix','toshop','btn')) ) {
								if( !empty( $vvv['params']['urlname'] ) && $vvv['params']['urlname'] == $v['name'] ){
									$params['data'][$kkk]['params']['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$v['id'];
								}
							}

						}
					}
				}
				$params = iserializer( $params );
				pdo_update('zxbddiy_sitetemp_page',array('params'=>$params),array('id'=>$vv['id']));

			}
		}

	}


}
