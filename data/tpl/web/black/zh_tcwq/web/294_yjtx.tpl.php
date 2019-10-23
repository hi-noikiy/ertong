<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
  <span class="ygxian"></span>
  <div class="ygdangq">当前位置:</div>
  <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('yjtx',array('type'=>all));?>">全部</a></li>

  <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('yjtx',array('type'=>wait,'status'=>1));?>">待提现</a></li>

  <li   <?php  if($type=='now') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('yjtx',array('type'=>now,'status'=>2));?>">提现通过</a></li>

  <li   <?php  if($type=='delivery') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('yjtx',array('type'=>delivery,'status'=>3));?>">提现拒绝</a></li>

</ul>



<div class="row ygrow">

    <div class="col-lg-12">

        <form action="" method="get" class="col-md-6">
        <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="yjtx" />

            <div class="input-group" style="width: 300px">

                <input type="text" name="keywords" class="form-control" value="<?php  echo $_GPC['keywords'];?>" placeholder="请输入城市名称">

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

                        <td class="store_td1">管理员名称</td>
                        <td>所属城市</td>
                        <td>提现金额</td>
                        <td>姓名</td> 
                        <td>账号</td> 
                        <td>提现方式</td>
                        <td >提现时间</td>
                        <td >审核状态</td>
                        <td>操作</td>

                        <!-- <td class="store_td1">酒店名</td>
                        <td class="col-md-1">星级</td>
                        <td class="col-md-1">地区</td>
                        <td class="col-md-1">具体地址</td>
                        <td class="col-md-1">酒店电话</td>
                        <td class="col-md-2">成立日期</td>
                        <td class="col-md-1">营业执照</td>
                        <td class="col-md-1">联系人</td>
                        <td class="col-md-1">审核状态</td>
                        <td class="col-md-1">手机</td> -->

                    </tr>
                      <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                      <td class="store_td1"><?php  echo $item['username'];?></td>
                         <td><?php  echo $item['cityname'];?></td>
                        <td><?php  echo $item['tx_cost'];?></td>
                         <td><?php  echo $item['name'];?></td>
                        <td><?php  echo $item['account'];?></td>
                       <?php  if($item['tx_type']==1) { ?>
                <td>
               支付宝
                </td>
                 <?php  } else if($item['tx_type']==2) { ?>
                 <td>
               微信
                </td>
                 <?php  } else if($item['tx_type']==3) { ?>
                 <td>
               银联
                </td>
                <?php  } ?>
                        <td><?php  echo $item['cerated_time'];?></td>
                       
                    
                        <?php  if($item['status']==1) { ?>
                <td >
                <span class="label storered">待确认</span>
                </td >
                <?php  } else if($item['status']==2) { ?>
                <td >
                <span class="label storeblue">已提现</span>
                </td>
                <?php  } else if($item['status']==3) { ?>
                 <td >
                 <span class="label storegrey">已拒绝</span>
                </td>
               
                 <?php  } ?>  
                    <td>
                          <?php  if($item['status']==1) { ?>
                     <a class="btn storeblue btn-xs" href="<?php  echo $this->createWebUrl('yjtx',array('id'=>$item['id'],'op'=>'adopt'))?>" >通过</a>
                         <a class="btn storegrey btn-xs" href="<?php  echo $this->createWebUrl('yjtx', array('id' => $item['id'],'op'=>'reject'))?>" title="拒绝">拒绝</a>
                         <?php  } ?>
                      <!--    <a class="btn btn-success btn-sm" href="<?php  echo $this->createWebUrl('txinfo',array('id'=>$item['id'],'op'=>'info'))?>">查看</a> -->
                        <a href="<?php  echo $this->createWebUrl('yjtx', array('id' => $item['id'],'op'=>'delete'))?>" class="storespan btn btn-xs" onclick="return confirm('确认删除吗？');return false;">
                            <span class="fa fa-trash-o"></span>
                            <span class="bianji">删除
                                <span class="arrowdown"></span>
                            </span>
                        </a>
                        <!-- <a class="btn btn-danger btn-sm" href="<?php  echo $this->createWebUrl('yjtx', array('id' => $item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a> -->

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
        $("#frame-15").show();
        $("#yframe-15").addClass("wyactive");
    })
</script>