<?php defined('IN_IA') or exit('Access Denied');?>  		<h3 class="sub_title_bar white" style="display: -webkit-inline-box;margin-bottom: 5px;">
	  		<?php  if(is_array($topbar)) { foreach($topbar as $k => $item) { ?>
	  			<?php  if($k != 'search') { ?>
	  			<div class="filter_content dropdown_topbar"> 
			   		<div class="dropdown_menu ">
			    		<a href="javascript:;" class="btn dropdown_switch jsDropdownBt">
			    			<label class="jsBtLabel">
			    				<?php  if(is_array($item)) { foreach($item as $kk => $in) { ?>
			    					<?php  if($_GPC[$k] == $in['value']) { ?>
			    						<?php  echo $in['name'];?>
			    					<?php  } ?>
			    				<?php  } } ?>
			    			</label>
			    			<i class="arrow"></i>
			    		</a> 
			    		<div class="dropdown_data_container jsDropdownList" > 
				     		<ul class="dropdown_data_list">
				     			<?php  if(is_array($item)) { foreach($item as $kk => $in) { ?>
				      			<li class="dropdown_data_item "> <a href="<?php  echo $in['url'];?>"><?php  echo $in['name'];?></a> </li> 
				      			<?php  } } ?>
				    		</ul>
			    		</div> 
			   		</div>
	  			</div>
	  			<?php  } ?>
	  		<?php  } } ?>
	  		<?php  if(!empty($topbar['search'])) { ?>
	  		<?php  if(is_array($topbar['search'])) { foreach($topbar['search'] as $item) { ?>
				<span class="frm_input_box search append  frm_input_box_200px">
					<form action="./index.php" method="get" >
						<input type="hidden" name="c" value="site" />
						<input type="hidden" name="a" value="entry" />
						<input type="hidden" name="m" value="<?php  echo MODULE?>" />
						<input type="hidden" name="do" value="<?php  echo $item['do'];?>" />
						<input type="hidden" name="op" value="<?php  echo $item['op'];?>" />
						<input type="hidden" name="p" value="<?php  echo $item['p'];?>" />
						<a href="javascript:void(0);" class="frm_input_append js_search">
							<i class="icon16_common search_gray">搜索</i>&nbsp;
						</a>
						<input type="text" name="<?php  echo $item['for'];?>"  placeholder="<?php  echo $item['placeholder'];?>" value="" class="frm_input">	
					</form>
				</span>
			<?php  } } ?>
			<?php  } ?>
  		</h3>