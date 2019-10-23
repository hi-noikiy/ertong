$(function(){

	
	common();
	
	$('.showdown').click(function(){
		var status = $(this).attr('status');
		var classs = $(this).attr('cid');
		if( status == 0 ) {
			$(this).attr('status',1);
			$(this).text('查看下级分类');
			$(classs).hide();
		}else{
			$(this).attr('status',0);
			$(this).text('收起');
			$(classs).show();
		}
		
	});
	
	// 保存卡券
	$('#savecard').click(function(){

		var form = $('form[id=add]').serializeArray();
		for (var i = 0; i < form.length; i++) {
			var name = form[i].name;
			if( name == 'name' && form[i].value == '' ){
				webAlert('请填写名称');return false;
			}
			if( name == 'value' &&  form[i].value*1 <= 0 ){
				webAlert('面值必须大于0');return false;
			}
			if( name == 'value' &&  $('input[name="useleast"]').val()*1 <= form[i].value*1 && $('input[name="type"]:checked').val() == 0 ){
				webAlert('使用条件必须大于面值');return false;
			}			
			if( name == 'type' && $('input[name="value"]').val()*1 >= 10 && $('input[name="type"]:checked').val() == 1 ){
				webAlert('卡券面值应在0-10之间');return false;
			}		
			if( name == 'type' && $('input[name="value"]').val()*1 <= 0 && $('input[name="type"]:checked').val() == 1 ){
				webAlert('卡券面值应在0-10之间');return false;
			}			
		}
		Http('post','json','savecard',form,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);

	});
	
	$('.deleteform').click(function(){
		var type = $(this).attr('type');
		if( confirm('确定删除吗？') ){
			Http('post','json','deleteform',{type:type},function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});

	//预约
	$('.add_appoint').click(function(){
		var type = $(this).attr('type');
		var name = $(this).text();
		var id = 'i' + $('.appoint_box_item').length + new Date().getTime();

		var se = '<div class="appoint_additem">'
				+'</div>'
				+'<div class="appoint_additem">'
					+'<span class="frm_input_box frm_input_150">'
						+'<input type="text" class="frm_input"  name="editvalue" >'
					+'</span>'
					+'<a href="javascript:;" class="add_appoint_item">添加选项</a>'
				+'</div>';

		if( type != 'single' && type != 'multi' ) {
			se = '';
		}

		var typestr = ' 提示文字 <span class="frm_input_box frm_input_150">'
						+'<input type="text" class="frm_input"  name="pla['+id+']" >'
					+'</span>	<span class="font_mini">'+name+'</span>'
					+'<a href="javascript:;" class="delete_appoint_item">删除</a>	';
		if( type == 'img' ) {
			typestr = ' 最多图片 <span class="frm_input_box frm_input_150">'
						+'<input type="text" class="frm_input"  name="maxnum['+id+']" >'
					+'</span>	<span class="font_mini">'+name+'</span>'
					+'<a href="javascript:;" class="delete_appoint_item">删除</a>	';			
		}			


		var str = '<div class="good_rule_item appoint_box_item" ng-repeat="item in rule">'
						+'<input type="hidden" name="aid[]" value="'+id+'">'
						+'<input type="hidden" name="type['+id+']" value="'+type+'">'
						+'<div class="appoint_form_item">'
							+'<div>'
								+'名称<span class="frm_input_box frm_input_150">'
									+'<input type="text" class="frm_input"  name="name['+id+']" >'
								+'</span>'
								+typestr
							+'</div>'+se
						+'</div>'
					+'</div>';
		$('.appoint_list').append(str);

	});


	$('body').on('click','.add_appoint_item',function(){
		var item = $(this).prev().find('input').val();
		var id = $(this).parents('.appoint_box_item').find('input[name="aid[]"]').val();
		if( item == '' ) return false;
		$(this).parent().prev().append( '<span class="appoint_additem_item">'+item+'<input type="hidden" name="sitem['+id+'][]" value="'+item+'"></span>' );
		$(this).prev().find('input').val('');
	});

	$('body').on('click','.appoint_additem_item',function(){
		$(this).remove();
	});

	$('body').on('click','.delete_appoint_item',function(){
		$(this).parents('.appoint_box_item').remove();
	});
	

	$('body').on('mouseover','.temp_item_thumb',function(){
		$('.temp_page_settemp').hide();
		$(this).find('.temp_page_settemp').show();
	});

	$('body').on('mouseleave','.temp_item_thumb',function(){
		$('.temp_page_settemp').hide();
	});	

	// 预约时间
	$('.add_appointtime').click(function(){
		var html = '<div class="appointtime_item">'
						+'<span class="frm_input_box frm_input_box_100">'
							+'<input type="text" class="frm_input"  name="ltimesh[]" value="">'
						+'</span>点<span class="frm_input_box frm_input_box_100">'
							+'<input type="text" class="frm_input"  name="ltimesm[]" value="">'
						+'</span>分 - '
						+'<span class="frm_input_box frm_input_box_100">'
							+'<input type="text" class="frm_input"  name="ltimeh[]" value="">'
						+'</span>点<span class="frm_input_box frm_input_box_100">'
							+'<input type="text" class="frm_input"  name="ltimeem[]" value="">'
						+'</span>分 <a href="javascript:;" class="delete_appointtime">删除</a>'
					+'</div>';
		$('.appointtime_list').append(html);
	});
	$('body').on('click','.delete_appointtime',function(){
		$(this).parents('.appointtime_item').remove();
	});

	// 测试打印
	$('#testprint').click(function(){
		var postdata={
			plug : $(this).attr('plug'),
		};
		Http('post','json','testprint',postdata,function(data){
			alert( data.res );
		},true);
	});

	// 设为首页
	$('body').on('click','.toindex',function(){
		var postdata={
			pid : $(this).attr('pid'),
		};
		Http('post','json','pagetoindex',postdata,function(data){
			webAlert(data.res);
			if( data.status == 200 ) {
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});

	// 模板云
	$('#tempcloud').click(function(){
		if( $('.cloudbox_in').html() == '' ) {
			var postdata = {
				type : 'login',
			};
			Http('post','json','tempcloud',{data:postdata},function(data){
				if( data.status == 200 ) {
					$('.cloudbox').show();
					if( data.obj && data.obj.html ) $('.cloudbox_in').html(data.obj.html);
				}else{
					webAlert(data.res);
				}
			},true);
		}else{
			$('.cloudbox').show();
		}
	});
	$('.closecloud').click(function(){
		$('.cloudbox').hide();
	});

	// 模板云
	$('#tomycloud').click(function(){
		Http('post','json','tomycloud',{},function(data){
			webAlert(data.res);
		},true);
	});

	// 至云端
	window.tocloudtid = 0; 
	$('.tocloud').click(function(){
		window.tocloudtid = $(this).attr('id');
		$('.my_model[tocloud]').show();

	});
	$('#confirm_tocloud').click(function(){
		var postdata = {
			fee : $('input[name="fee"]').val(),
			id : window.tocloudtid,
		};
		Http('post','json','tocloud',postdata,function(data){
			webAlert(data.res);
			if( data.status == 200 ){
				$('input[name="fee"]').val('');
				$('.my_model[tocloud]').hide();
			}
			if( data.status == 210 ) {
				$('.cloudbox').show();
				$('.cloudbox .cloudbox_in').html(data.obj.html);
			}
		},true);
	});

	// 设置模板
	$('#loadmoduletemp').click(function(){
		Http('post','json','loadmoduletemp',{},function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});

	// 删除导航
	$('body').on('click','#deletebar',function(){
		if( confirm('删除后不可恢复，确定删除吗？') ) {
			Http('post','json','deletebar',{id:$(this).attr('tid')},function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});

	// 修改模板分类
	$('.confirm_changetempsort').click(function(){
		var postdata={
			id : $(this).attr('id'),
			target : $(this).parents('.dropdown_data_list').find('select').val(),
		};
		Http('post','json','changetempsort',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});

	// 采集淘宝
	$('#gettb').click(function(){
		var postdata={
			sortid : $('input[name=sortid]').val(),
		};

		var arr = [];
		$('input[name=tburl]').each(function(){
			if( $(this).val() != '' ){
				arr.push( $(this).val() );
			}
		});

		if( arr.length <= 0 ) {
			webAlert('请填写淘宝或天猫商品链接');
			return false;
		}
		postdata.arr = arr;
		
		Http('post','json','gettb',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});

	// 退款
	$('.confirm_refund').click(function(){

		var postdata = {
			'oid' : $(this).attr('oid'),
			'money' : $(this).parents('.dropdown_data_list').find('.drop_down_input').val(),
		}

		Http('post','json','refundorder',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);

	});

	//删除核销员
	$('.deleteadmin').click(function(){
		var id = $(this).attr('id');
		if( confirm('确定删除此核销员吗？') ){
			Http('post','json','deleteadmin',{id:id},function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});	

	// 发货
	$('.confirm_express').click(function(){
		var postdata = {
			'oid' : $(this).attr('oid'),
			'expressname' : $(this).parents('.dropdown_data_list').find('.expressname').val(),
			'expressnum' : $(this).parents('.dropdown_data_list').find('.expressnum').val(),
		}
		if( confirm('确定发货处理吗？') ){
			Http('post','json','sendgood',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});	

	$('.comorder').click(function(){
		var postdata = {
			'oid' : $(this).attr('oid'),
		}
		if( confirm('确定完成订单吗？') ){
			Http('post','json','comorder',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});

	$('.lawyerdo').click(function(){
		var postdata = {
			'oid' : $(this).attr('oid'),
		}
		if( confirm('确定设为履行吗？') ){
			Http('post','json','lawyerdo',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});

	// 处理预约订单
	$('.dealapporder').click(function(){
		var _this = $(this);
		var postdata = {
			oid : $(this).attr('oid'),
			type : $(this).attr('type'),
			money : $(this).parents('.dropdown_data_container').find('input').val(),
		}
		var str = '确定取消吗？';
		if( postdata.type == 'take' ) str = '确定接单吗？';
		if( postdata.type == 'com' ) str = '确定完成吗？';
		if( postdata.type == 'refound' ) str = '确定退款吗？';
		if( postdata.type == 'delete' ) str = '确定删除吗？';

		if( confirm( str ) ){
			Http('post','json','dealapporder',postdata,function(data){
				webAlert(data.res);
				if( data.status == 200 ) _this.parents('tr').remove();
			},true);
		}
	});	


	// 处理卡券
	$('.dealcard').click(function(){
		var _this = $(this);
		var postdata = {
			id : $(this).attr('id'),
			type : $(this).attr('type'),
		}
		var str = '确定操作吗？';
		if( postdata.type == 'com' ) str = '确定核销掉卡券吗？';
		if( postdata.type == 'delete' ) str = '确定删除吗？';

		if( confirm( str ) ){
			Http('post','json','dealcard',postdata,function(data){
				webAlert(data.res);
				if( data.status == 200 ) _this.parents('tr').remove();
			},true);
		}
	});	

	// 控制权限
	$('.auth_change').click(function(){
		var _this = $(this);
		var value = _this.find('input:checked').val();

		var postdata = {
			uid : _this.attr('uid'),
			type : _this.attr('type'),
		}

		if( value ) {
			postdata.status = 0;
		}else{
			postdata.status = 1;
		}
		
		Http('post','json','changeauth',postdata,function(data){
			webAlert(data.res);
		},true);

	});

	// 批量修改权限
	$('.dealauth').click(function(){
		var type = $(this).attr('act');
		var arr = [];
		$('input[name="checkall[]"]:checked').each(function(){
			arr.push( $(this).val() );
		});
		if( arr.length <= 0 ) return false;

		Http('post','json','changeauthall',{type:type,arr:arr},function(data){
			webAlert(data.res);
			if( data.status == 200 ) {
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);

	});


	// 修改地址
	$('.confirm_editaddress').click(function(){
		var postdata = {
			'oid' : $(this).attr('oid'),
			name : $('.addressname').val(),
			tel : $('.addresstel').val(),
			add : $('.addressaddress').val(),
		}
		if( confirm('确定修改吗？') ){
			Http('post','json','editaddress',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}
	});

	// 测试发邮件和短信
	$('.usemail').click(function(){
		var postdata = {
			type : $(this).attr('type'),
			value : $(this).parents('.dropdown_data_list').find('input').val(),
		}
		Http('post','json','usemail',postdata,function(data){
			webAlert(data.res);
		},true);
	});

	// 修复图片
	$('.fixsite').click(function(){
		var postdata = {
			oldsite : $('input[name="oldsite"]').val(),
			newsite : $('input[name="newsite"]').val(),
		}
		Http('post','json','fixsite',postdata,function(data){
			webAlert(data.res);
		},true);
	});

	// 增减发短信数量
	$('.addsms').click(function(){
		var postdata = {
			uid : $(this).attr('uid'),
			value : $(this).parents('.dropdown_data_list').find('input').val(),
		}
		Http('post','json','addsms',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});	

	// 选择坐标
	var ismap = false;
	var lng = lat = 0;
	var actelem = null;
	$('.showmap').click(function(){
		actelem = $(this);
		lng = lat = 0;

        if( !ismap ) {
            ismap = true;
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

                lng = e.latLng.lng;
                lat = e.latLng.lat;             
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
                    console.log( result );
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
	});
    
    $('.setLocation').click(function(){
        if( lng <= 0 || lat <= 0 ) {
            webAlert('请先点击选择坐标');return false;
        }
        var latname = actelem.attr('latname');
        var lngname = actelem.attr('lngname');

        var str = lat + '，'+lng + '<input type="hidden" name="'+latname+'" value="'+lat+'"><input type="hidden" name="'+lngname+'" value="'+lng+'">';
        actelem.prev().html(str);
        $('.my_model[map]').hide();
    });

	// 设置模板
	/*$('.settemp_btn').click(function(){
		var postdata={
			id : $(this).attr('id'),
		};
		Http('post','json','setacttemp',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				setTimeout(function(){
					location.href = "";
				},500);
			}
		},true);
	});*/

	// 导出模板
	window.settemptype = '';
	$('.temptopage,.settemp_btn').click(function(){
		window.settemptype = $(this).attr('type');

		var postdata={
			id : $(this).attr('id'),
			ctype : $(this).attr('ctype'),
		};
		Http('post','json','gettemppage',postdata,function(data){
			if( data.obj && data.obj.length > 0 ) {
				var str = '';
				for (var i = 0; i < data.obj.length; i++) {
					str += '<div class="item_cell_box setlink_r_item">'
	        				+'<div>'+data.obj[i].name+'</div>'
	        				+'<div class="setlink_r_box item_cell_flex " >'
	        					+'<span class="select_index" pageid="'+data.obj[i].id+'" tid="'+postdata.id+'">选择作为主页</span>'
	        				+'</div>'
	        			+'</div>';
				}

				$('.temp_page').html( str );
				$('.my_model[url]').show();
			}else{
				temptopage( postdata );
			}

		},true);
	});

	$('body').on('click','.select_index',function(){
		var postdata={
			id : $(this).attr('tid'),
			pageid : $(this).attr('pageid'),
		};
		temptopage( postdata );
	});

	function temptopage( postdata ){
		if( settemptype == 'use' ){
			Http('post','json','setacttemp',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					setTimeout(function(){
						location.href = "";
					},500);
				}
			},true);
		}else{
			if( confirm('重要：导出后一定要编辑被导出模板的页面中的链接！！！确定要使用模板吗？') ) {
				Http('post','json','temptopage',postdata,function(data){
					alert(data.res);
					$('.my_model[url]').hide();
				},true);
			}
		}
	}

	// 设为系统模板
	$('.tosystem').click(function(){
		var postdata={
			id : $(this).attr('id'),
			sys : $(this).attr('sys'),
			type : $(this).parents('.dropdown_menu').find('select').val(),
		};
		
		if( confirm('设为系统模板后，平台的其他小程序也能导出使用此模板，确定要将此模板设为系统模板吗？') ) {
			Http('post','json','tosystem',postdata,function(data){
				webAlert(data.res);
			},true);
		}
	});

	$('.deletesystem').click(function(){
		var _this = $(this);
		var postdata={
			id : $(this).attr('id'),
			type : $(this).attr('type'),
		};
		if( confirm('确定要删除此模板吗？') ) {
			Http('post','json','deletesystem',postdata,function(data){
				webAlert(data.res);
				if(data.status == 200){
					/*setTimeout(function(){
						location.href = "";
					},500);*/
					_this.parents('.temp_page_item ').remove();
				}
			},true);
		}
	});

	// 添加商品
	$('input[name=create],input[name=save]').click(function(){

		var form = $('form[id=add]').serializeArray();
		for (var i = 0; i < form.length; i++) {
			var name = form[i].name;
			if( name == 'title' && form[i].value == '' ){
				webAlert('请填写标题');return false;
			}
			if( name == 'isselftake' && form[i].value == '1' && ( $('input[name="shoptel"]').val() == '' || $('input[name="shopaddress"]').val() == '' ) ){
				webAlert('请填写门店电话和地址');return false;
			}
			if( name == 'isrule' && form[i].value == '0' && ( $('input[name="price"]').val() == '' || $('input[name="price"]').val()*1 < 0.01 || $('input[name="oldprice"]').val() == '' ) ){
				webAlert('请填写商品原价和现价');return false;
			}

			if( name == 'isrule' && form[i].value == '1' ){
				
				var rule = $('input[name=rule]').val();
				var map = $('input[name=rulemap]').val();
				if( rule == '' || map == '' ){
					webAlert('请设置规格');return false;
				}

				var rule = JSON.parse( rule );
				var map = JSON.parse( map );
				
				if( rule.length <= 0 || map.length <= 0 ){
					webAlert('请设置规格');return false;
				}
				var ispass = true;
				for (var j = 0; j < map.length; j++) {
					
					if( map[j].nowprice*1 < 0.01 ){
						webAlert('请设置规格对应的价格，价格必须大于0.01');
						ispass = false;
						return false;
					}
				}
				if( !ispass ) {
					webAlert('请设置规格对应的价格，价格必须大于0.01');return false;
				}
			}			

		}

	});


	//参数标签
	$('#add_icon').click(function(){
		var html = '<div class="edit_right_item">'
					+'名称<span class="frm_input_box frm_input_box_150">'
						+'<input type="text" class="frm_input"  name="iconname[]" value="">'
					+'</span>属性<span class="frm_input_box frm_input_box_300">'
						+'<input type="text" class="frm_input"  name="icondesc[]" value="">'
					+'</span>'
					+'<a href="javascript:;" class="delete_params"> 删除</a>'
				+'</div>';
		$('.group_icon_box').append(html);
	});

	//淘宝url
	$('#add_tb').click(function(){
		var html = '<div class="edit_right_item">'
					+'链接<span class="frm_input_box frm_input_box_500">'
						+'<input type="text" class="frm_input"  name="tburl" value="">'
					+'</span>'
					+'<a href="javascript:;" class="delete_params"> 删除</a>'
				+'</div>';
		$('.group_tb_box').append(html);
	});

	// 添加输入栏
	$('.addinput').click(function(){
		$(this).hide();
		$(this).prev().show();
	});

	$('#confirm_addiplimit').click(function(){
		var name = $('#iplimit_name').val();
		if( name == '' ) return false;
		var str = '<label class="frm_checkbox_label selected" > '
		     			+'<i class="icon_checkbox"></i> '
		     			+'<span class="lbl_content">'+name+'</span> '
		     			+'<input type="checkbox" class="frm_checkbox" checked value="'+name+'" name="iplimit[]" /> '
		     		+'</label>';
		$('.iplimit_list').append(str);
		$('#iplimit_name').val('');
	});

	
	$('#confirm_addaddsite').click(function(){
		var name = $('#addsite_name').val();
		if( name == '' ) return false;
		var str = '<label class="frm_checkbox_label selected" > '
		     			+'<i class="icon_checkbox"></i> '
		     			+'<span class="lbl_content">'+name+'</span> '
		     			+'<input type="checkbox" class="frm_checkbox" checked value="'+name+'" name="sitearr[]" /> '
		     		+'</label>';
		$('.isite_list').append(str);
		$('#addsite_name').val('');
	});


	// 设为已读
	$('.toreaded').click(function(){
		var thisclass = $(this);
		var postdata = {
			fid: thisclass.attr('fid')
		};

		Http('post','json','toreaded',postdata,function(data){
			webAlert(data.res);
			if(data.status == 200){
				thisclass.parents('tr').remove();
			}
		},true);
	});



	// 删除价格
	$('body').on('click','.delete_price',function(){
		$(this).parents('.edit_right_item').remove();
		if( $('.group_price_box .edit_right_item').length > 1 ){
			$('.group_price_box .edit_right_item').last().append(' <div class="btn btn_warn btn_mini delete_price"> 删除</div>');
		}
	});
	
	if( $( ".multi-img-details" ).length > 0 ){
		// 拖拽
		$( ".multi-img-details" ).sortable();
		$( ".multi-img-details" ).disableSelection();	
	}



	// 删除属性
	$('body').on('click','.delete_params',function(){
		$(this).parents('.edit_right_item').remove();
	});

	//标签
	/*$('#add_icon').click(function(){
		var html = ' <span class="frm_input_box frm_input_box_100" >'
						+'<font class="delete_icon delete_c">x</font>'
						+'<input type="text" class="frm_input"  name="kakey[]" value="">'
					+'</span>';
		$(this).next().append(html);
	});*/

	/*$('body').on('click','.delete_icon',function(){
		$(this).parent().remove();		
	});*/
	/*$('body').on('click','.delete_icona',function(){
		$(this).parents('.edit_right_item').remove();		
	});*/



	$('body').on('click','.add_rule_item',function(){
		var num = $(this).attr('data-no');
		var html = '<span class="frm_input_box frm_input_box_100" >'
						+'<input type="text" class="frm_input" name="rulepro['+num+'][]" value="">'
					+'</span>';
		$(html).insertBefore($(this));
	});

	$('body').on('dblclick','.rule_list .frm_input',function(){
		$(this).parent().remove();
	});


	// 选择区域
	$('body').on('click','#js_selectarea_opt a',function(){
		var  obj = new areaSelect($(this).next());
		obj.init();
		$('.ui-draggable').show();
	});





	// 复制链接
	require(['/web/resource/components/zclip/jquery.zclip.min.js'], function(){
		$('.copy_url').zclip({
			path: './resource/components/zclip/ZeroClipboard.swf',
			copy: function(){
				return $(this).attr('data-href');
			},
			afterCopy: function(){
				webAlert('复制成功');
			}
		});
	});

	// 清理缓存
	$('.deletecache').click(function(){
		var type = $(this).attr('type');
		if(confirm('确定删除吗？')){
			Http('post','html','deletecache',{type:type},function(data){
				if(data == 1){
					webAlert('已删除');
				}else{
					webAlert('删除失败');
				}
			},true);
		}
	});


	// 导入数据 改变显示文字
	$('input[name=inputfile]').change(function(){
		if( confirm('确定导入吗？') ){
			loading();
			$('input[name="import"]').click();
		}
	});


	// 编辑排序
	var nowvalue;
	$('.edit_number_input').focus(function(){
		$(this).addClass('edit_number_input_act');
		nowvalue = $(this).val();
	});
	$('.edit_number_input').blur(function(){
		$(this).removeClass('edit_number_input_act');
		if(nowvalue == $(this).val()) return false;
		var data = {
			type : $(this).attr('inputtype'),
			value : $(this).val(),
			name : $(this).attr('inputname'),
			id : $(this).attr('id')
		};
		Http('post','html','editvalue',data,function(data){},true);
	});


	// 搜索
	$('.js_search').click(function(){
		$(this).parents('form').submit();
	});

	// 拉黑和恢复
	$('.edit_user').click(function(){
		var data = {
			type : $(this).attr('type'),
			id : $(this).attr('id')
		};
		if(confirm('确定执行操作吗？')){
			Http('post','html','edituser',data,function(data){
				if(data == 1){
					alert('操作完成');
					location.href = "";
				}else{
					alert('操作失败');
				}
			},true);
		}
	});


	

	// 切换参数设置
	$('.js_top').click(function(){
		$('.js_top').removeClass('selected');
		var thisclass = $(this).attr('showme');
		$(this).addClass('selected');
		$('.settings_group').each(function(){
			if($(this).hasClass(thisclass)){
				$(this).show();
			}else{
				$(this).hide();
			}
		})
	})


})
	
	function common(){
		
		$('.topbar_jsbtn').on('click',function(){
			var type = $(this).attr('js');
			$('.my_model['+type+']').show();
		});

		$('body').on('mouseover','.show_good_qrcode',function(){
			$('.show_good_qrcode img').hide();
			$(this).next().show();
		});

		$('body').on('mouseleave','.show_good_qrcode',function(){
			$('.good_qrcode_box img').hide();
		});		

		$('body').on('click','.hide_item',function(){
			var elem = $(this).attr('hideitem');
			if( elem ){
				var arr = elem.split(",");
				for (var i = 0; i < arr.length; i++) {
					$(arr[i]).hide();
				}
			}
		});
		$('body').on('click','.show_item',function(){
			var elem = $(this).attr('showitem');
			if( elem ){
				var arr = elem.split(",");
				for (var i = 0; i < arr.length; i++) {
					$(arr[i]).show();
				}
			}
		});
		//
		$('.model_close').click(function(){
			$(this).parents('.my_model').hide();
		});

		//下拉选择
		$('body').on('click','.radio_list_item',function(){
			var txt = $(this).text();
			$(this).find('input').prop('checked',true);
			$(this).parents('.dropdown_menu').find('.jsBtLabel').text(txt).end().addClass('open');

		});
		$('.radio_list_input:checked').each(function(){
			var txt = $(this).parent().text();
			$(this).parents('.dropdown_menu').find('.jsBtLabel').text(txt);
		});

		//点击相应位置隐藏筛选/下拉
		$('body').on('click',function(e) {
			if($(e.target).parents('.dropdown_topbar').length <= 0){
				$('.dropdown_menu').each(function(){
					var $this = $(this);
					if($this.hasClass('open')) $this.removeClass('open');
				})
			}
		});	

		// 切换参数设置
		$('.js_top').click(function(){
			$('.js_top').removeClass('selected');
			var thisclass = $(this).attr('showme');
			$(this).addClass('selected');
			$('.settings_group').each(function(){
				if($(this).hasClass(thisclass)){
					$(this).show();
				}else{
					$(this).hide();
				}
			})
		});

		// table内编辑框
		$('.drop_down_editbtn').click(function(){
			$('.jsDropdownsList').hide();
			
			$(this).parents('.opclass').eq(0).find('.jsDropdownsList').toggle();
		});
		$('body').on('click','.dropdown_edit_cancel',function(){
			$(this).parents('.jsDropdownsList').hide();
		});

		// 自动选择单选框
		$('.frm_radio').each(function(){
			var $this = $(this);
			if($this.attr('checked')){
				$this.parents('.frm_radio_label').addClass('selected');
			}
		});
		$('.frm_checkbox').each(function(){
			var $this = $(this);
			if($this.attr('checked')){
				$this.parents('.frm_checkbox_label').addClass('selected');
			}
		});		

		// checkbox
		$('body').on('click','.frm_radio_label',function(e){
			e.preventDefault();
			if( $(this).hasClass('disabled') ) return false;
			var $this = $(this);
			var name = $this.find('input[type=radio]').prop('name');
			
			$('input[name='+name+']').each(function(){
				$(this).prop('checked',false);
				$(this).parents('.frm_radio_label').removeClass('selected');
			})
			$this.addClass('selected').find('input').prop('checked',true);
		});


		// 复选框 包括全选
		$('body').on('click','.frm_checkbox_label',function(e){
			var checkbox = $(this).find('input[type=checkbox]');
			if( $(this).hasClass('disabled') ) return false;
			
			var isall = $(this).prop('for') == 'selectAll';
			if( checkbox.prop("checked") ){
				checkbox.prop("checked",false);
				$(this).removeClass('selected');
				if(isall){

					$('.tbody input[type=checkbox][name="checkall[]"]').each(function(){
						$(this).parents('.frm_checkbox_label').removeClass('selected');
						$(this).prop("checked",false);
					})
				}
			}else{
				checkbox.prop("checked",true);
				$(this).addClass('selected');
				if(isall){
					$('.tbody input[type=checkbox][name="checkall[]"]').each(function(){					
						$(this).parents('.frm_checkbox_label').addClass('selected');
						$(this).prop("checked",true);
					})
				}
			}
			e.preventDefault();
		});


		// 下拉
		$('body').on('click','.dropdown_menu',function(e){
			var $this = nowdropdown = $(this);
			if($this.hasClass('open')){
				$this.removeClass('open');
			}else{
				$this.addClass('open');
				$('.dropdown_menu').not(this).each(function(){
					if( $(this).hasClass('open') ) $(this).removeClass('open');
				})
			}
		});

		// 切换
		$('body').on('click','.change_item .frm_radio_label',function(){
			var item = $(this).parents('.change_item').attr('item');
			$('.'+item).hide();
			var show = $(this).attr('show');
			$('.'+show).show();		
		});
		
		// select
		$('body').on('click','.select_item',function(){
			var id = $(this).attr('id');
			var text = $(this).text();
			var parent = $(this).parents('.dropdown_menu');
			parent.find('input').val(id);
			parent.find('.jsBtLabel').text(text);
		})

	};


	function webAlert(str){
		if($('#webalert').length > 0){
			$('#webalert .alertcontent').text(str);
			alertShow();
		}else{
			var div = '<div id="webalert" style="position:fixed;z-index:99999;top:20px;left:50%;padding:5px;width:500px;margin-left:-250px;background:red;">\
					<div class="alertcontent" style="font-size: 16px;color:#fff;text-align:center;">'+str+'</div></div>';
			$('body').append(div);
			alertShow();
		}
	};

	function alertShow() {
		$('#webalert').show('fast',function(){
			setTimeout(function(){$('#webalert').hide();},3000);
		})
	};

	//http请求
	 function Http(type,datatype,op,data,successCall,isloading,beforeCall,comCall){
		$.ajax({
			type: type,
			url: ajaxUrl(op),
			dataType: datatype,
			data : data,
			beforeSend:function(){
				if(beforeCall) beforeCall();
				if(isloading) loading();
			},
			success: function(data){
				if(successCall) successCall(data);
			},
			complete:function(){
				if(comCall) comCall();
				if(isloading) loaded();
			},				
			error: function(xhr, type){
				console.log(xhr);
			}
		});	
	};

	function ajaxUrl(op){
		return window.sysinfo.siteroot + 'web/index.php?c=site&a=entry&op='+op+'&do=ajax&m=zofui_sitetemp&version_id='+version_id;
	};
	
	function loading(){
		var html = 
			'<div id="loading" class="loading" style="z-index:52111;position:relative">'+
			'<div class="load_mask"></div>'+
			'<div class="modal-loading">'+
			'	<div class="modal-loading-in">'+
			'		<img style="width:48px; height:48px;" src="../attachment/images/global/loading.gif"><p>处理中，勿关闭本页</p>'+
			'	</div>'+
			'</div>'+
			'</div>';
		$(document.body).append(html);
	};
	
	function loaded(){
		$('.loading').remove();
	};

	function serializeJson( elem ){
		
		var serializeArr = elem.serializeArray();
		var postdata={};
		for (i in serializeArr) {
			postdata[ serializeArr[i].name ] = serializeArr[i].value;
		}
		return postdata;
	}

	function arrval( elem ){
        var self = elem;
        var result = [];
        if(self.length > 0){
            self.each(function(i, o){
           	 	result.push($(o).val());
			});
        }
        return result;
	}

	/***地区选择***/
	function areaSelect (element) {
		//if(typeof areaSelect.areaobj === 'object') return areaSelect.areaobj;
		this.elem = element;
		//areaSelect.areaobj = this;
		this.bindEvent();
	};

	areaSelect.prototype.init = function(){
		var self = this;
		if ($('.delivery_privince .js_dd_list').html() == ''){		
			var province = '';
			for(var i=0;i<citydata.length;i++){
				province += '<dd>'
								+'<a href="javascript:;" class="jsLevel father_menu jsLevel1" data-name="'+citydata[i].text+'">'
									+'<span class="item_name">'+citydata[i].text+'</span>'
								+'</a>'
							+'</dd>';
			}
			$('.delivery_privince .js_dd_list').html(province);
		}
		
		var provincevalue = self.elem.find('.area_province_input').val();

		if(provincevalue != ''){
			var cityvalue = self.elem.find('.area_city_input').val();
			var countyvalue = self.elem.find('.area_county_input').val();
			
			$('.jsLevel1').each(function(){	
				if($(this).attr('data-name') == provincevalue) $(this).addClass('selected');
			});
			
			self.appendCityStr(provincevalue,cityvalue); //城市

			countyvalue = countyvalue.replace(/,$/,'');
			countyarray = countyvalue.split(","); //数组
			
			self.appendCountyStr(provincevalue,cityvalue,countyarray); //区县
		}else{
			$('.delivery_city .js_dd_list').empty();
			$('.delivery_county .js_dd_list').empty();
		}

	};

	areaSelect.prototype.bindEvent = function(){
		var self = this;
		//点击一级展开二级
		$('body').off('click','.delivery_box .jsLevel1').on('click','.delivery_box .jsLevel1',function(){
			var province = $(this).attr('data-name');
			$('.delivery_privince .selected').removeClass('selected');
			$(this).addClass('selected');
			self.appendCityStr(province,'');
		});		
		//点击二级展开三级
		$('body').off('click','.delivery_box .jsLevel2').on('click','.delivery_box .jsLevel2',function(){
			var province = $(this).attr('data-province'),
				city = $(this).attr('data-name');
			$('.delivery_city .selected').removeClass('selected');
			$(this).addClass('selected');
			self.appendCountyStr(province,city,[]);
		});	

		//选择区县
		$('body').off('click','.delivery_box .jsLevel3').on('click','.delivery_box .jsLevel3',function(){

			if($(this).hasClass('disabled')) return false;
			/*var province = $(this).attr('data-province'),
				city = $(this).attr('data-city'),
				county = $(this).attr('data-name');*/

			if($(this).hasClass('selected')){
				$(this).removeClass('selected');
			}else{
				$(this).addClass('selected');
			}
		});	
	
		//确定选择
		$('#confirm_indelivery').off('click').on('click',function(){

			var province = '',
				city = '',
				county = '';
			$('.delivery_county .selected').each(function(){
				province = $(this).attr('data-province');
				city = $(this).attr('data-city');
				county += $(this).attr('data-name') + ',';
			});
		
			self.elem.find('.area_province_input').val(province).next().val(city).next().val(county);
			self.elem.find('.delivery_item_province').text(province).next().text(city).next().text(county);
			self.hideDeliveryTable();
		});
		
		
		//关闭操作框
		$('.delivery_close').off('click').on('click',function(){
			self.hideDeliveryTable();
		});			
		
	};
	
	//插入城市数据
	areaSelect.prototype.appendCityStr = function (province,city){
		
		for(var i=0;i<citydata.length;i++){
			if(citydata[i].text == province){
				var citystr = '';
				for(var j=0;j<citydata[i].children.length;j++){
					var selectstr = '';
					if(city == citydata[i].children[j].text) selectstr = 'selected';
					citystr += '<dd>'
									+'<a href="javascript:;" class="jsLevel father_menu jsLevel2 '+selectstr+'" data-province="'+province+'" data-name="'+citydata[i].children[j].text+'">'
										+'<span class="item_name">'+citydata[i].children[j].text+'</span>'
									+'</a>'
								+'</dd>';
					
				}
				$('.delivery_city .js_dd_list').html(citystr);
				$('.delivery_county .js_dd_list').empty();
				return false;
			}
		}		
		
	};
	
	areaSelect.prototype.appendCountyStr = function (province,city,countyarray){
		//已经选择了的地区，
		var selectedcountystr = '';
		$('.area_county_input').each(function(){
			selectedcountystr += $(this).val() + ',';
		});
		selectedcountystr = selectedcountystr.replace(/,$/,'');
		selectedcountystr = selectedcountystr.split(","); //数组
		
		for(var i=0;i<citydata.length;i++){
			if(citydata[i].text == province){
				for(var j=0;j<citydata[i].children.length;j++){			
					if(citydata[i].children[j].text == city){
						var county = '';
						for(var k=0;k<citydata[i].children[j].children.length;k++){
							if($.inArray(citydata[i].children[j].children[k], countyarray) >= 0  || $.inArray(citydata[i].children[j].children[k], selectedcountystr) < 0 ){
								var selectedstr = '';
								if( $.inArray(citydata[i].children[j].children[k], countyarray) >= 0 ) selectedstr = 'selected';
								
								county += '<dd>'
												+'<a href="javascript:;" class="jsLevel father_menu jsLevel3 '+selectedstr+'" data-province="'+province+'" data-city="'+city+'" data-name="'+citydata[i].children[j].children[k]+'">'
													+'<span class="item_name">'+citydata[i].children[j].children[k]+'</span>'
												+'</a>'
											+'</dd>';								
							}
						}
						
						$('.delivery_county .js_dd_list').html(county);
						return false;						
					}
				}
			}
		}
	};
	
	areaSelect.prototype.hideDeliveryTable = function (){
		$('.delivery_box .selected').removeClass('selected');
		$('.ui-draggable').hide();
		self = null;
	};
