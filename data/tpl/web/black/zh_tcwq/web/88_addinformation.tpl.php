<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<style type="text/css">
	input[type="radio"] + label::before {
		content: "\a0"; /*不换行空格*/
		display: inline-block;
		vertical-align: middle;
		font-size: 16px;
		width: 1em;
		height: 1em;
		margin-right: .4em;
		border-radius: 50%;
		border: 2px solid #ddd;
		text-indent: .15em;
		line-height: 1; 
	}
	input[type="radio"]:checked + label::before {
		background-color: #44ABF7;
		background-clip: content-box;
		padding: .1em;
		border: 2px solid #44ABF7;
	}
	input[type="radio"] {
		position: absolute;
		clip: rect(0, 0, 0, 0);
	}
	.dizhiname{width: 500px;float: left;}
	.dizhi:hover{color: #ff3b3b}
	.dizhi{
		color: #ff3b3b;
		float: left;
		height: 35px;
		line-height: 35px;
		margin-left: 20px;
		font-weight: bold;

	}
</style>

<ul class="nav nav-tabs">    
	<span class="ygxian"></span>
	<div class="ygdangq">当前位置:</div>
	<li class="active"><a href="javascript:void(0);">帖子信息</a></li>
</ul>

<div class="main">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">

		<!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->

		<div class="panel panel-default ygdefault">

			<div class="panel-heading wyheader">

				帖子信息

			</div>

			<div class="panel-body">

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label" style="margin-right: 15px;">栏目分类</label>
					<select class="col-sm-8" name="type_id" id="onefenlei">
						<?php  if(is_array($type)) { foreach($type as $key => $item) { ?>

						<option value="<?php  echo $item['id'];?>" ><?php  echo $item['type_name'];?></option>

						<?php  } } ?>
					</select>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label" style="margin-right: 15px;">二级栏目分类</label>
					<input type="hidden" id="hiddeninfo" value="<?php  echo $info['type2_id'];?>">
					<select class="col-sm-8" name="type2_id" id="twofenlei" value="">

					</select>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">所属城市</label>
					<div class="col-sm-9">
						<input type="text" name="cityname" class="form-control" value="<?php  echo $info['cityname'];?>" />
						*填空为全国版
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">地址坐标</label>
					<div class="col-sm-10">
						<!-- <?php  echo tpl_form_field_coordinate('op',$list['coordinates'])?> -->
						<input type="text" name="coordinates" class="form-control dizhiname" value="<?php  echo $info['coordinates'];?>" placeholder="地理位置" />
						<a href="http://lbs.qq.com/tool/getpoint/" target="_blank" class="dizhi">点击获取地理位置</a>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">发布人</label>
					<div class="col-sm-9">
						<input type="text" name="user_name" class="form-control" value="<?php  echo $info['user_name'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">发布人头像</label>
					<div class="col-sm-9">
						<?php  echo tpl_form_field_image('user_img2',$item['user_img2']);?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">联系电话</label>
					<div class="col-sm-9">
						<input type="text" name="user_tel" class="form-control" value="<?php  echo $info['user_tel'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">地址信息</label>
					<div class="col-sm-9">
						<input type="text" name="address" class="form-control" value="<?php  echo $info['address'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子内容</label>
					<div class="col-sm-9">
						<textarea name="details" class="form-control" cols="30" rows="7"><?php  echo $info['details'];?></textarea>
					</div>
				</div>


				<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子图片</label>
					<div class="col-sm-9">
						<?php  echo tpl_form_field_multi_image('img',$img);?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">浏览量</label>
					<div class="col-sm-9">
						<input type="number" name="views" class="form-control" value="<?php  echo $info['views'];?>" />
					</div>
				</div>
<!-- 
              <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否热门</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" name="hot" value="1" <?php  if($info['hot']==1 || empty($info['hot'])) { ?>checked<?php  } ?> />是
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="hot" value="2" <?php  if($info['hot']==2) { ?>checked<?php  } ?> />否
                        </label>
                    </div>
                </div> -->
                <div class="form-group">
                	<label for="lastname" class="col-sm-2 control-label">是否置顶</label>
                	<div class="col-sm-10">
                		<label class="radio-inline">
                			<input type="radio" id="emailwy1" name="top" value="1" <?php  if($info['top']==1) { ?>checked<?php  } ?> />
                			<label for="emailwy1">是</label>
                		</label>
                		<label class="radio-inline">
                			<input type="radio" id="emailwy2" name="top" value="2" <?php  if($info['top']==2 || empty($info['top'])) { ?>checked<?php  } ?> />
                			<label for="emailwy2">否</label>
                		</label>
                	</div>
                </div>
                <div class="form-group">
                	<label class="col-xs-12 col-sm-3 col-md-2 control-label">到期时间</label>
                	<div class="col-sm-10">
                		<?php  echo tpl_form_field_date('dq_time',date('Y-m-d',$info['dq_time']))?>
                	</div>
                </div>



            </div>

        </div>

        <div class="form-group">

        	<input type="submit" name="submit" value="添加" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>

        	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        	<input type="hidden" name="id" value="<?php  echo $info['id'];?>" />

        </div>

    </form>

</div>
<script type="text/javascript">
	$(function(){
		$("#frame-1").show();
		$("#yframe-1").addClass("wyactive");

		var onefen = $("#onefenlei").val();
		console.log(onefen)
		$.ajax({
			type:"post",
			url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=GetInformationType&m=zh_tcwq",
			dataType:"text",
			data:{id:onefen},
			success:function(data){
				var data = eval('(' + data + ')');
				console.log(data);
				for(var i = 0; i<data.length;i++){
					console.log(data[i].name+data[i].id);
					$("#twofenlei").append("<option value="+data[i].id+">"+data[i].name+"</option>");                    	
				}
				console.log("这是选中的值："+$("#twofenlei").val());
				$("#twofenlei").val($("#hiddeninfo").val());

			}
		})
		$("#onefenlei").change(function(){
			$("#twofenlei").empty();
			var str = $("#onefenlei option").map(function(){
				return $(this).text();
			}).get().join(", ")
			console.log(str);
			var onefenl = $("#onefenlei").val();
			var twofen = $("#twofenlei").val();
			$.ajax({
				type:"post",
				url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=GetInformationType&m=zh_tcwq",
				dataType:"text",
				data:{id:onefenl},
				success:function(data){
					var data = eval('(' + data + ')');
					console.log(data);
					for(var i = 0; i<data.length;i++){
						console.log(data[i].name+data[i].id);
						$("#twofenlei").append("<option value="+data[i].id+">"+data[i].name+"</option>");                    	
					}
					console.log("这是选中的值："+$("#twofenlei").val());
				}
			})
			
			
		});

	})
</script>
