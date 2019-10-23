var opts = {
	type :'image',
	direct : false,
	multiple : true,
	tabs : {
		'upload' : 'active',
		'browser' : '',
		'crawler' : ''
	},
	path : '',
	dest_dir : '',
	global : false,
	thumb : false,
	width : 0
};
UE.registerUI('myinsertimage',function(editor,uiName){
	editor.registerCommand(uiName, {
		execCommand:function(){
			require(['fileUploader'], function(uploader){
				uploader.show(function(imgs){
					if (imgs.length == 0) {
						return;
					} else if (imgs.length == 1) {
						editor.execCommand('insertimage', {
							'src' : imgs[0]['url'],
							'_src' : imgs[0]['attachment'],
							'width' : '100%',
							'alt' : imgs[0].filename
						});
					} else {
						var imglist = [];
						for (i in imgs) {
							imglist.push({
								'src' : imgs[i]['url'],
								'_src' : imgs[i]['attachment'],
								'width' : '100%',
								'alt' : imgs[i].filename
							});
						}
						editor.execCommand('insertimage', imglist);
					}
				}, opts);
			});
		}
	});
	var btn = new UE.ui.Button({
		name: '插入图片',
		title: '插入图片',
		cssRules :'background-position: -726px -77px',
		onclick:function () {
			editor.execCommand(uiName);
		}
	});
	editor.addListener('selectionchange', function () {
		var state = editor.queryCommandState(uiName);
		if (state == -1) {
			btn.setDisabled(true);
			btn.setChecked(false);
		} else {
			btn.setDisabled(false);
			btn.setChecked(state);
		}
	});
	return btn;
}, 19);
UE.registerUI('myinsertvideo',function(editor,uiName){
	editor.registerCommand(uiName, {
		execCommand:function(){
			require(['fileUploader'], function(uploader){
				uploader.show(function(video){
					if (!video) {
						return;
					} else {
						var videoType = video.isRemote ? 'iframe' : 'video';
						editor.execCommand('insertvideo', {
							'url' : video.url,
							'width' : 300,
							'height' : 200
						}, videoType);
					}
				}, {type : 'video', allowUploadVideo : 'true'});
			});
		}
	});
	var btn = new UE.ui.Button({
		name: '插入视频',
		title: '插入视频',
		cssRules :'background-position: -320px -20px',
		onclick:function () {
			editor.execCommand(uiName);
		}
	});
	editor.addListener('selectionchange', function () {
		var state = editor.queryCommandState(uiName);
		if (state == -1) {
			btn.setDisabled(true);
			btn.setChecked(false);
		} else {
			btn.setDisabled(false);
			btn.setChecked(state);
		}
	});
	return btn;
}, 20);


var myyapp = angular.module('myyapp',['ng.ueditor','ui.sortable']);
myyapp.controller('ctr',['$scope',function($scope){
	
	$scope.modules = [
		{
			name : 'slide',
			title : '幻灯片',
			showimg : 'slide.png',
			icon : 'icon-carousel',
			params : {
				ischange : 0,
				changetime : 3,
				changelast : 500,
				imgheight : 50,
				pointcolor : '#dddddd',
				actcolor : '#585656',
				showpoint : 0,
				data : [
					{
						id : '00000001',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/stemp.png',
						type : 'url',
					}
				]
			}
		},
		{
			name : 'nav',
			title : '导航',
			showimg : 'nav.png',
			icon : 'icon-top-nav',
			params : {
				num : 4,
				radius : 0,
				padding : 5,
				paddingv : 5,
				imgheight : 18,
				imgwid : 18,
				martop : 5,
				bgcolor : '#ffffff',
				fontcolor : '#000',
				data : [
					{
						id : '00000001',
						title : '导航名称',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
						type : 'url',
						url : ''
					},
					{
						id : '00000002',
						title : '导航名称',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
						type : 'url',
						url : ''
					},
					{
						id : '00000003',
						title : '导航名称',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
						type : 'url',
						url : ''
					},
					{
						id : '00000004',
						title : '导航名称',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
						type : 'url',
						url : ''
					},
					{
						id : '00000005',
						title : '导航名称',
						img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
						type : 'url',
						url : ''
					},																		
				]
			}
		},
		{
			name : 'image',
			title : '图片',
			showimg : 'image.png',
			icon : 'icon-picture',
			params : {
				padding : 1,
				pdimg : 100,
				imgheight : 50,
				type : 1,
				istext : 0,
				fontsize : 14,
				fontcolor : '#333',
				bgcolor : '#ffffff',
				fonttop : 0,
				pimg : 1,
				ismengc : 0,
				mengcbg : '#000000',
				trans : 5,
				mcbg : {a:0,b:0,c:0},
				radius : 0,
				mccolor : '#ffffff',
				mctsize : 16,
				mcsize : 14,
				data : [
					{
						id : '00000001',
						img : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/stemp.png',
						url : '',
						title : '',
						type : 'url',
					}
				]

			}
		},
		{
			name : 'card',
			title : '卡片',
			showimg : 'acard.png',
			icon : 'icon-vip-card',
			params : {
				margin : 5,
				type : 'url',
				bgcolor : '#ffffff',
				ishead : 0,
				isbot : 0,
				headsize : 14,
				headcontent : '卡头',
				headcolor : '#333333',
				headalign : 'center',
				notice : '[查看更多]',
				noticecolor : '#ed414a',
				indent : 35,

				botcontent : '卡尾',
				botcolor : '#333',
				botsize : 14,
				botalign : 'center',

				content : '卡片内容',
				fontsize : 14,
			}
		},
		{
			name : 'list',
			title : '列表',
			showimg : 'list.png',
			icon : 'icon-dynamic-classify',
			params : {
				bgcolor : '#ffffff',
				color : '#333',
				titlesize : 14,
				descsize : 14,
				padding : 5,
				imgwidth : 50,
				imgheight : 50,
				data : [
					{
						id : '00000001',
						img : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/stemp.png',
						type : 'url',
						url : '',
						title : '',
						desc : '',
					}
				]
			}
		},			
		{
			name : 'text',
			title : '富文本',
			showimg : 'txt.png',
			icon : 'icon-text',
			params : {
				bgcolor : '#ffffff',
				margin : 0,
				padding : 5,
				content : '请输入内容'
			}
		},
		{
			name : 'space',
			title : '空白',
			showimg : 'space.png',
			icon : 'icon-list-vessel',
			params : {
				height : '10',
				bgcolor : '#f3f4f5'
			}
		},
		{
			name : 'title',
			title : '标题',
			showimg : 'title.png',
			icon : 'icon-title-ele',
			params : {
				content : "标题内容",
				paddingv : '10',
				paddingh : '10',
				bgcolor : '#ffffff',
				color : '#333',
				size : 14,
				pos : 'left',
				lefticon : 0,
				leftimg : '',
				lwidth : 20,
				fonticon : 0,
				fontimg : '',
				fontwidth : 20,
				
				leftbord : 0,
				bordbg : '#ed414a',

				righticon : 0,
				rightimg : '',
				rwidth : 20,
				type : 'url',
				url : '',
				rightfont : '',
				rfontcolor : '#999999',
			}
		},
		{
			name : 'newstitle',
			title : '新闻头',
			showimg : 'arthead.png',
			icon : 'icon-bbs',
			params : {
				padding : '10',
				size : 18,
				bgcolor : '#ffffff',
				color2 : '#999',
				color : '#333',
				content : '请修改标题',
				time : new Date().getFullYear() +'-'+ new Date().getMonth() +'-'+ new Date().getDate(),
				author : ''
			}
		},
		{
			name : 'form',
			title : '表单',
			showimg : 'form.png',
			icon : 'icon-form-vessel',
			params : {
				bgcolor : '#ffffff',
				padding : 5,
				btnbg : '#ed414a',
				btncolor : '#ffffff',
				color : '#333333',
				placolor : '#999999',
				data : [
					{
						id : '00000001',
						type : 'input',
						name : '名称',
						pla : '',
						ismust : 0, // 0是必须填写
					}
				]
			}
		},
		{
			name : 'video',
			title : '视频',
			showimg : 'v.png',
			icon : 'icon-video',
			params : {
				bgcolor : '#ffffff',
				padding : 5,
				url : '',
				type : 1,
				isauto : 0,
			}
		},
		{
			name : 'audio',
			title : '音频',
			showimg : 'v.png',
			icon : 'icon-audio',
			params : {
				bgcolor : '#ffffff',
				ncolor : '#333333',
				dcolor : '#333333',
				padding : 5,
				url : '',
				type : 1,
				isauto : 0,
			}
		},		
		{
			name : 'groupbtn',
			title : '按钮组',
			showimg : 'btn.png',
			icon : 'icon-button',
			params : {
				mbg : '#ffffff',
				paddingv : 5,
				mar : 5,
				bgcolor : '#ed414a',
				bordercolor : '#f6f6f6',
				color : '#ffffff',
				height : 40,
				width : 90,
				size  : 14,
				width : 100,
				radius : 2,
				inpaddingw : 0,
				inpaddingh : 10,
				data: [{
					id : 1,
					title : '文字内容',
					type : 'url',
				}]
			}
		},
		{
			name : 'tabbtn',
			title : 'tab按钮',
			showimg : 'tabbtn.jpg',
			icon : 'icon-add-assemble',
			params : {
				padding : 5,
				paddingv : 0,
				fontp : 5,
				cpadding : 5,
				cpaddingv : 5,
				margintop : 2,
				cbgcolor : '#ffffff',
				bgcolor : '#ffffff',
				actcolor : '#ed414a',
				color : '#999999',
				fontsize  : 14,
				data: [{
					id : 1,
					name : '按钮',
					text : '文字内容',
				}]
			}
		},
		{
			name : 'fix',
			title : '悬浮',
			showimg : 'fix.png',
			icon : 'icon-suspension',
			params : {
				img : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
				mbg : 'rgba(255, 255, 255, 0)',
				padding : 0,
				height : 25,
				width : 25,
				type : 'url',
				top : 40,
				right : 0,
			}
		},
		{
			name : 'article',
			title : '文章列表',
			showimg : 'al.png',
			icon : 'icon-advanced-list',
			params : {
				title : '行业新闻',
				botfont : '更多内容',
				bgcolor : '#ffffff',
				paddingv : 10,
				paddings : 10,
				topcolor : '#44b549',
				topsize : 20,
				botcolor : '#44b549',
				botbord : '#44b549',
				titlecolor : '#333333',
				timecolor : '#8c8c8c',
				showtype : 0,
				ordertype : 0,
				goodnum : 6,
				data : [],
			}
		},
		{
			name : 'searchart',
			title : '搜索文章',
			icon : 'icon-search',
			showimg : 'sa.png',
			params : {
				bgcolor : '#eeeeee',
				incolor : '#ffffff',
				placolor : '#999999',
				paddingv : 5,
				paddingh : 5,
				radius : 20,
				pla:'搜索文章'
			}
		},
		{
			name : 'product',
			title : '产品列表',
			icon : 'icon-advanced-list',
			params : {
				botfont : '更多内容',
				bgcolor : '#ffffff',
				paddingv : 10,
				paddings : 10,
				topcolor : '#44b549',
				topsize : 20,
				botcolor : '#44b549',
				botbord : '#44b549',
				titlecolor : '#333333',
				showtype : 0,
				ordertype : 0,
				goodnum : 6,
				data : [],
			}
		},
		{
			name : 'webview',
			title : '网页容器',
			icon : 'icon-Information',
			params : {
				url : '',
			}
		},
		{
			name : 'toshop',
			title : '导航到店',
			showimg : 'loc.png',
			icon : 'icon-location',
			params : {
				name : '店名',
				address : '详细地址',
				bgcolor : '#ffffff',
				padding : 10,
				type : 'map',
				topcolor : '#333333',
				botcolor : '#999999',
				loc : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/loc.png',
			}
		},
		{
			name : 'map',
			title : '地图',
			showimg : 'map.png',
			icon : 'icon-map',
			params : {
				name : '店名',
				address : '详细地址',
				bgcolor : '#ffffff',
				fontbg : '#ffffff',
				fontcolor : '#000000',
				padding : 10,
				height : 100,
				type : 'map',
				topcolor : '#333333',
				botcolor : '#999999',
				loc : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/loc.png',
			}
		},
		{
			name : 'ad',
			title : '广播',
			showimg : 'ad.png',
			icon : 'icon-announce-component',
			params : {
				bgcolor : '#ffffff',
				type : 'url',
				tobgcolor : '#ffffff',
				color : '#333333',
				leftimg : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/laba.png',
				fontsize : 14,
				height : 30,
				padding : 5,
				imgwidth : 25,
				paddingv : 5,
				adtype : 0,
				text : '',
				data : [
					{
						id : '00000001',
						type : 'url',
						url : '',
						text : '',
					}
				]
			}
		},
		{
			name : 'wxad',
			title : '流量主广告',
			icon : 'icon-advertising',
			params : {
				code : '',
			}
		},
		{
			name : 'head',
			title : '会员头像',
			showimg : 'head.png',
			icon : 'icon-bbs-avatar',
			params : {
				style : 0,
				headimg : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/usertemp.jpg',
				bgcolor : '#ffffff',
				nickcolor : '#333333',
				nicksize : 16,
				topbot : 20,
			}
		},
		{
			name : 'link',
			title : '标题链接',
			showimg : 'link.png',
			icon : 'icon-title-ele',
			params : {
				bgcolor : '#ffffff',
				fontcolor : '#333333',
				fontsize : 14,
				padding : 10,
				paddingv : 5,
				pin : 5,
				isborder : 0,
				bdcolor : '#eeeeee',
				data : [
					{
						id : '00000001',
						type : 'url',
						url : '',
						text : '文字内容',
					}
				]
			}
		},		
	];


	$scope.urlarr = [];
	$scope.urlarr.push({
		name : '模板页面',
		key : 'page',
		data : [],
	});
	$scope.urlarr.push({
		name : '文章页面',
		key : 'news',
		data : [],
	});	
	$scope.urlarr.push({
		name : '文章分类',
		key : 'artsort',
		data : [],
	});
	$scope.urlarr.push({
		name : '产品页面',
		key : 'product',
		data : [],
	});	
	$scope.urlarr.push({
		name : '产品分类',
		key : 'prosort',
		data : [],
	});	
	$scope.urlarr.push({
		name : '官网系统页面',
		key : 'other',
		data : [
			{title:'查看表单数据',url:'/zxbddiy_sitetemp/pages/form/form'},
			{title:'文章列表',url:'/zxbddiy_sitetemp/pages/artlist/artlist?tid='+tempid},
			{title:'产品列表',url:'/zxbddiy_sitetemp/pages/product/list?tid='+tempid},
		],
	});

	$scope.shopurl = [];
	if( auth && auth.isshop == 1 ) {

		$scope.goodtowsort = goodtowsort;

		$scope.modules.push({
			name : 'goods',
			title : '商城商品',
			showimg : 'gl.png',
			icon : 'icon-goods-list',
			params : {
				bgcolor : '#eeeee',
				style : 1,
				botfont : '查看更多',
				paddingv : 5,
				paddings : 5,
				istitle : 0,
				titlepos : 'left',
				ordertype : 0,
				isprice : 0,
				showtype : 0,
				goodnum : 10,
				data : []
			}
		});
		$scope.modules.push({
			name : 'searchgoods',
			title : '搜索商品',
			showimg : 'sg.png',
			icon : 'icon-search',
			params : {
				bgcolor : '#eeeeee',
				paddingv : 5,
				paddingh : 5,
				radius : 20,
				pla:'搜索商品'
			}
		});

		$scope.urlarr.push({
			name : '商城系统页面',
			key : 'shop',
			data : [
				{title:'商品分类',url:'/zxbddiy_sitetemp/pages/gsort/gsort?tid='+tempid},
				{title:'商品列表',url:'/zxbddiy_sitetemp/pages/goodlist/goodlist?tid='+tempid},
				{title:'购物车',url:'/zxbddiy_sitetemp/pages/cart/cart?tid='+tempid},
				{title:'个人中心',url:'/zxbddiy_sitetemp/pages/user/user?tid='+tempid},
				{title:'订单列表',url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?tid='+tempid},
				{title:'选购界面',url:'/zxbddiy_sitetemp/pages/exbuy/exbuy?tid='+tempid},
				{title:'订单管理(管理员)',url:'/zxbddiy_sitetemp/pages/adminshop/orderlist?tid='+tempid},
				{title:'地址管理',url:'/zxbddiy_sitetemp/pages/user/address?tid='+tempid},
				{title:'待付款订单',url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?type=0&tid='+tempid},
				{title:lawyer == 1 ? '待履行订单' : '已付款订单' ,url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?type=1&tid='+tempid},
				{title:lawyer == 1 ? '正履行订单' : '已发货订单',url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?type=2&tid='+tempid},
				{title:'已完成订单',url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?type=3&tid='+tempid},
				{title:'已退款订单',url:'/zxbddiy_sitetemp/pages/orderlist/orderlist?type=4&tid='+tempid},
			],
		});

		$scope.urlarr.push({
			name : '商城商品',
			key : 'goods',
			data : [],
		});
		$scope.urlarr.push({
			name : '商城一级分类',
			key : 'sortone',
			data : [],
		});
		$scope.urlarr.push({
			name : '商城二级分类',
			key : 'sorttwo',
			data : [],
		});

	}
	$scope.isshop = auth && auth.isshop == 1 ? 1 : 0;


	$scope.orderurl = [];
	if( auth && auth.isdesk == 1 ) {

		$scope.ordertowsort = ordertowsort;

		$scope.modules.push({
			name : 'ogoods',
			title : '点餐商品',
			showimg : 'gl.png',
			icon : 'icon-goods-list',
			params : {
				bgcolor : '#eeeee',
				style : 1,
				botfont : '查看更多',
				paddingv : 5,
				paddings : 5,
				istitle : 0,
				titlepos : 'left',
				ordertype : 0,
				isprice : 0,
				showtype : 0,
				goodnum : 10,
				data : []
			}
		});
		/*$scope.modules.push({
			name : 'osearchgoods',
			title : '搜索点餐商品',
			params : {
				bgcolor : '#eeeeee',
				paddingv : 5,
				paddingh : 5,
				radius : 20,
				pla:'搜索商品'
			}
		});*/

		$scope.urlarr.push({
			name : '点餐系统页面',
			key : 'order',
			data : [
				/*{title:'商品分类',url:'/zxbddiy_sitetemp/pages/gsort/gsort?tid='+tempid},*/
				/*{title:'商品列表',url:'/zxbddiy_sitetemp/pages/goodlist/goodlist?tid='+tempid},*/
				{title:'个人中心',url:'/zxbddiy_sitetemp/pages/deskorder/user?tid='+tempid},
				{title:'订单列表',url:'/zxbddiy_sitetemp/pages/deskorder/orderlist?tid='+tempid},
				{title:'点餐界面',url:'/zxbddiy_sitetemp/pages/deskorder/exbuy?tid='+tempid},
				{title:'订单管理(管理员)',url:'/zxbddiy_sitetemp/pages/deskorder/adminolist?tid='+tempid},
			],
		});

		$scope.urlarr.push({
			name : '点餐商品',
			key : 'ordergoods',
			data : [],
		});
		/*$scope.urlarr.push({
			name : '点餐一级分类',
			key : 'ordersortone',
			data : [],
		});
		$scope.urlarr.push({
			name : '点餐二级分类',
			key : 'ordersorttwo',
			data : [],
		});*/
	}
	$scope.isorder = auth && auth.isdesk == 1 ? 1 : 0;


	$scope.appointurl = [];
	if( auth && auth.isappoint == 1 ) {

		$scope.appsort = appsort;

		$scope.modules.push({
			name : 'appoint',
			title : '预约项目',
			showimg : 'app.png',
			icon : 'icon-goods-list',
			params : {
				bgcolor : '#eeeeee',
				style : 1,
				botfont : '查看更多',
				paddingv : 5,
				botbord : '#333333',
				botcolor : '#333333',
				topbgcolor : '#f7edec',
				topcolor : '#9a4e5a',
				pricecolor : '#f15445',
				fontbgcolor : '#98499c',
				fontcolor : '#ffffff',
				btnfont : '预约',
				showtype : 0,
				goodnum : 4,
				ordertype : 0,
				data : []
			}
		});

		$scope.urlarr.push({
			name : '预约系统页面',
			key : 'appointp',
			data : [
				{title:'预约列表',url:'/zxbddiy_sitetemp/pages/appoint/list?tid='+tempid},
				{title:'我的预约',url:'/zxbddiy_sitetemp/pages/appoint/olist?tid='+tempid},
				{title:'预约管理(管理员)',url:'/zxbddiy_sitetemp/pages/appoint/adminlist?tid='+tempid},
			],
		});

		$scope.urlarr.push({
			name : '预约项目',
			key : 'appoint',
			data : [],
		});
		$scope.urlarr.push({
			name : '预约分类',
			key : 'appsort',
			data : [],
		});
	}
	$scope.isappoint = auth && auth.isappoint == 1 ? 1 : 0;

	$scope.cardurl = [];
	if( auth && auth.iscard == 1 ) {
		$scope.modules.push({
			name : 'actcard',
			title : '卡券',
			showimg : 'actcard.png',
			icon : 'icon-coupon',
			params : {
				style : 1,
				padding : 5,
				bgcolor : '#eeeeee',
				topbgcolor : '#f7edec',
				botbgcolor : '#65cbd0',
				pricecolor : '#f15445',
				usecolor : '#999999',
				namecolor: '#ffffff',
				botfont : '查看更多',
				botbord : '#333333',
				botcolor : '#333333',
				showtype : 0,
				goodnum : 2,
				sortname : '官网系统卡券',
				sortid : 0,
				data : []
			}
		});

		$scope.urlarr.push({
			name : '卡券系统页面',
			key : 'cardurl',
			data : [
				{title:'我的卡券',url:'/zxbddiy_sitetemp/pages/card/mycard?tid='+tempid},
				{title:'官网卡券列表',url:'/zxbddiy_sitetemp/pages/card/list?type=0&tid='+tempid},
			],
		});
			
		/*$scope.urlarr.push({
			name : '卡券列表',
			key : 'card',
			data : [],
		});*/

	}
	$scope.iscard = auth && auth.iscard == 1 ? 1 : 0;
	$scope.params = page && page.params ? page.params : {}; // 初始数据
	
	if( page && page.params ){
	    angular.forEach($scope.params.data,function(m,i){
	       	if(m.name == 'text'){
	        	m.params.content = decodeURIComponent(m.params.content);
	        }
	    })
	}

	$scope.focus = {
		id : '00000000',
		name : 'basic'
	};
	if( opp == 'bar' ){
		$scope.focus = {
			id : 'bar',
			name : 'bar'
		};
	}
	
	$scope.prosort = prosort;
	
	$scope.article = article;
	$scope.allsort = allsort;
	$scope.op = op;

	$scope.qrurl = window.sysinfo.siteroot + '/web/index.php?c=site&a=entry&op=page&do=img&m=zxbddiy_sitetemp&version_id='+version_id;
	$scope.alltemppage = alltemppage;

	$scope.pid = page && page.id ? page.id : 0;
	$scope.items = page && page.params && page.params.data ? page.params.data : [];

	$scope.basic =  page && page.params && page.params.basic ? page.params.basic : {
		id:'0000000',
		ftype : 'url',
		name : '',
		title : '',
		sharetitle : '',
		shareimg : '',
		isbar : 0,
		pbot : 40,
		topbg : '#000000',
		pagebg : '#eeeeee',
		topcolor : '#ffffff',
		isf : 0,
	};
	
	/*bar*/
	$scope.tempid = tempid;
    $scope.isacttemp = isacttemp;
	$scope.nav = {
		num : 4,
        bartype : 0,
		padding : 5,
		bgcolor : '#ffffff',
        maincolor : '#ed414a',
		color : '#999',
        topbg : '#000000',
        topcolor : '#ffffff',
		actcolor : '#ed414a',
        bjcolor : '#eeeeee',
        paddingtop : 5,
        paddingbot : 3,
        iconwidth : 20,
        fontsize : 14,
        padfont : 2,
        fontsizesys : 0,
		data : [
			{
				id : '00000001',
				name : '导航名称',
				img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/home1.png',
				actimg: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/home2.png',
				url : '',
                type : 'url',
			},
			{
				id : '00000002',
				name : '导航名称',
				img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/tel1.png',
				actimg: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/tel2.png',
				url : '',
                type : 'url',
			},
            {
                id : '00000003',
                name : '导航名称',
                img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/location1.png',
                actimg: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/location2.png',
                url : '',
                type : 'url',
            },
            {
                id : '00000004',
                name : '导航名称',
                img: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/contact1.png',
                actimg: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/contact2.png',
                url : '',
                type : 'url',
            },            																
		]
	};


    
    $scope.iconlist = [
    ['home1.png','home2.png'],['tel1.png','tel2.png'],['location1.png','location2.png'],['share1.png','share2.png'],['about1.png','about2.png'],
       ['a_1.png','a_2.png'],['a_3.png','a_4.png'],['a_5.png','a_6.png'],['a_7.png','a_8.png'],
       ['b_1.png','b_2.png'],['b_3.png','b_4.png'],['b_5.png','b_6.png'],['b_7.png','b_8.png'],
       ['c_1.png','c_2.png'],['c_3.png','c_4.png'],['c_5.png','c_6.png'],['c_7.png','c_8.png'],['c_9.png','c_10.png'],['c_11.png','c_12.png'],
       ['d_1.png','d_2.png'],['d_3.png','d_4.png'],['d_5.png','d_6.png'],['d_7.png','d_8.png'],['d_9.png','d_10.png'],
       ['e_1.png','e_2.png'],['e_3.png','e_4.png'],['e_5.png','e_6.png'],['e_7.png','e_8.png'],['e_9.png','e_10.png'],
       ['f_1.png','f_2.png'],['f_3.png','f_4.png'],['f_5.png','f_6.png'],['f_7.png','f_8.png'],
    ];
    $scope.bar = bar && bar.data ? bar.data : $scope.nav; // 初始数据

	$scope.changeNum = function( num ){
        var nowlength = $scope.bar.data.length;
        var diff = num - nowlength;
        $scope.bar.num = num;
        if( diff > 0 ) {
        	for (var i = 0; i < diff; i++) {
        		var mid = 'm'+i+new Date().getTime();
				$scope.bar.data.push({
					id : mid,
					img : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
					actimg: window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/thank.png',
					url : '',
					name : '导航名称',
                    type : 'url',
				})
        	}
        	
        }else if( diff < 0 ){

        	for (var i = -diff; i > 0; i--) {
        		$scope.bar.data.splice(nowlength+diff,1);
        	}
        }
	};

    $scope.loadindex = 0;
    $scope.loadicon = function(i){
        $scope.loadindex = i;
        $('.my_model[icon]').show();
    };

    $scope.seticon = function(a,b){
        $scope.bar.data[$scope.loadindex].img = window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/icon/'+a;
        $scope.bar.data[$scope.loadindex].actimg = window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/icon/'+b;
        $('.my_model[icon]').hide();
    }

	/*bar end*/


	//点击动作
	$scope.clickEvents = [
		{name:'跳转页面',type:'url'},
		{name:'跳转网页',type:'weburl'},
		{name:'其他小程序',type:'other'},
		{name:'拨打电话',type:'tel'},
		{name:'分享提示',type:'share'},
		{name:'地图位置',type:'map'},
		{name:'联系客服',type:'kefu'},
		{name:'打开图片',type:'images'},
		{name:'扫一扫',type:'scan'},
		{name:'复制文字',type:'copy'}
	];



	$scope.selectType = function(type){
		if(type == 1){
			$scope.params.arttype = 1;
		}else{
			$scope.params.arttype = 2;
			var newitem = $.extend(true,{},$scope.imgmodule);
			$scope.items.push(newitem);
			$scope.imgfocus = '00000001'; // 图片组默认焦点
		}
		$scope.params.isselect = 1;
	};

	$scope.imageType = function( tid,type ){

	    angular.forEach($scope.items, function(obj){
	        if(obj.id== tid){

	            var nowlength = obj.params.data.length;
	            obj.params.type = type;

	            var num = type  > 3 ? 3 : type;
	            var diff = num - nowlength;
	            
	            if( diff > 0 ) {
	            	for (var i = 0; i < diff; i++) {
	            		var mid = 'm'+i+new Date().getTime();
						obj.params.data.push({
							id : mid,
							img : window.sysinfo.siteroot+'/addons/zxbddiy_sitetemp/public/images/stemp.png',
							type : 'url',
							url : '',
							title : '',
						})
	            	}
	            	
	            }else if( diff < 0 ){
	            	for (var i = -diff; i > 0; i--) {
	            		obj.params.data.splice(i,1);
	            	}
	            }
	            return false;
	        }
	    });
	};
	
	$scope.upvedio = function(item){
		require(['fileUploader'], function(uploader){
			uploader.show(function(video){
				if (!video) {
					return;
				} else {
					var videoType = video.isRemote ? 'iframe' : 'video';
					if( videoType == 'video' ){
						item.url = video.url;
						$scope.$apply();
					}else{
						alert('需要上传本地视频');
					}
				}
			}, {type : 'video', allowUploadVideo : 'true'});
		});
	};

	// 编辑删除页面
	$scope.geteditpage = function( id,type ){
		if( id == $scope.pid ) {
			webAlert('正在编辑的页面不能进行此操作');
			return false;
		}
		if( type == 'delete' ) {
			if( confirm('删除后不可恢复，确定要删除此页面吗？') ){
				Http('post','json','deletepage',{id:id},function(data){
					webAlert( data.res );
					if( data.status == 200 ){
						angular.forEach(alltemppage, function(m,i){
							if( m.id == id ) {
				        		alltemppage.splice(i,1);
							}
						});
					}
					$scope.$apply();
				},true);
			}
		}else if( type == 'edit' ){
			if( confirm('加载此页面编辑会清空当前已编辑的内容，请确保当前编辑的内容已保存。确定要编辑此页面吗？') ){
				Http('post','json','loadpagebyid',{id:id},function(data){
					if( data.status == 200 ){
						$scope.pid = data.obj.id;
					    angular.forEach(data.obj.params.data,function(m,i){
					       	if(m.name == 'text'){
					        	m.params.content = decodeURIComponent(m.params.content);
					        }
					    })

						$scope.items = data.obj.params.data;
						$scope.basic =  data.obj.params.basic;
						//$scope.ltype = 'module';
						$scope.upSlider();
					}
					$scope.$apply();
				},true);
			}
		}

	}

	// 添加新页面
	$scope.addNewPage = function(){
		if( confirm('添加新页面会清空当前已编辑的内容，请确保当前编辑的内容已保存。确定要添加新页面吗？') ){
			$scope.pid = 0;
			$scope.items = [];
			$scope.basic =  {
				id:'0000000',
				ftype : 'url',
				name : '',
				title : '',
				sharetitle : '',
				shareimg : '',
				isbar : 0,
				pbot : 40,
				topbg : '#000000',
				pagebg : '#eeeeee',
				topcolor : '#ffffff',
				isf : 0,
			};
			$scope.ltype = 'module';
			$scope.focus = {
				id : '00000000',
				name : 'basic'
			};
			$scope.rtype = 'style';
		}
		$scope.upSlider();
	}

	$scope.addTabbtn = function(data){
		var id = 'b'+ new Date().getTime();
		data.push({id:id,name:'按钮',text:'内容'});
	}
	$scope.deleteTabbtn = function(data,id){
		angular.forEach(data, function(obj,i){
			if( obj.id == id ){
				data.splice(i,1);
			}
		})
	}

	$scope.addForm = function( id,type ){
		angular.forEach($scope.items, function(obj){
			if( obj.id == id ) {
        		var mid = 'm'+ new Date().getTime();
				obj.params.data.push({
					id : mid,
					type : type,
					name : '名称',
					pla : '',
					data : [],
				})
			}
		});
	};

	$scope.addFormIn = function(id,iid){
		angular.forEach($scope.items, function(obj){
			if( obj.id == id ) {
				angular.forEach(obj.params.data, function(objin){
					if( objin.id == iid ) {
		        		var fid = 'f'+ new Date().getTime();
						objin.data.push(
							{id : fid,name : ''}
						);
		        		
					}
				});				
			}
		});
	};


	$scope.deleteFormIn = function(id,iid,fid){
		angular.forEach($scope.items, function(obj){
			if( obj.id == id ) {
				angular.forEach(obj.params.data, function(obji){
					if( obji.id == iid ) {
						angular.forEach(obji.data, function(objf,f){
							if( objf.id == fid ) {
				        		obji.data.splice(f,1);
							}
						});
					}
				});				
			}
		});
	}

	// 保存数据
	$scope.issaving = false;
	$scope.saveData = function(){
		if($scope.issaving) return false;
		
        var arr = [];
        $(".view_item,.view_item_fix").each(function(i) {
            arr[i] = $(this).attr('viewid');
        });
        var newarr = [];
        angular.forEach(arr, function(aid){
        	
            angular.forEach($scope.items, function(obj){
                if(obj.id== aid){
                	var newdata = $.extend(true,{},obj);
                    newarr.push( newdata );
                    return false;
                }
            });
        });
		

        angular.forEach(newarr,function(m,i){
        	if(m.name == 'text'){
        		m.params.content = encodeURIComponent(m.params.content);
        	}
        })
        
		var items = angular.toJson(newarr);
		var basic = angular.toJson($scope.basic);
		$scope.issaving = true;
        $.ajax({
            type: 'POST',
            dataType : 'json',
            url: window.sysinfo.siteroot + '/web/index.php?c=site&a=entry&op=savepage&do=ajax&m=zxbddiy_sitetemp&version_id='+version_id,
            data: {data : items,basic : basic,tid : tempid,id : $scope.pid,bar : $scope.bar },
            success: function(data){
                if(data.status == 200){
                    webAlert("已保存！");
					if( $scope.pid > 0 ) { // 编辑的
					    angular.forEach($scope.alltemppage,function(m,i){
					       	if(m.id == $scope.pid){
					        	m.name = data.obj.name;
					        }
					    })
					}else{
						$scope.alltemppage = typeof $scope.alltemppage == 'object' ? $scope.alltemppage : [];
						$scope.alltemppage.push({id:data.obj.id,name:data.obj.name});
						$scope.pid = data.obj.id;
					}
					$scope.$apply();
            	}else{
                    webAlert(data.res);
                }
            },
            error: function(){
                webAlert('保存失败请重试,如果站点设置了cdn加速可能被cdn拦截了。');
            },
            complete : function(){
            	$scope.issaving = false;
            }
        })
	}

	$scope.changeS = function(type,s){
		if(type == 'status') $scope.mystatus = s;
		if(type == 'top') $scope.mytop = s;
	}


	$scope.addGoodImages = function(){
		var newitem = $.extend(true,{},$scope.imgmodule);
		newitem.id = 'i'+ new Date().getTime();
		$scope.items.push(newitem);
		$scope.imgfocus = newitem.id;
		$scope.getFocus('00000002');
	};

	$scope.seturltype = 'page';
	$scope.pagetype = function( type ){
		$scope.seturltype = type;
	}

	// 导入页面
	$scope.isloaded = false;
	$scope.loadpagelist = [];
	$scope.loadpage = function(){
		if( $scope.loadpagelist.length <= 0 ) {
			Http('post','json','loadpagelist',{},function(data){
				if( data.obj.length > 0 ){
					$scope.loadpagelist = data.obj;
				}
				$scope.$apply();
			},true);
		}
		$('.my_model[loadpage]').show();
	};

	// 选择页面
	$scope.loadPageByid = function( id ){
		if( confirm('此功能是将以前设计的页面导入，可进行编辑添加页面，确定导入页面吗？') ) {
			//location.href = window.sysinfo.siteroot + '/web/index.php?c=site&a=entry&op=add&do=page&p=site&m=zxbddiy_sitetemp'+'&tid='+tempid + '&lid='+id ;
			Http('post','json','loadpagebyid',{id:id},function(data){
				if( data.status == 200 ){
				    angular.forEach(data.obj.params.data,function(m,i){
				       	if(m.name == 'text'){
				        	m.params.content = decodeURIComponent(m.params.content);
				        }
				    })
					$scope.items = data.obj.params.data;
					$scope.basic =  data.obj.params.basic;
				}else{
					webAlert( data.res );
				}
				$scope.$apply();
				$('.my_model[loadpage]').hide();
			},true);
		}
	};

	// 设置链接
	$scope.setlinkiid = 0;
	$scope.setlinkdid = 0;
	$scope.allpage = null;
	$scope.allapp = null;
	$scope.allnews = null;
	$scope.allgoods = null; // 商品
	$scope.allonesorts = null; // 1分类
	$scope.alltwosorts = null; // 2分类
	$scope.artsort = null; // 文章分类
	$scope.allappoint = null; // 预约项目

	$scope.allcard = null; // 卡券
	

	$scope.urltype = '';
	$scope.showLink = function(itemid,dataid,type){
		$scope.urltype = type;
		$scope.setlinkiid = itemid;
		if( dataid ) {
			$scope.setlinkdid = dataid;
		}else{
			$scope.setlinkdid = 0; // 来自第一级的设为0
		}

		if( $scope.urlarr[0].data.length <= 0 && type == 'my' ) {
			Http('post','json','getlink',{tid : tempid},function(data){

				for ( var t in data.obj ) {
					for (var i = 0; i < $scope.urlarr.length; i++) {
						if( t == $scope.urlarr[i].key ){
							$scope.urlarr[i].data = data.obj[t];
						}
					}
				}
				/*if( data.obj.page.length > 0 ){
					$scope.allpage = data.obj.page;
				}
				if( data.obj.news.length > 0 ){
					$scope.allnews = data.obj.news;
				}
				if( data.obj.goods.length > 0 ){
					$scope.allgoods = data.obj.goods;
				}				
				if( data.obj.sortone.length > 0 ){
					$scope.allonesorts = data.obj.sortone;
				}
				if( data.obj.sorttwo.length > 0 ){
					$scope.alltwosorts = data.obj.sorttwo;
				}
				if( data.obj.artsort.length > 0 ){
					$scope.artsort = data.obj.artsort;
				}
				if( data.obj.appoint.length > 0 ){
					$scope.allappoint = data.obj.appoint;
				}*/															
				$scope.$apply();
			},true);
		}

		// 小程序链接
		if( !$scope.allapp && type == 'app' ) {
			Http('post','json','getapp',{tid : tempid},function(data){
				//webAlert(data.res);
				if( data.obj.length > 0 ){
					$scope.allapp = data.obj;
				}
				$scope.$apply();
			},true);
		}

		$('.my_model[url]').show();
	};


	// 选择商品或文章
	$scope.actgoodid = null;
	$scope.showgoodtype = '';
	$scope.showAddgoods = function(id,type){
		$scope.actgoodid = id;
		$scope.showgoodtype = type;

		if( type == 'good' || type == 'ogood' ){
			Http('post','json','getgoods',{type : type == 'good' ? 0 : 1},function(data){
				if( data.obj.length > 0 ){
					$scope.allgoods = data.obj;
				}
				$scope.$apply();
			},true);
		}
				
		if( !$scope.allnews && type == 'article' ){
			Http('post','json','getarticle',{},function(data){
				if( data.obj.length > 0 ){
					$scope.allnews = data.obj;
				}
				$scope.$apply();
			},true);
		}
		if( !$scope.allnews && type == 'appoint' ){
			Http('post','json','getappoint',{},function(data){
				if( data.obj.length > 0 ){
					$scope.allappoint = data.obj;
				}
				$scope.$apply();
			},true);
		}
		if( !$scope.allcard && type == 'card' ){
			Http('post','json','getcard',{},function(data){
				if( data.obj.length > 0 ){
					$scope.allcard = data.obj;
				}
				$scope.$apply();
			},true);
		}
		if( !$scope.allproduct && type == 'product' ){
			Http('post','json','getproduct',{},function(data){
				if( data.obj.length > 0 ){
					$scope.allproduct = data.obj;
				}
				$scope.$apply();
			},true);
		}
		$('.my_model[goods]').show();
	}

	$scope.selectGoods = function(item){

		angular.forEach($scope.items, function(obj){
			if( obj.id == $scope.actgoodid ) {
				var id = 'g'+new Date().getTime();
		   		var newgood = $.extend(true,{},item);
		   		newgood.id = id;
		   		newgood.gid = item.id;

		   		if( typeof obj.params.data == 'undefined' ) obj.params.data = [];
		   		obj.params.data.push(newgood);	
		   		//$scope.$apply();
			}
		});

	}

	$scope.setLink = function(url,name,uid){
		if( $scope.setlinkiid == 'fimg' ) {
            $scope.basic.furl = url;
            $scope.basic.furlname = name;

		}else if( $scope.setlinkiid == 'bar' ) {

		    angular.forEach($scope.bar.data, function(n) {
		        if(n.id == $scope.setlinkdid){
		        	n.pageid = uid ? uid : '-1';
		            n.url = url;
		            n.urlname = name;
		            return false;
		        }
		    });       
		}else{
		    angular.forEach($scope.items, function(m) {
		        if(m.id == $scope.setlinkiid){
		        	if( $scope.setlinkdid ) {
					    angular.forEach(m.params.data, function(n) {
					        if(n.id == $scope.setlinkdid){

					            n.url = url;
					            n.urlname = name;
					            return false;
					        }
					    }); 
		        	}else{
			            m.params.url = url;
			            m.params.urlname = name;
			            return false;
		        	}
		        }
		    }); 
		}
		$('.my_model[url]').hide();
	};	

	// 其他小程序链接
	$scope.setotherLink = function(item,initem){
		console.log( $scope.setlinkiid,$scope.setlinkdid );
		if( $scope.setlinkiid == 'fimg' ) {

            $scope.basic.fappid = item.appid;
            $scope.basic.fappurl = initem.url;

		}else if( $scope.setlinkiid == 'bar' ) {
	        angular.forEach($scope.bar.data, function(m) {
	            if(m.id == $scope.setlinkdid){
	                m.appid = item.appid;
	                m.appurl = initem.url;
	            }
	        });           
		}else{
		    angular.forEach($scope.items, function(m) {
		        if(m.id == $scope.setlinkiid){

		        	if( $scope.setlinkdid ) {
					    angular.forEach(m.params.data, function(n) {
					        if(n.id == $scope.setlinkdid){

					            n.appid = item.appid;
					            n.appurl = initem.url;
					            return false;
					        }
					    }); 
		        	}else{
			            m.params.appid = item.appid;
			            m.params.appurl = initem.url;
			            return false;
		        	}
		        }
		    }); 
		}
		$('.my_model[url]').hide();
	};


    $scope.ismap = false;
    $scope.lng = 0;
    $scope.lat = 0;
    $scope.mapaid = -1;
    $scope.mapdid = -1;

    $scope.showMap = function(aid,did){
        $scope.mapaid = aid;
        $scope.mapdid = did;
        //console.log( aid,did );
        //$scope.lng = $scope.lat = 0;

        if( !$scope.ismap ) {
            $scope.ismap = true;
            var map = new qq.maps.Map(document.getElementById("map"), {
                center: new qq.maps.LatLng(39.916527,116.397128),
                zoom:11,
                disableDefaultUI: true
            });
            //获取城市列表接口设置中心点
            citylocation = new qq.maps.CityService({
                complete : function(result){
                    map.setCenter(result.detail.latLng);
                }
            });
            //调用searchLocalCity();方法    根据用户IP查询城市信息。
            citylocation.searchLocalCity();

            // 点击
            var markers = [];
            qq.maps.event.addListener(map, 'click', function(e) {
                if( markers ) clearOverlays( markers );
                var pointer = new qq.maps.LatLng(e.latLng.lat,e.latLng.lng);
                var marker = new qq.maps.Marker({
                    position: pointer,
                    map: map,
                    animation: qq.maps.MarkerAnimation.BOUNCE
                });

                markers.push( marker );

                $scope.lng = e.latLng.lng;
                $scope.lat = e.latLng.lat;             
            });

            // 检索
            var latlngBounds = new qq.maps.LatLngBounds();
            //调用Poi检索类
            searchService = new qq.maps.SearchService({
                complete : function(results){

                    var pois = results.detail.pois;

                    if( pois && pois.length > 0 ) {

                        for(var i = 0,l = pois.length;i < l; i++){
                            var poi = pois[i];
                            latlngBounds.extend(poi.latLng);  
                            var marker = new qq.maps.Marker({
                                map:map,
                                position: poi.latLng
                            });
                            marker.setTitle(i+1);
                            markers.push(marker);
                        }
                        map.fitBounds(latlngBounds);
                    }else{
                        alert('没有找到');
                    }
                }
            });

            //清除地图上的marker
            function clearOverlays(overlays){
                if( !overlays ) return;
                var overlay;
                while(overlay = overlays.pop()){
                    overlay.setMap(null);
                }
            }

            geocoder = new qq.maps.Geocoder({
                complete : function(result){
                    //console.log( result );
                    map.setCenter(result.detail.location);

                    clearOverlays(markers);
                    searchService.setLocation( result.detail.addressComponents.city );

                    var keyword = document.getElementById("searaddress").value;
                    searchService.search(keyword);

                }
            });


            $('#find_address').click(function(){
                var p = map.getCenter();
                var latLng = new qq.maps.LatLng(p.lat, p.lng);
                geocoder.getAddress(latLng);                
            });

        }

        $('.my_model[map]').show();
    }

    $scope.setLocation = function(){
        if( $scope.lng <= 0 || $scope.lat <= 0 || $scope.mapdid < 0 ) {
            webAlert('请先点击选择坐标');return false;
        }
        if( $scope.mapaid == 'fimg' ) {
			$scope.basic.flng = $scope.lng;
			$scope.basic.flat = $scope.lat;
        }else if( $scope.mapaid == 'bar' ) {
        	$scope.bar.data[ $scope.mapdid ].lng = $scope.lng;
       	 	$scope.bar.data[ $scope.mapdid ].lat = $scope.lat;	
        }else{
	        angular.forEach($scope.items, function(m){
	        	if( m.id == $scope.mapaid ) {
	        		
	        		if( $scope.mapdid && $scope.mapdid != -1 ) {
			            angular.forEach(m.params.data, function(n){
			                if(n.id== $scope.mapdid){
	        					n.lng = $scope.lng;
	        					n.lat = $scope.lat;
			                }
			            });
	        		}else{
	        			m.params.lng = $scope.lng;
	        			m.params.lat = $scope.lat;
	        		}
	        	}
	        });
        }

        $scope.mapaid = -1;
        $scope.mapdid = -1;

        $('.my_model[map]').hide();
    };

    $scope.upaudio = function(type,data){

		require(["util"], function(util){
			
			util.audio('', function(url){
				if( type == 'basic' ) {
					if(url && url.attachment && url.url){
						$scope.basic.audiourl = url.attachment;
					}
					if(url && url.media_id){
						$scope.basic.audiourl = url.media_id;
					}
				}else{
					data.url = url.url;
				}
				console.log( url );
				$scope.$apply();
			}, "" , {"direct":true,"multiple":false,"fileSizeLimit":5120000});
		});
	
    }

	// 上传单张图片
    $scope.uploadImage = function(id,type,iid){
        require(['jquery', 'util'], function($, util){
            util.image('',function(data){
            	if(type == 'shareimg'){
            		$scope.basic.shareimg = data['url'];

            	}else if(type == 'pbgimg'){
            		$scope.basic.bgimg = data['url'];
            	}else if(type == 'fimg'){
            		$scope.basic.fimg = data['url'];
            	}else if(id == 'bar'){
            		$scope.bar[type] = data['url'];
            	}else if(type == 'goodimg'){
            		id.img = data['url'];
            	}else if(type == 'actimg'){
            		id.actimg = data['url'];
            	}else if(type == 'link'){
	                var items = $scope.items;
	                angular.forEach(items, function(m,index) {
	                    if(m.id == id){
	                    	angular.forEach(m.params.data, function(n,i) {
	                    		if( n.id == iid ){
	                    			n.img = data['url'];
	                    		}
	                    	});
	                    }
	                }); 
            	}else{
	                var items = $scope.items;
	                angular.forEach(items, function(m,index) {
	                    if(m.id == id){
	                        m.params[type] = data['url'];
	                    }
	                }); 
            	}
				//处理图片后重置焦点到当前模块
				$scope.$apply();
                //$("div[viewid="+id+"]").trigger("click");
            });
        });
    };

    // 多图上传
    $scope.uploadMultiImage = function(id,iid){
    	
		util.image("", function(urls){
			var arr = [];
			$.each(urls,function(i){
				var id = 'i'+i+new Date().getTime();
				arr.push( { id : id , url : urls[i].url } );
			});
			if( id == 'fimg' ) {
				if( !$scope.basic.fimagesdata ) $scope.basic.fimagesdata = [];
				$scope.basic.fimagesdata.push.apply($scope.basic.fimagesdata,arr);
			}else if( id == 'bar' ) {
	            angular.forEach($scope.bar.data, function(m,index) {
	                if(m.id == iid){
	                    if( !m.imagesdata ) m.imagesdata = [];
	                    m.imagesdata.push.apply(m.imagesdata,arr);
	                }
	            }); 			
			}else{
	            angular.forEach($scope.items, function(m,index) {
	                if(m.id == id){
	                    if( iid ) {
	                    	angular.forEach(m.params.data, function(n,i) {
	                    		if( n.id == iid ){

	                    			if( !n.imagesdata ) n.imagesdata = [];
	                    			n.imagesdata.push.apply(n.imagesdata,arr);
	                    		}
	                    	});
	                    }else{
	                    	if( !m.params.imagesdata ) m.params.imagesdata = [];
	                    	m.params.imagesdata.push.apply(m.params.imagesdata,arr);
	                    }
	                }
	            });
			}

			$scope.$apply();
		}, {multiple:true,direct:false});
    };

    $scope.deleteImagesdata = function(m,i){
    	if( confirm('确定删除吗？') ){
    		angular.forEach(m,function(n,j){
    			if( i == n.id ){
    				m.splice(j,1);
    			}
    		});
    	}
    };

    $scope.sortableOptions = function(){
        $scope.sortableOptions = {
            update: function(e, ui) {
                //console.log("update",e,ui);
                //需要使用延时方法，否则会输出原始数据的顺序，可能是BUG？
                $timeout(function() {
                    var resArr = [];
                    /*for (var i = 0; i < $scope.data.length; i++) {
                        resArr.push($scope.data[i].i);
                    }*/
                });
            },

            // 完成拖拽动作
            stop: function(e, ui) {
                console.log( 'end' );
            }
        }
    }

    $scope.over = function(id){$("div[viewid="+id+"]").find(".del_modules").stop().show();}
    $scope.out = function(id){$("div[viewid="+id+"]").find(".del_modules").stop().hide();}	

   	$scope.getFocus = function(id){
		$scope.focus.id = id;
		if( id == '00000000' ){
			$scope.rtype = 'style';
		}
		/*if($scope.params.arttype == 2 && id == '00000002'){
			height = 400;
		}else{
			var $this = $('div[viewid='+id+']');
       		var height = $this.offset().top;
		}*/

        //var starttop = $('.my_article_box').offset().top;
        //$('.portable_editor').css('margin-top',height-starttop-10);
       // $(document.body).animate({scrollTop:height-100},500);
   };

   $scope.upSlider = function(){
		require(['jquery.ui'],function(){
			$('.slider').each(function(){
				$(this).slider({
					value : $(this).attr('value'),
				})
			});
		})	
   }

   $scope.addgood = function(good){
   		var id = 'g'+new Date().getTime();
   		var newgood = $.extend(true,{},$scope.modules[0].params.data[0]);
   		//console.log(newgood)
   		newgood.id = id;
   		good.push(newgood);
   };

   $scope.addItemData = function(good,type){
   		var id = 'd'+new Date().getTime();
   		var data = {};
   		angular.forEach($scope.modules,function(m,i){
   			if(type == m.name){
   				data = m.params.data[0];
   			}
   		})
   		var newgood = $.extend(true,{},data);
   		newgood.id = id;
   		good.push(newgood);
   };

   	$scope.deleteItem = function(pid,cid){
   		if(cid == 'images'){
	   		angular.forEach($scope.items,function(m,i){
	   			if(pid == m.id){
	   				if($scope.items.length <= 1){
	   					alert('至少一张图片');
	   					return false;
	   				}
	   				$scope.items.splice(i,1);
	   				if(i == 0){
	   					$scope.imgfocus = $scope.items[0].id; // 焦点
	   				}else{
	   					$scope.imgfocus = $scope.items[i-1].id; // 焦点
	   				}
	   				
	   			}
	   		})
   		}else{
	   		angular.forEach($scope.items,function(m,i){
	   			if(m.id == pid){
	   				angular.forEach(m.params.data,function(cm,ci){
	   					if(cm.id == cid){
	   						m.params.data.splice(ci,1);
	   						if(m.params.data.length <= 0){
	   							$scope.items.splice(i,1);
	   						}
	   						return false;
	   					}
	   				})
	   				return false;
	   			}
	   		}) 			
   		}

   	};
  
   	$scope.delItem = function(id,e){
   		e.stopPropagation();
   		if(confirm('确定要删除吗？')){
   			angular.forEach($scope.items,function(m,i){
   				if(m.id == id){
   					$scope.items.splice(i,1);
   					$scope.focus.id = '';
   					return false;
   				}
   			})
   		}
   		return false;
   	};

   	$scope.hex2rbg = function(item){
   		var res = $scope.hexToRgba( item.mengcbg );
   		item.mcbg = res;
   	}

	$scope.hexToRgba = function (hex) {
		return {
			a : parseInt("0x" + hex.slice(1, 3)),
			b : parseInt("0x" + hex.slice(3, 5)),
			c : parseInt("0x" + hex.slice(5, 7)),
		};
	}


}])
.directive('viewDelete',function(){
	return {
		restrict : 'A',
		link : function(scope,elem,attr){
			$(elem).on('mouseover',function(e){
				$(elem).next().show();
				e.stopPropagation();
				e.preventDefault();
			})
			$(elem).on('mouseleave',function(e){
				$(elem).next().hide();
				e.stopPropagation();
				e.preventDefault();
			})			
		}
	}
})
.directive('addModule',function($timeout){
	return {
		restrict : 'A',
		link : function(scope,elem,attr){
			$(elem).on('click',function(){

				if( (scope.items.length > 0 && attr.name == 'webview') || ( scope.items[0] && scope.items[0].name == 'webview') ){
					webAlert('网页容器只能有一个，而且不能与其他元素同时存在');return false;
				}

				angular.forEach(scope.modules,function(m){
					if(m.name == attr.name){		
						var mid = 'm'+new Date().getTime();
						var tempobj = $.extend(true,{},m);
						
						var newitem = {id:mid,name:tempobj.name,params:tempobj.params};
						var index = -1;
						angular.forEach(scope.items,function(m,i){
							if(m.id == scope.focus.id){
								index = i;
								return false;
							}
						})
						scope.items.splice(index+1,0,newitem);
							
						scope.$apply();
						$('div[viewid='+mid+']').trigger('click');

						return false;
					}
				})
			})
		}
	}
})
.directive('stringHtml' , function(){
    return function(scope , el , attr){
        if(attr.stringHtml){
            scope.$watch(attr.stringHtml , function(html){
                el.html(html || '');
            });
        }
    };
})
.directive('mySlider',function(){
	return {
		restrict : 'A',
		link : function(scope,elem,attr){
			require(['jquery.ui'],function(){	
					$(elem).slider({
						min: parseInt( attr.min, 10 ),
						max: parseInt( attr.max, 10 ),
						value : parseInt( attr.value, 10 ) ? parseInt( attr.value, 10 ) : 0,
						slide : function(event,ui){
							
							if(attr.type == 2){
								scope.image[attr.name] = ui.value;
							}else if(attr.type == 3){
								scope.$parent.basic.pbot = ui.value;
							}else if(attr.type == 'bar'){
								scope.bar[attr.name] = ui.value; 
							}else{
								scope.item.params[attr.name] = ui.value;	
							}
							scope.$apply();	
						}
					});
			});
		}
	}
})
.directive("myDatePicker", function() {
        var a = {
            transclude: !0,
            template: "<span ng-transclude></span>",
            scope: {
                dateValue: "=myDateValue"
            },
            link: function(a, c, d) {	
                var e = {
                    lang: "zh",
                    step: "30",
                    format: "Y-m-d H:i:s",
                    closeOnDateSelect: !0,
                    onSelectDate: function(b, c) {
                        a.dateValue = b.dateFormat("Y-m-d H:i:s"), a.$apply("dateValue")
                    },
                    onSelectTime: function(b, c) {
                        a.dateValue = b.dateFormat("Y-m-d H:i:s"), a.$apply("dateValue")
                    }
                };
                require(['datetimepicker'], function() {
                	$(c).datetimepicker(e);
                });
            }
        };
    	return a
})
.directive('timeDesc',function($timeout){
	return {
		restrict : 'A',
		link :function(scope,elem,attr){
			setInterval(function(){
					var date = new Date();
					var time = date.getTime();  //当前时间距1970年1月1日之间的毫秒数 
					var timestr = attr.time;
					var endTime = timestr.replace(/-/g,'/'); 
					var endTime = new Date(endTime).getTime(); //结束时间字符串	
					var lag = (endTime - time); //当前时间和结束时间之间的秒数	
					if(lag > 0){
						var second = Math.floor(lag/1000%60);     
						var minite = Math.floor(lag/1000/60%60);
						var hour = Math.floor(lag/1000/60/60%24);
						var day = Math.floor(lag/1000/60/60/24);
						second = second.toString().length == 2 ? second : '0'+second;
						minite = minite.toString().length == 2 ? minite : '0'+minite;
						hour = hour.toString().length == 2 ? hour : '0'+hour;
						day = day.toString().length == 2 ? day : '0'+day;
					}else{
						var second = '00';
						var minite = '00';
						var hour = '00';
						var day = '00';
					}
					$(elem).find('.day').text(day);
					$(elem).find('.hour').text(hour);
					$(elem).find('.minite').text(minite);
					$(elem).find('.second').text(second);
			},1000)	
		}
	}
})
.directive('onSelected', function ($timeout) {
    return {
        restrict: 'A',
        scope : {
        	style : '=ngModel'
        },
        link: function (scope, elem, attr) {
        	
    		var v = scope.style ? scope.style : 0;
            if(v == $(elem).val()){
            	$(elem).parent().addClass('selected');
            }

        }
    }
})
.directive('onSelectSort', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, elem, attr) {
        	if( scope.$last ){
        		//console.log( attr )	
        	}
           	
        }
    }
})
.directive('onFinishRender',['$timeout', '$parse', function ($timeout, $parse) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
        	

            if (scope.$last === true) {
        		
            	$('.dsadasda').each(function(){
            		var _this = $(this);
            		console.log( _this.attr('model') );
            		if( _this.attr('model') == _this.attr('value') ){

            			_this.parent().addClass('selected');
            		}
            	});
            }
        }
    }
}])
.directive('squareImage',function(){
    return {
        restrict: 'A',
        link: function (scope, elem, attr) {
        	var img = $(elem).find('img');
        	img.height(img.width());
        }
    }
});
