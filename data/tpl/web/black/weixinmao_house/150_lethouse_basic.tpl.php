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
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>标题</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="title" id="title" class="form-control" value="<?php  echo $item['title'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房租金</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="money" id="money" class="form-control" value="<?php  echo $item['money'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>支付定金</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="price" id="price" class="form-control" value="<?php  echo $item['price'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>打赏金额</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="dmoney" id="dmoney" class="form-control" value="<?php  echo $item['dmoney'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>佣金</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="fxmoney" id="fxmoney" class="form-control" value="<?php  echo $item['fxmoney'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>户间数</label>
	<div class="col-sm-9 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="roomid" name="roomid">
				<option value="0">请选择类型 </option>
				<option value="1" <?php  if($item['roomid'] == 1) { ?> selected<?php  } ?>>1室</option>
				<option value="2" <?php  if($item['roomid'] == 2) { ?> selected <?php  } ?>>2室</option>
				<option value="3" <?php  if($item['roomid'] == 3) { ?> selected <?php  } ?>>3室</option>
				<option value="4" <?php  if($item['roomid'] == 4) { ?> selected <?php  } ?>>4室</option>
				<option value="5" <?php  if($item['roomid'] == 5) { ?> selected <?php  } ?>>5室</option>
				<option value="6" <?php  if($item['roomid'] == 6) { ?> selected <?php  } ?>>5室以上</option>
				
			</select>
	

	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>房型</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="roomtype" id="roomtype" class="form-control" value="<?php  echo $item['roomtype'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>出租房类型</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="housetype" name="housetype">
				<option value="0">请选择类型 </option>
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
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">租房方式</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='letway' value=1 <?php  if($item['letway']==1) { ?>checked<?php  } ?> /> 整租
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='letway' value=2 <?php  if($item['letway']==2) { ?>checked<?php  } ?> /> 合租
						 </label>
					</div>
				</div>
				
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>付款方式</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="payway" id="payway" class="form-control" value="<?php  echo $item['payway'];?>" />
	</div>
</div>		
				
<div class="form-group">

		<label class="col-xs-12 col-sm-3 col-md-2 control-label">出租房标签</label>
					<div class="col-sm-9 col-xs-12">
			<label class='radio-inline'><input <?php  if($labellist ) { ?> <?php  if(in_array('拎包即住',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="拎包即住" />拎包即住</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?> <?php  if(in_array('交通便利',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="交通便利" />交通便利</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('设施齐全',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="设施齐全" />设施齐全</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('随时看房',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="随时看房" />随时看房</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('单身公寓',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="单身公寓" />单身公寓</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('精装修',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="精装修" />精装修</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('繁华地段',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="繁华地段" />繁华地段</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('婚房出租',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="婚房出租" />婚房出租</label>
			<label class='radio-inline'><input <?php  if($labellist ) { ?><?php  if(in_array('有钥匙',$labellist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="houselabel[]" value="有钥匙" />有钥匙</label>
		
					</div>

</div>


<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房面积</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="area" id="area" class="form-control" value="<?php  echo $item['area'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房楼层</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="floor" id="floor" class="form-control" value="<?php  echo $item['floor'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房朝向</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="direction" id="direction" class="form-control" value="<?php  echo $item['direction'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>出租房装修</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="decorate" name="decorate">
				<option value="">请选择装修</option>
				<option value="简易装修" <?php  if($item['decorate'] == '简易装修') { ?> selected <?php  } ?>>简易装修</option>
				<option value="中档装修" <?php  if($item['decorate'] == '中档装修') { ?> selected <?php  } ?>>中档装修</option>
				<option value="高档装修" <?php  if($item['decorate'] == '高档装修') { ?> selected <?php  } ?>>高档装修</option>
				<option value="豪华装修" <?php  if($item['decorate'] == '豪华装修') { ?> selected <?php  } ?>>豪华装修</option>
				<option value="毛坯" <?php  if($item['decorate'] == '毛坯') { ?> selected <?php  } ?>>毛坯</option>
			
				
			</select>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>建房年代</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="year" id="year" class="form-control" value="<?php  echo $item['year'];?>" />
	</div>
</div>
<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">出租房来源</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='source' value=0 <?php  if($item['source']==0) { ?>checked<?php  } ?> /> 房东
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='source' value=1 <?php  if($item['source']==1) { ?>checked<?php  } ?> /> 中介
						 </label>
					</div>
				</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>房东(中介)姓名</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="name" id="name" class="form-control" value="<?php  echo $item['name'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>房东(中介)电话</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="tel" id="tel" class="form-control" value="<?php  echo $item['tel'];?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房小区</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="housearea" id="housearea" class="form-control" value="<?php  echo $item['housearea'];?>" />
	</div>
</div>

<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>出租房地址</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="address" id="address" class="form-control" value="<?php  echo $item['address'];?>" />
	</div>
</div>


<div class="form-group">

		<label class="col-xs-12 col-sm-3 col-md-2 control-label">房源配套</label>
					<div class="col-sm-9 col-xs-12">
			<label class='radio-inline'><input  <?php  if($speciallist ) { ?><?php  if(in_array('厨房',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="厨房" />厨房</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('床',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="床" />床</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('家具',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="家具" />家具</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('有线电视',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="有线电视" />有线电视</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('热水器',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="热水器" />热水器</label>
			<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('宽带网',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="宽带网" />宽带网</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('电话',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="电话" />电话</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('饮水机',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="饮水机" />饮水机</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('电视机',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="电视机" />电视机</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('冰箱',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="冰箱" />冰箱</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('煤气',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="煤气" />煤气</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('车库',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="车库" />车库</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('天然气',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="天然气" />天然气</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('暖气',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="暖气" />暖气</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('空调',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="空调" />空调</label>
<label class='radio-inline'><input <?php  if($speciallist ) { ?><?php  if(in_array('洗衣机',$speciallist)) { ?> checked <?php  } ?><?php  } ?> type="checkbox" name="special[]" value="洗衣机" />洗衣机</label>






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
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>销售状态</label>
	<div class="col-sm-9 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="salestatus" name="salestatus" >
				<option value="0">请选择销售状态</option>
				<option value="1" <?php  if($item['salestatus'] == 1) { ?> selected<?php  } ?>>在售</option>
				<option value="2" <?php  if($item['salestatus'] == 2) { ?> selected<?php  } ?>>待售</option>
				<option value="3" <?php  if($item['salestatus'] == 3) { ?> selected<?php  } ?>>售完</option>
				<option value="4" <?php  if($item['salestatus'] == 4) { ?> selected<?php  } ?>>打折</option>
				
			</select>
	</div>
</div>


<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">状态</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='status' value=0 <?php  if($item['status']==0) { ?>checked<?php  } ?> /> 上架
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='status' value=1 <?php  if($item['status']==1) { ?>checked<?php  } ?> /> 下架
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