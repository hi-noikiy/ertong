<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="javascript:void(0);">模板消息</a></li>
</ul>
  <div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="invitative">
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                系统设置&nbsp;>&nbsp;模板消息
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">外卖下单通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="xd_tid" value="<?php  echo $item['xd_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0009,关键词(订单编号,联系人姓名,联系人手机号,金额,时间)</div>
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">外卖接单通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="jd_tid" value="<?php  echo $item['jd_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0328,关键词(订单编号,订单状态,预计送达,订单信息,接单时间)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">拒绝接单通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="jj_tid" value="<?php  echo $item['jj_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0375,关键词(订单号,拒绝时间,拒绝原因,商家名称,客服电话,支付金额,备注)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退款通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="tk_tid" value="<?php  echo $item['tk_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0036,关键词(订单编号,退款商家,退款金额,退款方式,退款时间)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">预约通过通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="yy_tid" value="<?php  echo $item['yy_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0080,关键词(预定商家,订单号,联系电话,预定人数,预定座号,预定时间,订单金额,用餐时间,备注)</div>
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">入驻申请通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="rzsh_tid" value="<?php  echo $item['rzsh_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0444,关键词(状态,申请时间,商家名称,联系电话,备注)</div>
                    </div>
                </div>
                     <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">入驻审核通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="rzcg_tid" value="<?php  echo $item['rzcg_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT1709,关键词(审核结果,申请时间,商家名称,审核备注)</div>
                    </div>
                </div>
                     <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">用户充值成功通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="cz_tid" value="<?php  echo $item['cz_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0016,关键词(充值金额,赠送金额,充值时间,备注)</div>
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家外卖新订单提醒通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="xdd_tid" value="<?php  echo $item['xdd_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0079,关键词(订单内容,订单类型,下单时间,订单金额,收货人,联系电话,收货地址,备注,订单号)</div>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家当面付新订单提醒通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="xdd_tid2" value="<?php  echo $item['xdd_tid2'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0152,关键词(收款金额,支付方式,订单时间,付款人,交易单号)</div>
                    </div>
                </div>
                  <div class="form-group">
                 <label class="col-xs-12 col-sm-3 col-md-2 control-label">模板消息群发通知：</label>
                 <div class="col-sm-9">
                     <p class="form-control-static">
                          <input type="text" name="qf_tid" value="<?php  echo $item['qf_tid'];?>" id="points" class="form-control" />
                     </p>
                     <div style="color: #999;">*AT0480,关键词(备注,信息来源,信息内容,通知时间)</div>
                 </div>
             </div>
             <div class="form-group">
                 <label class="col-xs-12 col-sm-3 col-md-2 control-label">取号成功通知：</label>
                 <div class="col-sm-9">
                     <p class="form-control-static">
                          <input type="text" name="qh_tid" value="<?php  echo $item['qh_tid'];?>" id="points" class="form-control" />
                     </p>
                     <div style="color: #999;">*AT0086,关键词(排队状态,排队号码,桌位类型,还需等待,取号时间,商家名称,温馨提示)</div>
                 </div>
             </div>
                <!-- <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">客户预约点餐通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="yy_tid" value="<?php  echo $item['yy_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0104,关键词(门店,时间,姓名,电话,就餐人数,备注)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">客户当面付点餐通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="dm_tid" value="<?php  echo $item['dm_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: #999;">*模板编号AT0005,关键词(商家名称,付款金额,付款时间)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号商家新订单通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="sj_tid" value="<?php  echo $item['sj_tid'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: red;">*模板编号OPENTM200523662  标题:新订单通知(请将公众平台模板消息所在行业选择为： IT科技/互联网|电子商务 其他|其他，所选行业不一致将会导致模板消息不可用。)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号商家预约通知：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="sj_tid2" value="<?php  echo $item['sj_tid2'];?>" id="points" class="form-control" />
                        </p>
                        <div style="color: red;">*模板编号OPENTM405628250 标题:预定就餐提醒(请将公众平台模板消息所在行业选择为： IT科技/互联网|电子商务 其他|其他，所选行业不一致将会导致模板消息不可用。)</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知公众号appid：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="wx_appid" value="<?php  echo $item['wx_appid'];?>" id="points" class="form-control" />
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知公众号AppSecret：</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                             <input type="text" name="wx_secret" value="<?php  echo $item['wx_secret'];?>" id="points" class="form-control" />
                        </p>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="保存设置" class="btn col-lg-3" style="color: white;background-color: #44ABF7;" />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <input type="hidden" name="id" value="<?php  echo $item['id'];?>"/>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        // $("#frame-14").addClass("in");
        $("#frame-14").show();
        $("#yframe-14").addClass("wyactive");
    })
</script>

