<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<div data-skin="default" class="skin-default <?php  if($_GPC['main-lg']) { ?> main-lg-body <?php  } ?>">
<?php  $frames = buildframes(FRAME);_calc_current_frames($frames);?>

 
<script type="text/javascript">
	window.version_id = "<?php  echo intval( $_GPC['version_id'] )?>";
	window.auth = <?php  echo json_encode( model_auth::authList() )?>;
	window.myset = <?php  echo json_encode( model_sysset::getSet() )?>;
	window.now = <?php  echo TIMESTAMP?>;
</script>
<link rel="stylesheet" type="text/css" href="<?php  echo MODULE_URL?>template/web/css/common.css<?php echo '?t='.TIMESTAMP?>">
<link rel="stylesheet" type="text/css" href="<?php  echo MODULE_URL?>template/web/css/tao.css<?php echo '?t='.TIMESTAMP?>">
<link rel="stylesheet" href="<?php  echo MODULE_URL?>template/web/css/jquery-ui.css">
<script src="<?php  echo MODULE_URL?>template/web/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php  echo MODULE_URL?>template/web/js/tao.js<?php echo '?t='.TIMESTAMP?>"></script>

<?php  $menu = menu::init()?>

<div class="header" style="padding-top: 0;">
    <div class="wrap">
        <div class="logo" style="padding-right: 0;">
        	<img src="<?php echo $_W['siteroot'].'attachment/headimg_'.$_W['uniacid'].'.jpg?time='.TIMESTAMP?>" style="display: inline-block;">
        	<span class="header_name"><?php  echo $_W['account']['name'];?></span>
        </div>
        <div class="nav">
            <ul>
                <?php  if(is_array($menu)) { foreach($menu as $item) { ?>
                	<li <?php  if($_GPC['p'] == $item['p'] ) { ?>class="selected"<?php  } ?>><a href="<?php  echo $item['url'];?>"><?php  echo $item['name'];?></a></li>
                <?php  } } ?>
            </ul>
        </div>
        <div class="nav_r">
        	<li>
        		<a href="./index.php?c=wxapp&a=display&do=home">返回小程序</a>
        	</li>
        </div>
    </div>
</div>
<?php  if($_GPC['do'] == 'page' && in_array($_GPC['op'],array('add','edit'))) { ?>

<?php  } else { ?>
<?php  if(is_array($menu)) { foreach($menu as $out) { ?>
<?php  if($out['p'] == $_GPC['p']) { ?>
	
 	<div id="body" class="body page_message" style="padding-top: 1px;">
   		<div id="js_container_box" class="container_box cell_layout side_l">
	    	<div class="col_side"> 
	    		
	    		 <div class="menu_box" id="menuBar">
				    <?php  if(is_array($out['leftbar'])) { foreach($out['leftbar'] as $k => $item) { ?>

				    	<?php  if($item['hide'] == 0 && !empty( $item )) { ?>
					    <dl class="menu">
					     	<dt class="menu_title clickable">
					     		<a href="<?php echo empty($item['url']) ? 'javascript:;' : $item['url']?>">
					      			<i class="icon_menu" style="background:url(<?php  echo $item['icon'];?>) no-repeat;"> </i><?php  echo $item['name'];?> 
					      		</a>
					     	</dt>
					     	<?php  if(is_array($item['list'])) { foreach($item['list'] as $kk => $vv) { ?>
					     		<?php  if($vv['hide'] == 0) { ?>
							    <dd class="menu_item <?php  if(($_GPC['do'] == $k && $_GPC['op'] == $vv['op']) || $_GPC['c'] == 'module' && $vv['op'] == 'set' ) { ?>selected<?php  } ?>">
							      	<a href="<?php  echo $vv['url'];?>" class="left_title_box"><?php  echo $vv['name'];?> <?php  if(isset($vv['num'])) { ?><i><?php  echo $vv['num'];?></i><?php  } ?></a>
							    </dd>
							    <?php  } ?>
							<?php  } } ?>
					    </dl>
					    <?php  } ?>
				    <?php  } } ?>
	     		</div>

	    	</div>
	    	<div class="col_main">

	    		<?php  if(is_array($out['leftbar'])) { foreach($out['leftbar'] as $k => $item) { ?>
	    			<?php  if($_GPC['do'] == $k) { ?>
						<div class="main_hd">
							<h2><?php  echo $item['name'];?></h2>
							<div class="title_tab" id="topTab">
								<ul class="tab_navs title_tab">
									<?php  if(is_array($item['list'])) { foreach($item['list'] as $kk => $vv) { ?>
										
										<?php  if($vv['hide'] != 1 || ($_GPC['op'] == $vv['op'] && $vv['hide'] == 1 ) || $vv['showtop'] == 1) { ?>
										<li class="tab_nav first js_top <?php  if($_GPC['op'] == $vv['op']) { ?>selected<?php  } ?>">
										<?php  if(in_array( $_GPC['op'],(array)$item['toplist'] )) { ?>
											<a class="left_title_box top_title_box" href="<?php  echo $vv['url'];?>"><?php  echo $vv['name'];?> <?php  if(isset($vv['num'])) { ?><i><?php  echo $vv['num'];?></i><?php  } ?> </a>
										<?php  } ?>
										</li>
										<?php  } ?>
									<?php  } } ?>
								</ul>
							</div>
						</div>
					<?php  } ?>
				<?php  } } ?>
				

				<div class="main_bd">
<?php  } ?>
<?php  } } ?>
<?php  } ?>