<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<style>
.yg5_tr2>td{padding: 10px 3px;border: 1px solid #e5e5e5;text-align: center;}
</style>
<ul class="nav nav-tabs">
  <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
  <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>all));?>">全部</a></li>

  <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>wait,'state'=>1));?>">待提现</a></li>

  <li   <?php  if($type=='now') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>now,'state'=>2));?>">提现通过</a></li>

  <li   <?php  if($type=='delivery') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>delivery,'state'=>3));?>">提现拒绝</a></li>

</ul>



<div class="row ygrow">

    <div class="col-lg-12">

        <form action="" method="get" class="col-md-6">
        <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="txlist" />

            <div class="input-group" style="width: 300px">

                <input type="text" name="keywords" class="form-control" value="<?php  echo $_GPC['keywords'];?>" placeholder="请输入姓名">

                <span class="input-group-btn">

                    <input type="submit" class="btn btn-default" name="submit" value="查找"/>

                </span>

            </div>

            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>

        </form>

        

    </div><!-- /.col-lg-6 -->

</div>  

<div class="main">

    <div class="panel panel-default">

        <div class="panel-heading">

            审核管理

        </div>

        <div class="panel-body" style="padding: 0px 15px;">

            <div class="row">

                <table class="yg5_tabel col-md-12">

                    <tr class="yg5_tr1">

                        <td class="store_td1 col-md-1 td_class_0">真实姓名</td>
                        <td class="col-md-1">提现金额</td>
                        <td class="col-md-1">实际金额</td> 
                        <td class="col-md-1">提现方式</td>
                        <td class="col-md-1">提现账号</td>
                        <td class="col-md-1">提现类型</td>
                        <td class="col-md-1">申请时间</td>
                        <td class="col-md-1">提现时间</td>
                        <td class="col-md-1">提现状态</td>
                        <td class="col-md-1">操作</td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                      <td class="store_td1"><?php  echo $item['name'];?></td>
                      <td><?php  echo $item['tx_cost'];?></td>
                      <td><?php  echo $item['sj_cost'];?></td>
                      <?php  if($item['type']==1) { ?>
                      <td>
                         支付宝
                     </td>
                     <?php  } else if($item['type']==2) { ?>
                     <td>
                         微信
                     </td>
                     <?php  } else if($item['type']==3) { ?>
                     <td>
                         银联
                     </td>
                     <?php  } ?>
                     <td><?php  echo $item['username'];?><br><?php  echo $item['bank'];?></td>
                     <?php  if($item['method']==1) { ?>
                      <td>
                         <span class="label label-danger">红包</span>
                     </td>
                     <?php  } else if($item['method']==2) { ?>
                     <td>
                     <?php  $res=pdo_get('zhtc_store',array('id'=>$item['store_id']))?>
                         <span class="label label-warning">商家余额</span><br><br>
                         <span class="label label-info"><?php  echo $res['store_name'];?></span>
                     </td>
                     <?php  } else if($item['method']==3) { ?>
                     <td>
                         <span class="label label-success">佣金</span>
                     </td>
                     <?php  } ?>
                     <td><?php  echo date("Y-m-d H:i:s",$item['time'])?></td>
                      <td><?php  if($item['sh_time']) { ?><?php  echo date("Y-m-d H:i:s",$item['sh_time'])?><?php  } ?></td>


                     <?php  if($item['state']==1) { ?>
                     <td >
                        <span class="label storered">待审核</span>
                    </td >
                    <?php  } else if($item['state']==2) { ?>
                    <td >
                        <span class="label storeblue">已提现</span>
                    </td>
                    <?php  } else if($item['state']==3) { ?>
                    <td >
                       <span class="label storegrey">已拒绝</span>
                   </td>

                   <?php  } ?>  
                   <td>
                      <?php  if($sys['tx_mode']==2&&$item['type']==2&&$item['state']==1) { ?>
                      <a class="btn ygyouhui2 btn-xs" href="<?php  echo $this->createWebUrl('txlist',array('id'=>$item['id'],'op'=>'adopt'))?>" >微信打款</a>
                      <?php  } ?>
                      <?php  if($sys['tx_mode']==1&&$item['state']==1 or $item['type']!=2) { ?>
                      <a class="btn ygyouhui2 btn-xs" href="<?php  echo $this->createWebUrl('txlist',array('id'=>$item['id'],'op'=>'adopt'))?>" >线下打款</a>
                      <?php  } ?>
                      <?php  if($item['state']==1) { ?>
                      <a class="btn storegrey2 btn-xs" href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'reject'))?>" title="拒绝">拒绝</a>
                      <?php  } ?>
                      
                      <!-- <a class="btn btn-danger btn-xs" href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a> -->

                      <a class="storespan btn btn-xs" href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;">
                          <span class="fa fa-trash-o"></span>
                          <span class="bianji">删除
                              <span class="arrowdown"></span>
                          </span>
                      </a>
                  </td>

              </td>

          </tr>

          <?php  } } ?>
          <?php  if(empty($list)) { ?>
          <tr class="yg5_tr2">
            <td colspan="12">
              暂无提现信息
          </td>
      </tr> 
      <?php  } ?>        



  </table>

</div>

</div>

</div>

</div>

<div class="text-right we7-margin-top">

   <?php  echo $pager;?>

</div>
<script type="text/javascript">
    $(function(){
        $("#frame-16").show();
        $("#yframe-16").addClass("wyactive");
    })
</script>