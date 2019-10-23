<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>
<?php  if($_GPC['op'] == 'wait' || $_GPC['op'] == 'scaned') { ?>
	<?php  if(!empty( $list )) { ?>
        <div class="extra_info icon_after r ml10">
            <form action="./index.php" method="get" >
			<a href="javascript:;" style="padding-right: 10px;" class="deleteform" type="1">清空未审核数据</a>
			<a href="javascript:;" class="deleteform" type="2">清空已审核数据</a>
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="m" value="<?php  echo MODULE?>" />
                <input type="hidden" name="do" value="<?php  echo $_GPC['do'];?>" />
                <input type="hidden" name="op" value="<?php  echo $_GPC['op'];?>" />
                <input type="hidden" name="p" value="<?php  echo $_GPC['p'];?>" />
                <input type="hidden" name="down" value="1" />
                <?php  echo tpl_form_field_daterange('time',array('start'=> date('Y-m-d H:i:s',TIMESTAMP-3600*24*30),'end'=>date('Y-m-d H:i:s',TIMESTAMP) ), true);?>
                <input type="submit" name="" value="下载数据" class="a_href">
            </form>
        </div>
	   <table class="table" cellspacing="0"> 
	   <thead class="thead"> 
	        <tr> 
	            <th class="table_cell title td_col_1"> 
	                <label class="frm_checkbox_label" for="selectAll"> 
	                    <i class="icon_checkbox"></i> 
	                    <span class="lbl_content">id</span> 
	                    <input type="checkbox" class="frm_checkbox" id="selectAll" /> 
	                </label>
	            </th>
	            <th class="table_cell tl td_col_5">表单内容</th>
	            <th class="table_cell tl td_col_2">提交时间</th>
	            <th class="table_cell tr td_col_1">操作</th>
	        </tr> 
	   </thead>
	   <tbody class="tbody" id="js_goods">
	   <form method="post">	
	       	<?php  if(is_array($list)) { foreach($list as $item) { ?>
	            <tr> 
	                <td class="table_cell title td_col_1"> 
	                    <div class="goods_info">
	                        <label class="frm_checkbox_label" > 
	                            <i class="icon_checkbox"></i> 
	                            <input type="checkbox" name="checkall[]" class="frm_checkbox" value="<?php  echo $item['id'];?>" /> 
	                            <?php  echo $item['id'];?>
	                        </label>
	                    </div>
	                </td> 
	                <td class="table_cell price tl td_col_5">
	                    <?php  echo $item['content'];?>
	                </td> 
                    <td class="table_cell price tl td_col_2">
                        <?php  echo date('Y-m-d H:i',$item['createtime'])?>
                    </td>                                                                                                         
	                <td class="table_cell oper last_child tr opclass td_col_1" style="position: relative;">
	                    <p>
	                        <a href="<?php  echo self::pwUrl('site','form',array('op'=>'deleteform','id'=>$item['id']))?>" onclick="return confirm('删除不能恢复，确定要删除吗？');">删除</a>
                            <?php  if($item['isread'] == 0) { ?>
	                           <a href="javascript:;" class="toreaded" fid="<?php  echo $item['id'];?>" >记为审核</a>
                            <?php  } ?>
	                    </p>                 
	                </td>
	            </tr>
	        <?php  } } ?>
	    </tbody>
	    </table>
	    <div class="bottom_page item_cell_box">
	        <div class="dib tl">
	                <label class="frm_checkbox_label" for="selectAll"> 
	                    <i class="icon_checkbox"></i> 
	                    <span class="lbl_content">全选</span> 
	                    <input type="checkbox" class="frm_checkbox" id="selectAll" /> 
	                </label>
	            <div class="filter_content dropdown_topbar"> 
	                <div class="dropdown_menu ">
	                    <a href="javascript:;" class="btn dropdown_switch jsDropdownBt">
	                        <label class="jsBtLabel">批量操作</label>
	                        <i class="arrow"></i>
	                    </a> 
	                    <div class="dropdown_data_container jsDropdownList" > 
	                        <ul class="dropdown_data_list"> 
	                            <li class="dropdown_data_item "> 
	                                <input name="deleteall" class="alldeal_btn" value="删除所选" onclick="return confirm('确定要删除选择的吗？');" type="submit">
	                            </li>
                                <?php  if($_GPC['op'] == 'wait') { ?>
                                    <li class="dropdown_data_item "> 
                                        <input name="readall" class="alldeal_btn" value="记为审核" onclick="return confirm('确定要将选择的记为已审核吗？');" type="submit">
                                    </li>
                                <?php  } ?>
	                        </ul> 
	                    </div> 
	                </div>
	            </div>
	        </div>
	        <div class="tr dib item_cell_flex">
	            <?php  echo $pager;?>
	        </div>
	    </div>
	        <input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
	    </form>
	    <?php  } else { ?>
	    	<div class="no_data">没有找到数据</div>
	    <?php  } ?>
<?php  } else if($_GPC['op'] == 'admin') { ?>
	
	<div class="tc" >

		<div class="admin_box_r" style="display: inline-block;margin-top: 50px;">
			<div>
				<img id="qrcode" src="<?php  echo $this->createWebUrl('img',array('op'=>'formlist'))?>">
				<p class="font_tips qrcode_tips">此二维码是微信端查看会员提交表单数据的二维码，查看验证码在参数设置里设置</p>
			</div>
		</div>
	</div>
<script type="text/javascript">

	function  getqrcode(){
		Http('post','json','getqrcode',{},function(data){
			if( data.status == 202 ){
				$('#qrcode').attr('src',data.obj.url);
			}
		},false);
	}
	Http('post','json','checkqrcode',{},function(data){
		if( data.status == 201 ){
			$('.qrcode_tips').text(data.res);
		}
	},true);

</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>
