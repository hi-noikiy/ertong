<?php defined('IN_IA') or exit('Access Denied');?>
<div class="form-group">
	
		<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>所属城市</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="cityid" name="cityid">
				<option value="0">请选择所属城市</option>
					<?php  if(is_array($citylist)) { foreach($citylist as $vo) { ?>
				<option value="<?php  echo $vo['id'];?>" <?php  if($item['cityid'] == $vo['id']) { ?> selected <?php  } ?>><?php  echo $vo['name'];?></option>
				<?php  } } ?>
				
				
			</select>
	</div>
</div>

<div class="form-group">
	
		<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>所属地区</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="houseareaid" name="houseareaid">
				<option value="0">请选择所属地区</option>
					<?php  if(is_array($arealist)) { foreach($arealist as $vo) { ?>
				<option value="<?php  echo $vo['id'];?>" <?php  if($item['houseareaid'] == $vo['id']) { ?> selected <?php  } ?>><?php  echo $vo['name'];?></option>
				<?php  } } ?>
				
				
			</select>
	</div>
</div>

<div class="form-group">
	
		<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>所属片区</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="bid" name="bid">
				<option value="0">请选择所属片区</option>
					<?php  if(is_array($buildarealist)) { foreach($buildarealist as $vo) { ?>
				<option value="<?php  echo $vo['id'];?>" <?php  if($item['bid'] == $vo['id']) { ?> selected <?php  } ?>><?php  echo $vo['name'];?></option>
				<?php  } } ?>
				
				
			</select>
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>楼盘名称</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housename" id="housename" class="form-control" value="<?php  echo $item['housename'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>开发商</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="companyname" id="companyname" class="form-control" value="<?php  echo $item['companyname'];?>" />
	</div>
</div>


<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>楼盘类型</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="housetype" name="housetype">
				<option value="0">请选择楼盘类型</option>
				<option value="1" <?php  if($item['housetype'] == 1) { ?> selected<?php  } ?>>住宅</option>
				<option value="2" <?php  if($item['housetype'] == 2) { ?> selected <?php  } ?>>别墅</option>
				<option value="3" <?php  if($item['housetype'] == 3) { ?> selected <?php  } ?>>公寓</option>
				<option value="4" <?php  if($item['housetype'] == 4) { ?> selected <?php  } ?>>商铺</option>
				<option value="5" <?php  if($item['housetype'] == 5) { ?> selected <?php  } ?>>写字楼</option>
				<option value="6" <?php  if($item['housetype'] == 6) { ?> selected <?php  } ?>>酒店</option>
				<option value="7" <?php  if($item['housetype'] == 7) { ?> selected <?php  } ?>>厂房</option>
				
			</select>
	</div>
</div>




<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>楼盘均价</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="houseprice" id="houseprice" class="form-control" value="<?php  echo $item['houseprice'];?>" />
	</div>
</div>


<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>佣金</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="fxmoney" id="fxmoney" class="form-control" value="<?php  echo $item['fxmoney'];?>" />
	</div>
</div>


<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>售楼电话</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="tel" id="tel" class="form-control" value="<?php  echo $item['tel'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>项目地址</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="houseaddress" id="houseaddress" class="form-control" value="<?php  echo $item['houseaddress'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>售楼地址</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housesaleaddress" id="housesaleaddress" class="form-control" value="<?php  echo $item['housesaleaddress'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>容积率</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="houserate" id="houserate" class="form-control" value="<?php  echo $item['houserate'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>绿化率</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housegreenrate" id="housegreenrate" class="form-control" value="<?php  echo $item['housegreenrate'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>占地面积</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housecovered" id="housecovered" class="form-control" value="<?php  echo $item['housecovered'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>建筑面积</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="buildarea" id="buildarea" class="form-control" value="<?php  echo $item['buildarea'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>开盘时间</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="opensaletime" id="opensaletime" class="form-control" value="<?php  echo $item['opensaletime'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>入驻时间</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="staytime" id="staytime" class="form-control" value="<?php  echo $item['staytime'];?>" />
	</div>
</div>

<div class="form-group">

		<label class="col-xs-12 col-sm-3 col-md-2 control-label">项目特色</label>
					<div class="col-sm-9 col-xs-12">
			<label class='radio-inline'><input <?php  if($speciallist ) { ?> <?php  if(in_array('学区房',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="学区房" />学区房</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?>  <?php  if(in_array('宜居生态地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="宜居生态地产" />宜居生态地产</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?>  <?php  if(in_array('国际化社区',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="国际化社区" />国际化社区</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?>  <?php  if(in_array('特色别墅',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="特色别墅" />特色别墅</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?>  <?php  if(in_array('豪华居住区',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="豪华居住区" />豪华居住区</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?>  <?php  if(in_array('景观居所',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="景观居所" />景观居所</label>
<label class='radio-inline'><input  <?php  if($speciallist ) { ?>  <?php  if(in_array('科技住宅',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="科技住宅" />科技住宅</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?> <?php  if(in_array('小户型',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="小户型" />小户型</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?> <?php  if(in_array('品牌地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="品牌地产" />品牌地产</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?> <?php  if(in_array('地铁沿线',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="地铁沿线" />地铁沿线</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('公园地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="公园地产" />公园地产</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('复合地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="复合地产" />复合地产</label>
<label class='radio-inline'><input  <?php  if($speciallist ) { ?> <?php  if(in_array('养老地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="养老地产" />养老地产</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?> <?php  if(in_array('南北通透',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="南北通透" />南北通透</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('赠送面积',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="赠送面积" />赠送面积</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('湖景地产',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="productspecial[]" value="湖景地产" />湖景地产</label>
					</div>


	
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>所属学区</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="houseschool" id="houseschool" class="form-control" value="<?php  echo $item['houseschool'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>附近公交</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housebus" id="housebus" class="form-control" value="<?php  echo $item['housebus'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>楼盘状态</label>
	<div class="col-sm-9 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="housestatus" name="housestatus" >
				<option value="0">请选择楼盘状态</option>
				<option value="1" <?php  if($item['housestatus'] == 1) { ?> selected<?php  } ?>>在售</option>
				<option value="2" <?php  if($item['housestatus'] == 2) { ?> selected<?php  } ?>>新盘</option>
				<option value="3" <?php  if($item['housestatus'] == 3) { ?> selected<?php  } ?>>尾盘</option>
				<option value="4" <?php  if($item['housestatus'] == 4) { ?> selected<?php  } ?>>售完</option>
				<option value="5" <?php  if($item['housestatus'] == 5) { ?> selected<?php  } ?>>待售</option>

				
			</select>
	</div>
</div>


<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>地图位置</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_form_field_coordinate('location', array('lng' => $item['lng'], 'lat' => $item['lat']))?>
					</div>
				</div>

<input type="hidden" name="id" id="id" value="<?php  echo $item['id'];?>"/>



<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">首图</label>
	<div class="col-sm-9 col-xs-12">
		<?php  echo tpl_form_field_image('thumb', $item['thumb'], '', array('extras' => array('text' => 'readonly')))?>
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">房源视频</label>
	<div class="col-sm-9 col-xs-12">
		<?php  echo tpl_form_field_video('video', $item['video'], '', array('extras' => array('text' => 'readonly')))?>
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">内页滚动图片</label>
	<div class="col-sm-9 col-xs-12">
		<?php  echo tpl_form_field_multi_image('thumbs',$piclist)?>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">详情介绍</label>
	<div class="col-sm-9 col-xs-12">
		<?php  echo tpl_ueditor('content', $item['content']);?>
	</div>
</div>

<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">首页推荐</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isrecommand' value=1 <?php  if($item['isrecommand']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isrecommand' value=0 <?php  if($item['isrecommand']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>



<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>排序</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="sort" id="sort" class="form-control" value="<?php  echo $item['sort'];?>" />
	</div>
</div>


</div>


<script language="javascript">



  	$("#cityid").change(function(){
    	var cityid = $("#cityid").val();
        
    //  alert(cityid);
    
      var url = "./index.php?c=site&a=entry&op=getcity&do=newhouse&m=weixinmao_house";
      
      
     // alert(url);
		$.ajax({
			"url": url,
             data:{cityid:cityid},
           datatype:"JSON",
			success:function(data){
              
            var json =  JSON.parse(data);
              console.log(json.data);
     var list = json.data;
              $("#houseareaid").empty();
              $("#houseareaid").append("<option value='0'>请选择所属地区</option>"); 
                  $.each(list, function (index) {  
                    //循环获取数据    
                              $("#houseareaid").append("<option value="+list[index].id+">"+list[index].name+"</option>"); 

                 // alert(list[index].housename);  
                  
                }); 
			}
		});
    
    
    
    })


$("#houseareaid").change(function(){
    	var houseareaid = $("#houseareaid").val();
        
   
    
      var url = "./index.php?c=site&a=entry&op=getbuild&do=newhouse&m=weixinmao_house";
      
      
     // alert(url);
		$.ajax({
			"url": url,
             data:{houseareaid:houseareaid},
           datatype:"JSON",
			success:function(data){
              
            var json =  JSON.parse(data);
              console.log(json.data);
     var list = json.data;
              $("#bid").empty();
              $("#bid").append("<option value='0'>请选择所属片区</option>"); 
                  $.each(list, function (index) {  
                    //循环获取数据    
                    $("#bid").append("<option value="+list[index].id+">"+list[index].name+"</option>"); 
                  
                }); 
			}
		});
    
    
    
    })




	$(function () {
		var i = 0;
		$('#selectimage').click(function () {
			var editor = KindEditor.editor({
				allowFileManager: false,
				imageSizeLimit: '30MB',
				uploadJson: './index.php?act=attachment&do=upload'
			});
			editor.loadPlugin('multiimage', function () {
				editor.plugin.multiImageDialog({
					clickFn: function (list) {
						if (list && list.length > 0) {
							for (i in list) {
								if (list[i]) {
									html = '<li class="imgbox" style="list-type:none">' +
												'<a class="item_close" href="javascript:;" onclick="deletepic(this);" title="删除"></a>' +
												'<span class="item_box"> <img src="' + list[i]['url'] + '" style="height:80px"></span>' +
												'<input type="hidden" name="attachment-new[]" value="' + list[i]['filename'] + '" />' +
											'</li>';
									$('#fileList').append(html);
									i++;
								}
							}
							editor.hideDialog();
						} else {
							alert('请先选择要上传的图片！');
						}
					}
				});
			});
		});
	});

	function deletepic(obj) {
		if (confirm("确认要删除？")) {
			var $thisob = $(obj);
			var $liobj = $thisob.parent();
			var picurl = $liobj.children('input').val();
			$.post('<?php  echo $this->createMobileUrl('ajaxdelete',array())?>', {pic: picurl}, function (m) {
				if (m == '1') {
					$liobj.remove();
				} else {
					alert("删除失败");
				}
			}, "html");
		}
	}

</script>