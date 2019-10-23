<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="main">
	<ul class="nav nav-tabs">
        <li class="active"><a href="<?php  echo $this->createWebUrl('member',array('op' =>'display'))?>">会员管理</a></li>
	</ul>

	<div style="padding:15px;">
		<form id="form2" class="form-horizontal" method="post">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:200px;">昵称</th>
					<th style="width:200px;">openid</th>
                   <th>头像</th> <th>积分</th>
					<th>创建时间</th>
					<th style="text-align:right;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $row) { ?>
				<tr>
					<td><?php  echo $row['nickname'];?> </td>
					<td><?php  echo $row['openid'];?> </td>
                    <td>
                        <img src="<?php  echo $row['avatar'];?>" style="width: 50px;height: 50px;" ></img>
                    </td>
					<td><?php  echo $row['credit1'];?></td>
					<td><?php  echo date('Y-m-d H:i:s', $row['createtime'])?></td>
					<td>
                        <a onclick="return confirm('您确定要删除吗？');return false;" href="<?php  echo $this->createWebUrl('pmember', array('op'=>'del','id'=>$row['id'],'status'=>1))?>"
                           class="btn btn-default btn-sm" title="取消拉黑"><i class="fa fa-unlock"></i>删除
                        </a>
						<?php  if($_W['isfounder']) { ?>
						<a class="btn btn-default btn-sm" href="javascript:;" onclick="$('#modal-upCreatetime').find(':input[name=cid]').val('<?php  echo $row['openid'];?>')" data-toggle="modal" data-target="#modal-upCreatetime"> <span  class="text-primary fa fa-random">赠送积分</span></a>
						<?php  } ?>
					</td>
				</tr>
				<?php  } } ?>
					<tr>

			</tr>
			</tbody>
		</table>
		<?php  echo $pager;?>
		</form>
	</div>
</div>

<div id="modal-upCreatetime" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<form class="form-horizontal form" action="<?php  echo $this->createWebUrl('member',array('op'=>'ajaxUpdateCredits'))?>" method="post" enctype="multipart/form-data">
		<input  type="hidden" name="cid" id="cid">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
					<h4>赠送<?php  if($custom_set['credittxt']) { ?><?php  echo $custom_set['credittxt'];?><?php  } else { ?>积分<?php  } ?></h4>
				</div>
				<div class="modal-body">
					<label for="upCreatetimeval" class="control-label">赠送<?php  if($custom_set['credittxt']) { ?><?php  echo $custom_set['credittxt'];?><?php  } else { ?>积分<?php  } ?>: </label>
					<div class="input-group">
						<input type="text" id='credit1' name="credit1" class="form-control" value="10" />
					</div>
				</div>

				<div class="modal-footer">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>"/>
					<button type="submit" class="btn btn-success col-sm-2" name="submit" value="赠送">
						<i class="fa fa-check-circle"></i> 赠送
					</button>

					<a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a>
				</div>

			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
    require(['bootstrap'], function ($) {
        $('.btn').hover(function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('hide');
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>