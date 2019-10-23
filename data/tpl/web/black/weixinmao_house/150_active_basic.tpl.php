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
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>活动名称</label>
	<div class="col-sm-9 col-xs-12">
		<input type="text" name="title" id="title" class="form-control" value="<?php  echo $item['title'];?>" />
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
	<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>绑定房源</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="pid" name="pid">
				<option value="0">请绑定房源</option>
				<?php  if(is_array($newhouselist)) { foreach($newhouselist as $v) { ?>
				<option value="<?php  echo $v['id'];?>" <?php  if($item['pid'] == $v['id']) { ?> selected <?php  } ?> ><?php  echo $v['housename'];?></option>
				<?php  } } ?>
				
			</select>
	</div>
</div>



<div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">活动详情</label>
	<div class="col-sm-9 col-xs-12">
		<?php  echo tpl_ueditor('content', $item['content']);?>
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