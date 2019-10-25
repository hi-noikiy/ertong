<?php defined('IN_IA') or exit('Access Denied');?>
<input type="hidden" name="id" id="id" value="<?php  echo $intro['id'];?>"/>
	<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $intro['name'];?>" />
					</div>
				</div>



<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>行业分类</label>
	<div class="col-sm-9 col-xs-12">
		<select name="cateid"  class="form-control">
			<option value="0">请选择行业分类</option>
			<?php  if(is_array($storecate)) { foreach($storecate as $vo) { ?>
			<option value="<?php  echo $vo['id'];?>" <?php  if($vo['id'] == $intro['cateid']) echo 'selected';?>><?php  echo $vo['advname'];?></option>
			<?php  } } ?>
		</select>
	</div>
</div>

				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店logo</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('logo', $intro['logo'])?>
                    </div>
                </div>

	
	
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店介绍</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('content', $intro['content'])?>
                    </div>
                </div>
                 <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店地址</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='address' name="address" class="form-control" value="<?php  echo $intro['address'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店位置</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_form_field_coordinate('location', array('lng' => $intro['lng'], 'lat' => $intro['lat']))?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店电话</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='tel' name="tel" class="form-control" value="<?php  echo $intro['tel'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>QQ</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='qq' name="qq" class="form-control" value="<?php  echo $intro['qq'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>Email</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='email' name="email" class="form-control" value="<?php  echo $intro['email'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>门店营业时间</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='opentime' name="opentime" class="form-control" value="<?php  echo $intro['opentime'];?>" />
					</div>
				</div>
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否默认</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='isdefault' value=1' <?php  if($intro['isdefault']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='isdefault' value=0' <?php  if($intro['isdefault']==0) { ?>checked<?php  } ?> /> 否
						</label>
					</div>
				</div>
<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>排序</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="sort" id="sort" class="form-control" value="<?php  echo $intro['sort'];?>" />
	</div>
</div>

</div>


<script language="javascript">

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