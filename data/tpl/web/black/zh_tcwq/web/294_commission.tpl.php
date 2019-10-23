<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<style type="text/css">
    .yginp{width: 50%;}
    .ygspan{line-height: 35px;margin-left: 10px;}
</style>
<ul class="nav nav-tabs">    
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li class="active"><a href="javascript:void(0);">城市代理佣金</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="invitative">
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                城市代理佣金比例设置
            </div>
            <div class="panel-body">
             
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金比例类型</label>
                    <div class="col-sm-9">
                        <select class="col-sm-5" id="type" name="type" autocomplete="off">  
                                <option value="1" <?php  if($item['type']=='1') { ?> selected <?php  } ?>>统一模式</option>
                                <option value="2" <?php  if($item['type']=='2') { ?> selected <?php  } ?>>分开模式</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group ygyi2">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金比例设置</label>
                    <div class="col-sm-9">
                        <input type="text" name="typer" value="<?php  echo $item['typer'];?>" id="web_name" class="form-control yginp col-md-7" placeholder="佣金比例设置" /><span class="ygspan">%</span>
                    </div>
                </div>               
                <div class="form-group ygyi">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家模块比例</label>
                    <div class="col-sm-9">
                        <input type="text" name="sjper" value="<?php  echo $item['sjper'];?>" id="web_name" class="form-control yginp col-md-7" placeholder="商家模块比例" /><span class="ygspan">%</span>
                    </div>
                </div> 
                 <div class="form-group ygyi">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子模块比例</label>
                    <div class="col-sm-9">
                        <input type="text" name="tzper" value="<?php  echo $item['tzper'];?>" id="web_name" class="form-control yginp col-md-7" placeholder="帖子模块比例" /><span class="ygspan">%</span>
                    </div>
                </div>                               
                <div class="form-group ygyi">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">黄页模块比例</label>
                    <div class="col-sm-9">
                        <input type="text" name="hyper" value="<?php  echo $item['hyper'];?>" id="web_name" class="form-control yginp col-md-7" placeholder="黄页模块比例"/><span class="ygspan">%</span>
                    </div>
                </div>                
                <div class="form-group ygyi">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">拼车模块比例</label>
                    <div class="col-sm-9">
                        <input type="text" name="pcper" value="<?php  echo $item['pcper'];?>" id="web_name" class="form-control yginp col-md-7" placeholder="拼车模块比例" /><span class="ygspan">%</span>
                    </div>
                </div>                 
            </div>
        </div>
        
        <div class="form-group">
            <input type="submit" name="submit" value="保存设置" class="btn col-lg-3" style="color: white;background-color: #44ABF7;" />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
            <input type="hidden" id="zhi" value="<?php  echo $item['type'];?>" />
        </div>
    </form>
</div>
<!-- <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?> -->
<script type="text/javascript">
    $(function(){
        // $(".ygyi").hide();
        // $(".ygyi2").show();  
        // $("select#ygadd").change(function(){
        //     if($(this).val()=='1'){
        //         console.log($(this).val())
        //         $(".ygyi").hide();
        //         $(".ygyi2").show();
        //     }else if($(this).val()=='2'){
        //         console.log($(this).val())
        //         $(".ygyi").show();
        //         $(".ygyi2").hide();
        //     }
        //  })
        "<?php  if($item) { ?>"
            "<?php  if($item['type']=='1') { ?>"
                $('.ygyi').hide();
            "<?php  } ?>"
            "<?php  if($item['type']=='2') { ?>"
                $('.ygyi2').hide();
            "<?php  } ?>"            
        "<?php  } else { ?>"
            $('.ygyi').hide();
        "<?php  } ?>"
        $('#type').change(function(){
            $('.ygyi').show();
            $('.ygyi2').show();
            if($(this).val() == '1') {
                $('.ygyi').hide();
            }
            if($(this).val() == '2') {
                $('.ygyi2').hide();
            }
        });
        $("#frame-15").show();
        $("#yframe-15").addClass("wyactive");
    })
</script>
