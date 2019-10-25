<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcsslist.css">
<style type="text/css">
    .yg5_key>div{float: left;line-height: 34px;}

    .store_td1{height: 45px;}

    .store_list_img{width: 60px;height: 60px;}

    .yg5_tabel{border-color: #e5e5e5;outline: 1px solid #e5e5e5;text-align: center;}

    .yg5_tr2>td{padding: 15px;border: 1px solid #e5e5e5;}

    .yg5_tr1>td{

        border: 1px solid #e5e5e5;
        background-color: #FAFAFA;

        font-weight: bold;

    }

    .yg5_btn{background-color: #EEEEEE;color: #333;border: 1px solid #E4E4E4;border-radius: 6px;width: 100px;height: 34px;}
    .check_img{width: 45px;height: 45px;}
    .ygrow{margin-top: 20px;}
    .yghuise{background-color: grey;color: white;}
    .yghuise:hover{background-color: grey;color: white;

    }
    .pa_0{
      padding: 10px 0;
    }
</style>


<ul class="nav nav-tabs">
  <span class="ygxian"></span>
  <div class="ygdangq">当前位置:</div>

  <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>wait,'state'=>1));?>">待提现</a></li>

  <li   <?php  if($type=='now') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>now,'state'=>2));?>">提现通过</a></li>

  <li   <?php  if($type=='delivery') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>delivery,'state'=>3));?>">提现拒绝</a></li>
  <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('txlist',array('type'=>all));?>">全部</a></li>

</ul>



<div class="row ygrow">

    <div class="col-lg-12">

        <form action="" method="get" class="col-md-6">
            <input type="hidden" name="c" value="site" />
                   <input type="hidden" name="a" value="entry" />
                   <input type="hidden" name="m" value="zh_cjdianc" />
                   <input type="hidden" name="do" value="txlist" />
            <div class="input-group" style="width: 300px">

                <input type="text" name="keywords" class="form-control" placeholder="请输入门店名称">

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

                        <td class="store_td1">门店名称</td>
                        <td>提现金额</td>
                        <!-- <td>实际金额</td>  -->
                        <td>提现方式</td>
                        <td>收款人</td>
                        <td>提现时间</td>
                        <td>提现状态</td>
                        <td>操作</td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                      <td class="store_td1"><?php  echo $item['md_name'];?></td>
                      <td><?php  echo $item['tx_cost'];?></td>
                      <!-- <td><?php  echo $item['sj_cost'];?></td> -->
                      <?php  if($item['type']==1) { ?>
                      <td>
                         微信
                     </td>
                     <?php  } else if($item['type']==2) { ?>
                     <td>
                         银联
                     </td>

                     <?php  } ?>
                     <td><?php  echo $item['sk_name'];?></td>
                     <td><?php  echo $item['time'];?></td>


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
                       <span class="label yghuise">已拒绝</span>
                   </td>

                   <?php  } ?>
                   <td>
        <!--               <?php  if($item['state']==1&&$item['type']==2) { ?>
                        <a href="javascript:;" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">
                          <span class="fa fa-pencil-square-o" style="margin-right: 0"></span>
                          <span class="bianji">查看
                              <span class="arrowdown"></span>
                          </span>
                      </a>
                      <?php  } ?> -->
                       <a href="javascript:;" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">
                          <span class="fa fa-pencil-square-o" style="margin-right: 0"></span>
                          <span class="bianji">查看
                              <span class="arrowdown"></span>
                          </span>
                      </a>
                      <?php  if($item['state']==1) { ?>

                      <a class="btn btn-info btn-xs" href="<?php  echo $this->createWebUrl('txlist',array('id'=>$item['id'],'op'=>'adopt2'))?>" >微信打款</a>

                      <a class="btn ygyouhui2 btn-xs" href="<?php  echo $this->createWebUrl('txlist',array('id'=>$item['id'],'op'=>'adopt'))?>" >线下打款</a>
                      <a class="btn ygshouqian2 btn-xs" href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'reject'))?>" title="拒绝">拒绝</a>
                      <?php  } ?>
                   <!--    <a href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'delete'))?>" class="storespan btn btn-xs" onclick="return confirm('确认删除吗？');return false;">
                          <span class="fa fa-trash-o"></span>
                          <span class="bianji">删除
                              <span class="arrowdown"></span>
                          </span>
                      </a> -->
                      <!-- <a class="btn btn-danger btn-xs" href="<?php  echo $this->createWebUrl('txlist', array('id' => $item['id'],'op'=>'delete'))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a> -->

                  </td>

              </td>

          </tr>
             <!-- 修改信息弹框（Modal） -->
      <div class="modal fade" id="myModal<?php  echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          &times;
        </button>
        <h4 class="modal-title" id="myModalLabel">
          编辑信息
        </h4>
      </div>
      <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="qs_box col-md-12 pa_0">
                <label for="" class="col-md-3">真实名称:</label>
                <input type="text" value="<?php  echo $item['name'];?>" name="name" class="col-md-6 con">
            </div>
          <div class="qs_box col-md-12 pa_0">
            <label for="" class="col-md-3">联系方式 :</label>
            <input type="text" value="<?php  echo $item['tel'];?>" name="tel" class="col-md-6 con">
          </div>
          <div class="qs_box col-md-12 pa_0">
            <label for="" class="col-md-3">银行卡号 :</label>
            <input type="text" value="<?php  echo $item['yhk_num'];?>" name="yhk_num" class="col-md-6 con">
          </div>
          <div class="qs_box col-md-12 pa_0">
            <label for=""  class="col-md-3">银行卡户信息:</label>
            <input type="text" value="<?php  echo $item['yh_info'];?>" name="yh_info" class="col-md-6 con">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">取消
          </button>
          <input type="submit" class="btn btn-primary" value="确定" name="submit">
<!--           <button type="button" class="btn btn-primary">
            确定
          </button> -->
        </div>
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>"/>
        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
      </form>
    </div>
  </div>
</div>


          <?php  } } ?>
          <?php  if(empty($list)) { ?>
          <tr class="yg5_tr2">
            <td colspan="9">
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
        // $("#frame-13").addClass("in");
        $("#frame-13").show();
        $("#yframe-13").addClass("wyactive");
    })
</script>