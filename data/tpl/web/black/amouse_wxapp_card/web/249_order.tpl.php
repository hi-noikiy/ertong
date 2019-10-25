<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
    .clearfix .row{
        margin-left: -15px;
        margin-right: -15px;
    }
</style>
<style>
    .label-red {
        background-color: red;
    }
</style>
<?php  if($operation == 'display') { ?>
<div class="main">
    <div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" method="get" class="form-horizontal" role="form">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="m" value="amouse_wxapp_card" />
                <input type="hidden" name="do" value="orders" />
                <div class="form-group">

                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">订单状态</label>
                    <div class="col-sm-2 col-lg-2">
                        <select name="status" class="form-control">
                            <option value="" <?php  if(!isset($_GPC['status'])||$_GPC['status']=='') { ?>selected<?php  } ?>>全部</option>
                            <option value="0" <?php  if(isset($_GPC['status'])&&$_GPC['status']==0) { ?>selected<?php  } ?>>待付款</option>
                            <option value="1" <?php  if($_GPC['status']==1) { ?>selected<?php  } ?>>已付款</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-lg-2">
                        <button class="btn btn-default">
                            <i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel panel-default">
        <form id="form2" class="form-horizontal" method="post">
            <div class="table-responsive panel-body">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;">全选</th>
                        <th>信息</th>
                        <th>订单详情</th>
                        <th>总价</th>
                        <th>状态</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="row-first">
                            <input type="checkbox"  onclick="var ck = this.checked;$(':checkbox').each(function(){this.checked = ck});" name=''></td>
                        <td colspan="8" style="text-align:left;">
                            <input name="token" type="hidden" value="<?php  echo $_W['token'];?>"/>
                            <input type="submit" class="btn btn-primary" name="submit1" value="批量删除"/>
                        </td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td> <input type="checkbox" value="<?php  echo $item['id'];?>" name="delete[]">  </td>
                        <td>
                            <label class='label label-primary'>订单号:<?php  echo $item['ordersn'];?> </label> <br/>
                            <label class='label label-default'><?php  echo $item['openid'];?></label> <br/>
                            <label class='label label-primary'><?php  echo $item['cid'];?>--<?php  echo $item['username'];?>--<?php  echo $item['mobile'];?></label> <br/>
                            <label class='label label-info'>下单时间:<?php  echo date('Y-m-d H:i:s', $item['createtime'])?></label>
                        </td>
                        <td style="text-align:left;">
                            <?php  if($item['paytype']==1) { ?><span class="label label-red">置顶【置顶天数：<?php  echo $item['top_day'];?>】</span>
                            <?php  } else if($item['paytype']==6) { ?> <span class="label label-red">代理</span>
                            <?php  } ?>
                        </td>
                        <td><?php  echo $item['price'];?>元</td>
                        <td>
                            <?php  if($item['status'] == 0) { ?><span class="label label-info">已下单</span>
                            <?php  } else if($item['status'] ==1) { ?><span class="label label-success">已付款</span>
                            <?php  } ?>
                        </td>
                        <td style="text-align:right;">
                            <a href="<?php  echo $this->createWebUrl('orders', array('op' => 'detail','id' => $item['id']))?>" title="详情" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="详情" >
                                <i class="fa fa-eye"></i>
                            </a>
                            <a onclick="return confirm('删除订单无法恢复，确认吗？');return false;"
                               href="<?php  echo $this->createWebUrl('orders', array('op' => 'delete', 'id' => $item['id']))?>" class="btn btn-default btn-sm"> <i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
                <?php  echo $pager;?>
            </div>
        </form>
    </div>
</div>
<?php  } else if($operation == 'detail') { ?>
<div class="main">
    <form class="form-horizontal form" action="" method="post" enctype="multipart/form-data"  onsubmit="return formcheck(this)">
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                订单信息
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">订单号</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  echo $item['ordersn'];?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">名片信息</label>
                    <div class="col-sm-8 col-xs-12">
                        <span class="label label-default"><?php  echo $card['id'];?></span>
                         <?php  echo $card['username'];?>-<?php  echo $card['mobile'];?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">订单类型</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  if($item['paytype']==1) { ?><span class="label label-red">置顶天数：<?php  echo $item['top_day'];?> </span>

                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">订单总价</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  echo $item['price'];?> 元
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">订单状态</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  if($item['status'] == 0) { ?><span class="label label-info">已下单待付款</span><?php  } ?>
                        <?php  if($item['status'] == 1) { ?><span class="label label-info">已付款</span><?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">下单日期</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  echo date('Y-m-d H:i:s', $item['createtime'])?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php  } ?>