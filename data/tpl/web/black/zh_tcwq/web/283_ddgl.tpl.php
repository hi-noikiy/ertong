<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
  <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
  <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>all));?>">全部订单</a></li>
  <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>wait,'status'=>1));?>">待支付</a></li>
   <li   <?php  if($type=='pay') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>pay,'status'=>2));?>">待发货</a></li>
  <li   <?php  if($type=='cancel') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>cancel,'status'=>3));?>">待收货</a></li>
  <li   <?php  if($type=='complete') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>complete,'status'=>4));?>">已完成</a></li>
    <li   <?php  if($type=='refund') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>refund,'status'=>5));?>">待退款</a></li>
    <li   <?php  if($type=='completerefund') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>completerefund,'status'=>6));?>">已退款</a></li>
    <li   <?php  if($type=='reject') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('ddgl',array('type'=>reject,'status'=>7));?>">退款拒绝</a></li>
</ul>

  <div class="row ygrow">
      <div class="col-lg-12">
          <form action="" method="get" class="col-lg-4">
          <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="ddgl" />
              <div class="input-group" style="width: 350px">
                  <input type="text" name="keywords" class="form-control" value="<?php  echo $_GPC['keywords'];?>" placeholder="请输入姓名/订单号">
                  <span class="input-group-btn">
                     <input type="submit" class="btn btn-default" name="submit" value="查找"/>
                  </span>
              </div>
              <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
          </form>
           <form action="" method="get" class="col-md-4">
           <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="ddgl" />
            <div class="input-group" style="width: 100px">
                <?php  echo tpl_form_field_daterange('time',$_GPC['time']);?>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit2" value="查找"/>
                     <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                </span>
            </div><!-- /input-group -->
        </form>
          <!-- <form class="col-lg-2" action="" method="POST">
            <div style="width: 100px">
          
              <input type="submit" class="btn btn-sm btn-success" name="export_submit" value="导出"/>
              <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
            
            </div>
                  </form> -->
      </div>    
  </div>
<div class="main">
    <div class="panel panel-default">
      <div class="panel-heading">全部订单</div>
        <div class="table-responsive">
          <table class="col-md-12">
              <tr class="yg5_tr1">
                <th class="store_td1 col-md-1">订单号</th>
                <th class="col-md-1">商家名称</th>
                <th class="col-md-1">买家</th>
                <th class="col-md-1">联系电话</th>
                <th class="col-md-2">收货地址</th> 
                 <th class="col-md-1">商品名称</th>
               <!--    <th>商品规格</th> -->
                  <th class="col-md-1">商品价格</th>
                  <th class="col-md-1">是否自提</th>
                <th class="col-md-1">状态</th>
               
                  <th class="col-md-2">操作</th>
              </tr>
              <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
              <tr class="yg5_tr2">
                <td>
                <?php  echo $item['order_num'];?>
                </td>
                 <td >
                <?php  echo $item['seller_name'];?>
                </td>      
                <td>
                <?php  echo $item['user_name'];?>
                </td>
                <td>
                <?php  echo $item['tel'];?>
                </td>
                 <td >
               <?php  echo $item['address'];?>
                </td>
                 <td >
              <?php  echo $item['good_name'];?>
                </td>
              <!--    <td >
                             <?php  echo $item['good_spec'];?>
                              </td> -->
                  <td >
               <?php  echo $item['money'];?>
                </td>
              <td >
               <?php  if($item['is_zt']==1) { ?><span class="label storered">是|<?php  echo $item['zt_time'];?></span><?php  } else { ?><span class="label storegrey">不是</span><?php  } ?>
                </td>
                <?php  if($item['state']==1) { ?>
                <td >
                <span class="label storered">待付款</span>
                </td>
                <?php  } else if($item['state']==2) { ?>
                 <td >
                 <span class="label storered"> 待发货</span>
                </td>
                <?php  } else if($item['state']==3) { ?>
                 <td >
                 <span class="label storered">待收货</span>
                
                </td>
                <?php  } else if($item['state']==4) { ?>
                 <td >
                 <span class="label storeblue">已完成</span>
                </td>
                 <?php  } else if($item['state']==5) { ?>
                 <td >
                 <span class="label storeblue">待退款</span>
                </td>
                  <?php  } else if($item['state']==6) { ?>
                 <td >
                 <span class="label storegrey">已退款</span>
                </td>
                  <?php  } else if($item['state']==7) { ?>
                 <td >
                 <span class="label storegrey">退款拒绝</span>
                </td>
                 <?php  } ?> 
                 <td>
                 <a href="<?php  echo $this->createWebUrl('orderinfo',array('id'=>$item['id']));?>" class="storespan btn btn-xs">
                      <span class="fa fa-pencil"></span>
                      <span class="bianji">编辑
                          <span class="arrowdown"></span>
                      </span>                            
                  </a>
                  <a class="storespan btn btn-xs" href="<?php  echo $this->createWebUrl('ddgl', array('id'=>$item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;">
                      <span class="fa fa-trash-o"></span>
                      <span class="bianji">删除
                          <span class="arrowdown"></span>
                      </span>
                  </a>
                 <!-- <a href="<?php  echo $this->createWebUrl('orderinfo',array('id'=>$item['id']));?>"><button class="btn btn-success btn-xs">查看</button></a>
                <a class="btn btn-danger btn-xs" href="<?php  echo $this->createWebUrl('ddgl', array('id'=>$item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a> -->
                <?php  if($item['state']==2) { ?>
                 <a href="<?php  echo $this->createWebUrl('ddgl',array('id'=>$item['id'],'op'=>'delivery'));?>"><button class="btn ygshouqian2 btn-xs">确认发货</button></a>
                 <?php  } ?>
                  <?php  if($item['state']==3) { ?>
                 <a href="<?php  echo $this->createWebUrl('ddgl',array('id'=>$item['id'],'op'=>'receipt'));?>"><button class="btn ygshouqian2 btn-xs">确认收货</button></a>
                 <?php  } ?>
                  <?php  if($item['state']==5) { ?>
                 <a href="<?php  echo $this->createWebUrl('ddgl',array('id'=>$item['id'],'op'=>'refund'));?>"><button class="btn ygshouqian2 btn-xs">确认退款</button></a>
                 <a href="<?php  echo $this->createWebUrl('ddgl',array('id'=>$item['id'],'op'=>'jj'));?>"><button class="btn ygshouqian2 btn-xs">拒绝退款</button></a>
                 <?php  } ?>
                </td>
               <!--  <td> <?php  echo $pager;?></td> -->
              </tr>
              <?php  } } ?>
             
                <?php  if(empty($list)) { ?>
               <tr>
                  <td colspan="12" style="padding: 10px 30px;">
                    暂无订单信息
                  </td>
                </tr>
             
              <?php  } ?>
          </table>
        </div>
    </div>
</div>
<div class="text-right we7-margin-top"><?php  echo $pager;?></div>
<script type="text/javascript">
    $(function(){
        $("#frame-7").show();
        $("#yframe-7").addClass("wyactive");
    })
</script>