<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$this->title = '修改送达时间';
$this->params['active_nav_group'] = 2;
$returnUrl = Yii::$app->request->referrer;
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="Address">
        <form class="auto-form">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">下单时间</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 20%;">
                        从
                        <select class="form-control start_h" name="model[start_h]" style="float: left;">
                            <?php foreach ($start_h_arr as $item): ?>
                                <?php if ($start_h==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>：
                        <select class="form-control start_i" name="model[start_i]" style="float: left;">
                            <?php foreach ($start_i_arr as $item): ?>
                                <?php if ($start_i==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                            
                        </select>
                        开始
                    </div>
                </div>

            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 20%;">
                        到
                        <select class="form-control end_h" name="model[end_h]" style="float: left;">
                            <?php foreach ($start_h_arr as $item): ?>
                                <?php if ($end_h==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>：
                        <select class="form-control end_i" name="model[end_i]" style="float: left;">
                            <?php foreach ($start_i_arr as $item): ?>
                                <?php if ($end_i==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>
                        结束
                    </div>
                    
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">送达日期</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 30%;">
                        最早：
                        <select class="form-control start_d" name="model[start_d]" style="float: left;">
                            <?php foreach ($start_d_arr as $key=>$item): ?>
                                <?php if ($start_d==$key): ?>
                                    <option value="<?= $key ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $key ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 30%;">
                        最晚：
                        <select class="form-control end_d" name="model[end_d]" style="float: left;">
                            <?php foreach ($end_d_arr as $key=>$item): ?>
                                <?php if ($end_d==$key): ?>
                                    <option value="<?= $key ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $key ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">送达时间</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 20%;">
                        上午：
                        <select class="form-control service_shang" name="model[service_shang]" style="float: left;">
                            <option value="">请选择</option>
                            <?php foreach ($service_shang_arr as $item): ?>
                                <?php if ($service_shang==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    
                </div>
                <div class="col-sm-6">
                    <div class="input-group" style="width: 20%;">
                        下午：
                        <select class="form-control service_xia" name="model[service_xia]" style="float: left;">
                            <option value="">请选择</option>
                            <<?php foreach ($service_xia_arr as $item): ?>
                                <?php if ($service_xia==$item): ?>
                                    <option value="<?= $item ?>" selected><?= $item ?></option>
                                <?php else: ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <input class="form-control delivery_id" type="hidden" name="model[id]" value="<?= $id ?>" >
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary submit" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).on("click", ".submit", function () {
        var start_h=$(".start_h  option:selected").val();
        var end_h=$(".end_h  option:selected").val();
        var start_i=$(".start_i  option:selected").val();
        var end_i=$(".end_i  option:selected").val();
        var start_d=$(".start_d  option:selected").val();
        var end_d=$(".end_d  option:selected").val();
        var service_shang=$(".service_shang  option:selected").val();
        var service_xia=$(".service_xia  option:selected").val();
        var delivery_id=$(".delivery_id").val();

        if(start_h>=end_h){
            alert("下单开始时间要大于结束时间！")
            return false
        }
        if(start_d>=end_d){
            alert("送达最早日期要大于最晚日期！")
            return false
        }
        if(service_shang=='' && service_xia==''){
            alert("送达时间最少选一项！")
            return false
        }
        var href = '<?= $urlManager->createUrl(['mch/delivery-time/delivery-time-edit']) ?>';
        var start_time=start_h+':'+start_i;
        var end_time=end_h+':'+end_i;
        $.ajax({
            url: href,
            type: "post",
            data: {
                start_time: start_time,
                end_time: end_time,
                start_d: start_d,
                end_d: end_d,
                delivery_id: delivery_id,
                service_shang: service_shang,
                service_xia: service_xia,
                _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                if(res.code==0){
                    window.location.href="<?= $urlManager->createUrl(['mch/delivery-time/list']) ?>";
                }else{
                    alert(res.msg)
                }
            }
        });
        return false;

    });
</script>