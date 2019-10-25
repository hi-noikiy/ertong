<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/index/index.html";i:1571855638;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $setting['pt_name']; if(!empty($setting['pt_name'])) echo '-'; ?>后台管理系统</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .main-header{
            position: fixed;
            width: 100%;
        }
        .content-wrapper{
            padding-top: 50px;
            position: fixed;
            width: calc(100vw - 230px);
            right: 0;
        }
        .sidebar-collapse .content-wrapper{
            width: calc(100vw - 50px);
        }
        body{
            height: 100vh!important;
        }
        .control-sidebar{
            position: fixed;
        }
        .control-sidebar>.tab-content{
            height: calc(100vh - 91px);
            overflow: auto;
        }
        @media (max-width: 767px)
        {
            .content-wrapper{
                padding-top: 100px;
                width: 100vw;
            }
        }
        .myscroll::-webkit-scrollbar { /*滚动条整体样式*/
            width: 5px;     /*高宽分别对应横竖滚动条的尺寸*/
            height: 0px;
        }
        .myscroll::-webkit-scrollbar-thumb { /*滚动条里面小方块*/
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
            background: #535353;
        }
        .myscroll::-webkit-scrollbar-track { /*滚动条里面轨道*/
            -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
            border-radius: 10px;
            /*background: #EDEDED;*/
        }

    </style>
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/custom/tabs-ext.css">
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo adminurl('index'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <!--<span class="logo-mini"><b>A</b>LT</span>-->
            <!-- logo for regular state and mobile devices -->
            <!--<span class="logo-lg"><b>Admin</b>LTE</span>-->
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b><?php echo !empty($setting['ht_title'])?$setting['ht_title']: 'A'; ?></b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b><?php echo !empty($setting['ht_title'])?$setting['ht_title']: 'Admin'; ?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <style type="text/css">
                .navbar.navbar-static-top .scroll-left{
                    position: absolute;
                    left: inherit;
                    /*background: rgba(0,0,0,0.1);*/
                    line-height: 50px;
                    color: #fff;
                    transform: scale(1,1);
                }
                .navbar.navbar-static-top .scroll-right{
                    position: absolute;
                    line-height: 50px;
                    color: #fff;
                    top: inherit;
                    right: 300px;
                    transform: scale(1,1);
                }
                .navbar.navbar-static-top .scroll-left:hover,.navbar.navbar-static-top .scroll-right:hover{
                    background: rgba(0,0,0,0.1);
                }
            </style>
            <a href="#" class="scroll-left hidden-xs"><</a>
            <ul class="nav navbar-nav hidden-xs myscroll" style="white-space: nowrap;overflow-x: auto;overflow-y: hidden;width: calc(100% - 400px);">
                <?php if(is_array($menugroup) || $menugroup instanceof \think\Collection || $menugroup instanceof \think\Paginator): $i = 0; $__LIST__ = $menugroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li style="float: none;display: inline-block;" data-menu="<?php echo $vo['id']; ?>"><a href="#"><?php echo $vo['name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <!--<li style="float: none;display: inline-block;" data-menu="Dashboard"><a href="#">Dashboard</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="LayoutOptions"><a href="#">LayoutOptions</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="Charts"><a href="#">Charts</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="UIElements"><a href="#">UIElements</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="Forms"><a href="#">Forms</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="Tables"><a href="#">Tables</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="Examples"><a href="#">Examples</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="Multilevel"><a href="#">Multilevel</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="商品管理"><a href="#">商品管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="门店管理"><a href="#">门店管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="菜单管理"><a href="#">菜单管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="权限管理"><a href="#">权限管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="系统设置"><a href="#">系统设置</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="商品管理"><a href="#">商品管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="门店管理"><a href="#">门店管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="菜单管理"><a href="#">菜单管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="权限管理"><a href="#">权限管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="系统设置"><a href="#">系统设置</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="商品管理"><a href="#">商品管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="门店管理"><a href="#">门店管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="菜单管理"><a href="#">菜单管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="权限管理"><a href="#">权限管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="系统设置"><a href="#">系统设置</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="商品管理"><a href="#">商品管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="门店管理"><a href="#">门店管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="菜单管理"><a href="#">菜单管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="权限管理"><a href="#">权限管理</a></li>-->
                <!--<li style="float: none;display: inline-block;" data-menu="系统设置"><a href="#">系统设置</a></li>-->
            </ul>
            <a href="#" class="scroll-right hidden-xs">></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <!--<li class="dropdown messages-menu">-->
                        <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                            <!--<i class="fa fa-envelope-o"></i>-->
                            <!--<span class="label label-success">4</span>-->
                        <!--</a>-->
                        <!--<ul class="dropdown-menu">-->
                            <!--<li class="header">You have 4 messages</li>-->
                            <!--<li>-->
                                <!--&lt;!&ndash; inner menu: contains the actual data &ndash;&gt;-->
                                <!--<ul class="menu">-->
                                    <!--<li>&lt;!&ndash; start message &ndash;&gt;-->
                                        <!--<a href="#">-->
                                            <!--<div class="pull-left">-->
                                                <!--<img src="/addons/sqtg_sun/public/static/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                                            <!--</div>-->
                                            <!--<h4>-->
                                                <!--Support Team-->
                                                <!--<small><i class="fa fa-clock-o"></i> 5 mins</small>-->
                                            <!--</h4>-->
                                            <!--<p>Why not buy a new awesome theme?</p>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--&lt;!&ndash; end message &ndash;&gt;-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<div class="pull-left">-->
                                                <!--<img src="/addons/sqtg_sun/public/static/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">-->
                                            <!--</div>-->
                                            <!--<h4>-->
                                                <!--AdminLTE Design Team-->
                                                <!--<small><i class="fa fa-clock-o"></i> 2 hours</small>-->
                                            <!--</h4>-->
                                            <!--<p>Why not buy a new awesome theme?</p>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<div class="pull-left">-->
                                                <!--<img src="/addons/sqtg_sun/public/static/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">-->
                                            <!--</div>-->
                                            <!--<h4>-->
                                                <!--Developers-->
                                                <!--<small><i class="fa fa-clock-o"></i> Today</small>-->
                                            <!--</h4>-->
                                            <!--<p>Why not buy a new awesome theme?</p>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<div class="pull-left">-->
                                                <!--<img src="/addons/sqtg_sun/public/static/dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">-->
                                            <!--</div>-->
                                            <!--<h4>-->
                                                <!--Sales Department-->
                                                <!--<small><i class="fa fa-clock-o"></i> Yesterday</small>-->
                                            <!--</h4>-->
                                            <!--<p>Why not buy a new awesome theme?</p>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<div class="pull-left">-->
                                                <!--<img src="/addons/sqtg_sun/public/static/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">-->
                                            <!--</div>-->
                                            <!--<h4>-->
                                                <!--Reviewers-->
                                                <!--<small><i class="fa fa-clock-o"></i> 2 days</small>-->
                                            <!--</h4>-->
                                            <!--<p>Why not buy a new awesome theme?</p>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                <!--</ul>-->
                            <!--</li>-->
                            <!--<li class="footer"><a href="#">See All Messages</a></li>-->
                        <!--</ul>-->
                    <!--</li>-->
                    <!--&lt;!&ndash; Notifications: style can be found in dropdown.less &ndash;&gt;-->
                    <!--<li class="dropdown notifications-menu">-->
                        <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                            <!--<i class="fa fa-bell-o"></i>-->
                            <!--<span class="label label-warning">10</span>-->
                        <!--</a>-->
                        <!--<ul class="dropdown-menu">-->
                            <!--<li class="header">You have 10 notifications</li>-->
                            <!--<li>-->
                                <!--&lt;!&ndash; inner menu: contains the actual data &ndash;&gt;-->
                                <!--<ul class="menu">-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<i class="fa fa-users text-aqua"></i> 5 new members joined today-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the-->
                                            <!--page and may cause design problems-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<i class="fa fa-users text-red"></i> 5 new members joined-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<i class="fa fa-shopping-cart text-green"></i> 25 sales made-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                        <!--<a href="#">-->
                                            <!--<i class="fa fa-user text-red"></i> You changed your username-->
                                        <!--</a>-->
                                    <!--</li>-->
                                <!--</ul>-->
                            <!--</li>-->
                            <!--<li class="footer"><a href="#">View all</a></li>-->
                        <!--</ul>-->
                    <!--</li>-->
                    <!--&lt;!&ndash; Tasks: style can be found in dropdown.less &ndash;&gt;-->
                    <!--<li class="dropdown tasks-menu">-->
                        <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                            <!--<i class="fa fa-flag-o"></i>-->
                            <!--<span class="label label-danger">9</span>-->
                        <!--</a>-->
                        <!--<ul class="dropdown-menu">-->
                            <!--<li class="header">You have 9 tasks</li>-->
                            <!--<li>-->
                                <!--&lt;!&ndash; inner menu: contains the actual data &ndash;&gt;-->
                                <!--<ul class="menu">-->
                                    <!--<li>&lt;!&ndash; Task item &ndash;&gt;-->
                                        <!--<a href="#">-->
                                            <!--<h3>-->
                                                <!--Design some buttons-->
                                                <!--<small class="pull-right">20%</small>-->
                                            <!--</h3>-->
                                            <!--<div class="progress xs">-->
                                                <!--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"-->
                                                     <!--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">-->
                                                    <!--<span class="sr-only">20% Complete</span>-->
                                                <!--</div>-->
                                            <!--</div>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--&lt;!&ndash; end task item &ndash;&gt;-->
                                    <!--<li>&lt;!&ndash; Task item &ndash;&gt;-->
                                        <!--<a href="#">-->
                                            <!--<h3>-->
                                                <!--Create a nice theme-->
                                                <!--<small class="pull-right">40%</small>-->
                                            <!--</h3>-->
                                            <!--<div class="progress xs">-->
                                                <!--<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"-->
                                                     <!--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">-->
                                                    <!--<span class="sr-only">40% Complete</span>-->
                                                <!--</div>-->
                                            <!--</div>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--&lt;!&ndash; end task item &ndash;&gt;-->
                                    <!--<li>&lt;!&ndash; Task item &ndash;&gt;-->
                                        <!--<a href="#">-->
                                            <!--<h3>-->
                                                <!--Some task I need to do-->
                                                <!--<small class="pull-right">60%</small>-->
                                            <!--</h3>-->
                                            <!--<div class="progress xs">-->
                                                <!--<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"-->
                                                     <!--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">-->
                                                    <!--<span class="sr-only">60% Complete</span>-->
                                                <!--</div>-->
                                            <!--</div>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--&lt;!&ndash; end task item &ndash;&gt;-->
                                    <!--<li>&lt;!&ndash; Task item &ndash;&gt;-->
                                        <!--<a href="#">-->
                                            <!--<h3>-->
                                                <!--Make beautiful transitions-->
                                                <!--<small class="pull-right">80%</small>-->
                                            <!--</h3>-->
                                            <!--<div class="progress xs">-->
                                                <!--<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"-->
                                                     <!--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">-->
                                                    <!--<span class="sr-only">80% Complete</span>-->
                                                <!--</div>-->
                                            <!--</div>-->
                                        <!--</a>-->
                                    <!--</li>-->
                                    <!--&lt;!&ndash; end task item &ndash;&gt;-->
                                <!--</ul>-->
                            <!--</li>-->
                            <!--<li class="footer">-->
                                <!--<a href="#">View all tasks</a>-->
                            <!--</li>-->
                        <!--</ul>-->
                    <!--</li>-->
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs"><?php echo $admin['name']; ?></span>
                        </a>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <?php if(!$_SESSION['admin']['store_id']): ?>
                    <li>
                        <?php if(isset($GLOBALS["new_framework"])): ?>
                            <a href="/web/index.php?c=user&a=logout" title="退出系统" style="font-size: 20px;">
                                <i class="fa fa-sign-out"></i>
                            </a>
                        <?php else: ?>
                            <a href="/web/index.php?c=home&a=welcome&do=system&" title="返回系统" style="font-size: 20px;">
                                <i class="fa fa-home"></i>
                            </a>
                        <?php endif; ?>
                        <!--<a href="/web/index.php?c=home&a=welcome&do=system&" title="返回系统" style="font-size: 20px;">-->
                            <!--<i class="fa fa-home"></i>-->
                        <!--</a>-->
                    </li>
                    <?php endif; if($_SESSION['admin']['store_id']): ?>
                    <li>
                        <a href="<?php echo url('user/logout'); ?>" title="退出" style="font-size: 20px;">
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <!-- <div class="user-panel">
              <div class="pull-left image">
                <img src="/addons/sqtg_sun/public/static/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
              </div>
              <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
              </div>
            </div> -->
            <!-- search form -->
            <!--   <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                        </button>
                      </span>
                </div>
              </form> -->
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <!--<ul class="sidebar-menu" data-widget="tree">-->
                <!--<li class="header">菜单</li>-->
                <!--<li class="treeview" data-menu="Dashboard">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-dashboard"></i> <span>Dashboard</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="index.html" target="blank"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>-->
                        <!--<li><a href="index2.html" target="blank"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="LayoutOptions">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-files-o"></i>-->
                        <!--<span>LayoutOptions</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<span class="label label-primary pull-right">4</span>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>-->
                        <!--<li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>-->
                        <!--<li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
                        <!--<li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<a href="pages/widgets.html" target="blank">-->
                        <!--<i class="fa fa-th"></i> <span>Widgets</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<small class="label pull-right bg-green">new</small>-->
                        <!--</span>-->
                    <!--</a>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="Charts">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-pie-chart"></i>-->
                        <!--<span>Charts</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/charts/chartjs.html" target="blank"><i class="fa fa-circle-o"></i> ChartJS</a></li>-->
                        <!--<li><a href="pages/charts/morris.html" target="blank"><i class="fa fa-circle-o"></i> Morris</a></li>-->
                        <!--<li><a href="pages/charts/flot.html" target="blank"><i class="fa fa-circle-o"></i> Flot</a></li>-->
                        <!--<li><a href="pages/charts/inline.html" target="blank"><i class="fa fa-circle-o"></i> Inline charts</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="UIElements">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-laptop"></i>-->
                        <!--<span>UIElements</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/UI/general.html" target="blank"><i class="fa fa-circle-o"></i> General</a></li>-->
                        <!--<li><a href="pages/UI/icons.html" target="blank"><i class="fa fa-circle-o"></i> Icons</a></li>-->
                        <!--<li><a href="pages/UI/buttons.html" target="blank"><i class="fa fa-circle-o"></i> Buttons</a></li>-->
                        <!--<li><a href="pages/UI/sliders.html" target="blank"><i class="fa fa-circle-o"></i> Sliders</a></li>-->
                        <!--<li><a href="pages/UI/timeline.html" target="blank"><i class="fa fa-circle-o"></i> Timeline</a></li>-->
                        <!--<li><a href="pages/UI/modals.html" target="blank"><i class="fa fa-circle-o"></i> Modals</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="Forms">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-edit"></i> <span>Forms</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/forms/general.html" target="blank"><i class="fa fa-circle-o"></i> General Elements</a></li>-->
                        <!--<li><a href="pages/forms/advanced.html" target="blank"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>-->
                        <!--<li><a href="pages/forms/editors.html" target="blank"><i class="fa fa-circle-o"></i> Editors</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="Tables">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-table"></i> <span>Tables</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/tables/simple.html" target="blank"><i class="fa fa-circle-o"></i> Simple tables</a></li>-->
                        <!--<li><a href="pages/tables/data.html" target="blank"><i class="fa fa-circle-o"></i> Data tables</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<a href="pages/calendar.html" target="blank">-->
                        <!--<i class="fa fa-calendar"></i> <span>Calendar</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<small class="label pull-right bg-red">3</small>-->
                          <!--<small class="label pull-right bg-blue">17</small>-->
                        <!--</span>-->
                    <!--</a>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<a href="pages/mailbox/mailbox.html" target="blank">-->
                        <!--<i class="fa fa-envelope"></i> <span>Mailbox</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<small class="label pull-right bg-yellow">12</small>-->
                          <!--<small class="label pull-right bg-green">16</small>-->
                          <!--<small class="label pull-right bg-red">5</small>-->
                        <!--</span>-->
                    <!--</a>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="Examples">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-folder"></i> <span>Examples</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="pages/examples/invoice.html" target="blank"><i class="fa fa-circle-o"></i> Invoice</a></li>-->
                        <!--<li><a href="pages/examples/profile.html" target="blank"><i class="fa fa-circle-o"></i> Profile</a></li>-->
                        <!--<li><a href="pages/examples/login.html" target="blank"><i class="fa fa-circle-o"></i> Login</a></li>-->
                        <!--<li><a href="pages/examples/register.html" target="blank"><i class="fa fa-circle-o"></i> Register</a></li>-->
                        <!--<li><a href="pages/examples/lockscreen.html" target="blank"><i class="fa fa-circle-o"></i> Lockscreen</a></li>-->
                        <!--<li><a href="pages/examples/404.html" target="blank"><i class="fa fa-circle-o"></i> 404 Error</a></li>-->
                        <!--<li><a href="pages/examples/500.html" target="blank"><i class="fa fa-circle-o"></i> 500 Error</a></li>-->
                        <!--<li><a href="pages/examples/blank.html" target="blank"><i class="fa fa-circle-o"></i> Blank Page</a></li>-->
                        <!--<li><a href="pages/examples/pace.html" target="blank"><i class="fa fa-circle-o"></i> Pace Page</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li class="treeview" data-menu="Multilevel">-->
                    <!--<a href="#">-->
                        <!--<i class="fa fa-share"></i> <span>Multilevel</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>-->
                        <!--<li class="treeview">-->
                            <!--<a href="#"><i class="fa fa-circle-o"></i> Level One-->
                                <!--<span class="pull-right-container">-->
                  <!--<i class="fa fa-angle-left pull-right"></i>-->
                <!--</span>-->
                            <!--</a>-->
                            <!--<ul class="treeview-menu">-->
                                <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>-->
                                <!--<li class="treeview">-->
                                    <!--<a href="#"><i class="fa fa-circle-o"></i> Level Two-->
                                        <!--<span class="pull-right-container">-->
                      <!--<i class="fa fa-angle-left pull-right"></i>-->
                    <!--</span>-->
                                    <!--</a>-->
                                    <!--<ul class="treeview-menu">-->
                                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                                    <!--</ul>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</li>-->
                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>-->
                <!--&lt;!&ndash; <li class="header">LABELS</li> &ndash;&gt;-->
                <!--<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->
            <!--</ul>-->
            <?php if(is_array($menugroup) || $menugroup instanceof \think\Collection || $menugroup instanceof \think\Paginator): $i = 0; $__LIST__ = $menugroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <ul class="sidebar-menu" style="display: none;" data-widget="tree" data-menu="<?php echo $vo['id']; ?>">
                <li class="header"><?php echo $vo['name']; ?></li>
                <?php if(is_array($vo['menus']) || $vo['menus'] instanceof \think\Collection || $vo['menus'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;$empty = array(); if($menu['menus'] == $empty): ?>
                <li><a href="<?php echo adminurl($menu['action'], $menu['control']); ?>" target="blank"><i class="<?php echo $menu['icon']; ?>"></i><span><?php echo $menu['name']; ?></span></a></li>
                <?php else: ?>
                <li class="treeview">
                    <a href="#"><i class="<?php echo $menu['icon']; ?>"></i> <span><?php echo $menu['name']; ?></span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if(is_array($menu['menus']) || $menu['menus'] instanceof \think\Collection || $menu['menus'] instanceof \think\Paginator): $i = 0; $__LIST__ = $menu['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu2): $mod = ($i % 2 );++$i;?>
                        <li><a href="<?php echo adminurl($menu2['action'], $menu2['control']); ?>" target="blank"><i class="fa fa-circle-o"></i><span><?php echo $menu2['name']; ?></span></a></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Multilevel">-->
                <!--<li class="header">Multilevel</li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o"></i>  <span>Level One</span></a></li>-->
                <!--<li class="treeview">-->
                    <!--<a href="#"><i class="fa fa-circle-o"></i> <span>Level One</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>-->
                        <!--<li class="treeview">-->
                            <!--<a href="#"><i class="fa fa-circle-o"></i> Level Two-->
                                <!--<span class="pull-right-container">-->
                                  <!--<i class="fa fa-angle-left pull-right"></i>-->
                                <!--</span>-->
                            <!--</a>-->
                            <!--<ul class="treeview-menu">-->
                                <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                                <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                            <!--</ul>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o"></i> <span>Level One</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Dashboard">-->
                <!--<li class="header">Dashboard</li>-->
                <!--<li><a href="index.html" target="blank"><i class="fa fa-circle-o"></i><span>Dashboard v1</span></a></li>-->
                <!--<li><a href="index2.html" target="blank"><i class="fa fa-circle-o"></i><span>Dashboard v2</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="LayoutOptions">-->
                <!--<li class="header">LayoutOptions</li>-->
                <!--<li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i><span>Top Navigation</span></a></li>-->
                <!--<li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> <span>Boxed</span></a></li>-->
                <!--<li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> <span>Fixed</span></a></li>-->
                <!--<li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> <span>Collapsed Sidebar</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Charts">-->
                <!--<li class="header">Charts</li>-->
                <!--<li><a href="pages/charts/chartjs.html" target="blank"><i class="fa fa-circle-o"></i>  <span>ChartJS</span></a></li>-->
                <!--<li><a href="pages/charts/morris.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Morris</span></a></li>-->
                <!--<li><a href="pages/charts/flot.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Flot</span></a></li>-->
                <!--<li><a href="pages/charts/inline.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Inline charts</span> </a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="UIElements">-->
                <!--<li class="header">UIElements</li>-->
                <!--<li><a href="pages/UI/general.html" target="blank"><i class="fa fa-circle-o"></i>  <span>General</span></a></li>-->
                <!--<li><a href="pages/UI/icons.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Icons</span></a></li>-->
                <!--<li><a href="pages/UI/buttons.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Buttons</span></a></li>-->
                <!--<li><a href="pages/UI/sliders.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Sliders</span></a></li>-->
                <!--<li><a href="pages/UI/timeline.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Timeline</span></a></li>-->
                <!--<li><a href="pages/UI/modals.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Modals</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Forms">-->
                <!--<li class="header">Forms</li>-->
                <!--<li><a href="pages/forms/general.html" target="blank"><i class="fa fa-circle-o"></i>  <span>General Elements</span></a></li>-->
                <!--<li><a href="pages/forms/advanced.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Advanced Elements</span></a></li>-->
                <!--<li><a href="pages/forms/editors.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Editors</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Tables">-->
                <!--<li class="header">Tables</li>-->
                <!--<li><a href="pages/tables/simple.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Simple tables</span></a></li>-->
                <!--<li><a href="pages/tables/data.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Data tables</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Examples">-->
                <!--<li class="header">Examples</li>-->
                <!--<li><a href="pages/examples/invoice.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Invoice</span></a></li>-->
                <!--<li><a href="pages/examples/profile.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Profile</span></a></li>-->
                <!--<li><a href="pages/examples/login.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Login</span></a></li>-->
                <!--<li><a href="pages/examples/register.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Register</span></a></li>-->
                <!--<li><a href="pages/examples/lockscreen.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Lockscreen</span></a></li>-->
                <!--<li><a href="pages/examples/404.html" target="blank"><i class="fa fa-circle-o"></i>  <span>404 Error</span></a></li>-->
                <!--<li><a href="pages/examples/500.html" target="blank"><i class="fa fa-circle-o"></i>  <span>500 Error</span></a></li>-->
                <!--<li><a href="pages/examples/blank.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Blank Page</span></a></li>-->
                <!--<li><a href="pages/examples/pace.html" target="blank"><i class="fa fa-circle-o"></i>  <span>Pace Page</span></a></li>-->
            <!--</ul>-->
            <!--<ul class="sidebar-menu" data-widget="tree" data-menu="Multilevel">-->
                <!--<li class="header">Multilevel</li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o"></i>  <span>Level One</span></a></li>-->
                <!--<li class="treeview">-->
                    <!--<a href="#"><i class="fa fa-circle-o"></i> <span>Level One</span>-->
                        <!--<span class="pull-right-container">-->
                          <!--<i class="fa fa-angle-left pull-right"></i>-->
                        <!--</span>-->
                    <!--</a>-->
                    <!--<ul class="treeview-menu">-->
                        <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>-->
                        <!--<li class="treeview">-->
                            <!--<a href="#"><i class="fa fa-circle-o"></i> Level Two-->
                                <!--<span class="pull-right-container">-->
                                  <!--<i class="fa fa-angle-left pull-right"></i>-->
                                <!--</span>-->
                            <!--</a>-->
                            <!--<ul class="treeview-menu">-->
                                <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                                <!--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>-->
                            <!--</ul>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</li>-->
                <!--<li><a href="#"><i class="fa fa-circle-o"></i> <span>Level One</span></a></li>-->
            <!--</ul>-->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper tabs-area">
        <div class="tabs-area-top">
            <a href="#" class="scroll-left"><</a>
            <ul class="nav nav-tabs myscroll" role="tablist">

                <!--<li class="active">-->
                    <!--<a href="#2f77656971696e672f6164646f6e732f637973635f73756e2f7075626c69632f61646d696e2e7068702f636d656e7567726f75702f696e6465782e68746d6c" data-previd="undefined" data-toggle="tab" aria-expanded="true">-->
                        <!--分组列表-->
                        <!--<button type="button" class="close">-->
                            <!--<span aria-hidden="true">×</span>-->
                        <!--</button>-->
                    <!--</a>-->
                <!--</li>-->
                <li class="active">
                    <a href="#2f77656971696e672f6164646f6e732f637973635f73756e2f7075626c69632f61646d696e2e7068702f636d656e7567726f75702f696e6465782e68746d6c" data-previd="undefined" data-toggle="tab" aria-expanded="true">
                        首页
                    </a>
                </li>
            </ul>
            <a href="#" class="scroll-right">></a>
            <div class="dropdown">
                <a class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 10px 20px;background: #fff;border: 0;border-left: 1px solid #ddd;border-radius: 0;">
                    操作
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu .dropdown-menu-right" style="min-width: 0;right: 0;left: inherit;" aria-labelledby="dropdownMenu1">
                    <li id="closeCurr"><a href="#">关闭当前</a></li>
                    <li id="closeAll"><a href="#">关闭全部</a></li>
                    <li role="separator" class="divider"></li>
                    <li id="refreshCurr"><a href="#">刷新当前</a></li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <!--<div class="tab-pane active" id="2f77656971696e672f6164646f6e732f637973635f73756e2f7075626c69632f61646d696e2e7068702f636d656e7567726f75702f696e6465782e68746d6c">-->
                <!--<iframe src="<?php echo adminurl('index','cmenugroup'); ?>"></iframe>-->
            <!--</div>-->
            <div class="tab-pane active" id="2f77656971696e672f6164646f6e732f637973635f73756e2f7075626c69632f61646d696e2e7068702f636d656e7567726f75702f696e6465782e68746d6c">
                <iframe src="<?php echo adminurl('home'); ?>"></iframe>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content myscroll">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <!--<h3 class="control-sidebar-heading">Recent Activity</h3>-->
                <!--<ul class="control-sidebar-menu">-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<i class="menu-icon fa fa-birthday-cake bg-red"></i>-->

                            <!--<div class="menu-info">-->
                                <!--<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>-->

                                <!--<p>Will be 23 on April 24th</p>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<i class="menu-icon fa fa-user bg-yellow"></i>-->

                            <!--<div class="menu-info">-->
                                <!--<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>-->

                                <!--<p>New phone +1(800)555-1234</p>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>-->

                            <!--<div class="menu-info">-->
                                <!--<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>-->

                                <!--<p>nora@example.com</p>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<i class="menu-icon fa fa-file-code-o bg-green"></i>-->

                            <!--<div class="menu-info">-->
                                <!--<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>-->

                                <!--<p>Execution time 5 seconds</p>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                <!--</ul>-->
                <!--&lt;!&ndash; /.control-sidebar-menu &ndash;&gt;-->

                <!--<h3 class="control-sidebar-heading">Tasks Progress</h3>-->
                <!--<ul class="control-sidebar-menu">-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<h4 class="control-sidebar-subheading">-->
                                <!--Custom Template Design-->
                                <!--<span class="label label-danger pull-right">70%</span>-->
                            <!--</h4>-->

                            <!--<div class="progress progress-xxs">-->
                                <!--<div class="progress-bar progress-bar-danger" style="width: 70%"></div>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<h4 class="control-sidebar-subheading">-->
                                <!--Update Resume-->
                                <!--<span class="label label-success pull-right">95%</span>-->
                            <!--</h4>-->

                            <!--<div class="progress progress-xxs">-->
                                <!--<div class="progress-bar progress-bar-success" style="width: 95%"></div>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<h4 class="control-sidebar-subheading">-->
                                <!--Laravel Integration-->
                                <!--<span class="label label-warning pull-right">50%</span>-->
                            <!--</h4>-->

                            <!--<div class="progress progress-xxs">-->
                                <!--<div class="progress-bar progress-bar-warning" style="width: 50%"></div>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a href="javascript:void(0)">-->
                            <!--<h4 class="control-sidebar-subheading">-->
                                <!--Back End Framework-->
                                <!--<span class="label label-primary pull-right">68%</span>-->
                            <!--</h4>-->

                            <!--<div class="progress progress-xxs">-->
                                <!--<div class="progress-bar progress-bar-primary" style="width: 68%"></div>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->
                <!--</ul>-->
                <!--&lt;!&ndash; /.control-sidebar-menu &ndash;&gt;-->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <!--<form method="post">-->
                    <!--<h3 class="control-sidebar-heading">General Settings</h3>-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Report panel usage-->
                            <!--<input type="checkbox" class="pull-right" checked>-->
                        <!--</label>-->

                        <!--<p>-->
                            <!--Some information about this general settings option-->
                        <!--</p>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Allow mail redirect-->
                            <!--<input type="checkbox" class="pull-right" checked>-->
                        <!--</label>-->

                        <!--<p>-->
                            <!--Other sets of options are available-->
                        <!--</p>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Expose author name in posts-->
                            <!--<input type="checkbox" class="pull-right" checked>-->
                        <!--</label>-->

                        <!--<p>-->
                            <!--Allow the user to show his name in blog posts-->
                        <!--</p>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->

                    <!--<h3 class="control-sidebar-heading">Chat Settings</h3>-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Show me as online-->
                            <!--<input type="checkbox" class="pull-right" checked>-->
                        <!--</label>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Turn off notifications-->
                            <!--<input type="checkbox" class="pull-right">-->
                        <!--</label>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->

                    <!--<div class="form-group">-->
                        <!--<label class="control-sidebar-subheading">-->
                            <!--Delete chat history-->
                            <!--<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>-->
                        <!--</label>-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /.form-group &ndash;&gt;-->
                <!--</form>-->
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/addons/sqtg_sun/public/static/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/addons/sqtg_sun/public/static/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/addons/sqtg_sun/public/static/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/addons/sqtg_sun/public/static/dist/js/demo.js"></script>
<script src="/addons/sqtg_sun/public/static/custom/tabs-ext.js"></script>
<script type="text/javascript">
    $('.navbar.navbar-static-top>.navbar-nav.myscroll>li').click(function(){
        var menu = $(this).data('menu');
        $(this).addClass('active').siblings('.active').removeClass('active');
        $('.sidebar-menu[data-menu='+menu+']').show().siblings().hide();
    })
    $('.navbar.navbar-static-top>.navbar-nav.myscroll>li:eq(0)').click();
    $('.sidebar-menu li').click(function(){
        $(this).addClass('active').siblings('.active').removeClass('active').removeClass('menu-open');
    })
    $('#closeCurr').on('click',function () {
        $('.tabs-area-top>ul>li.active>a>.close').click();
    })
    $('#closeAll').on('click',function () {
        $('.tabs-area-top>ul>li>a>.close').click();
    })
    $('#refreshCurr').on('click',function () {
        var $a = $('.tabs-area-top>ul>li.active>a');

        var id = $a.attr('href');
        var iframe = $('iframe',id)[0];
        iframe.contentWindow.location.reload();
    })
</script>
</body>
</html>
