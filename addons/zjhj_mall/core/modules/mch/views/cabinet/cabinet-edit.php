<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$this->title = '编辑柜子';
$this->params['active_nav_group'] = 2;
$returnUrl = Yii::$app->request->referrer;
$urlManager = Yii::$app->urlManager;
$commonDistrict = new \app\models\common\CommonDistrict();
$district = Yii::$app->serializer->encode($commonDistrict->search());
?>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/assets/js/data.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/assets/js/prettify.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/dist/jquery.city.select.min.js"></script>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="Address">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">柜子ID</label>
                </div>
                <div class="col-sm-6">
                    <?php if ($list['cabinet_id']): ?>
                        <input class="form-control" type="text" name="model[cabinet_id]" value="<?= $list['cabinet_id'] ?>" maxlength="16" readonly>
                    <?php else : ?>
                        <input class="form-control" type="text" name="model[cabinet_id]" value="<?= $list['cabinet_id'] ?>" maxlength="16">
                    <?php endif; ?>
                    
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">类型</label>
                </div>
                <div class="col-sm-6">
                        <?php if ($list['cabinet_type']): ?>
                            <?php if ($list['cabinet_type']==1): ?>
                                <select class="form-control parent" name="model[cabinet_type]" readonly>
                                    <option value="0"></option>
                                    <option value="1" selected>常温柜</option>
                                    <option value="2">冷藏柜</option>
                                    <option value="3">冷冻柜</option>
                                </select>
                            <?php elseif ($list['cabinet_type']==2) : ?>
                                <select class="form-control parent" name="model[cabinet_type]" readonly>
                                    <option value="0"></option>
                                    <option value="1">常温柜</option>
                                    <option value="2" selected>冷藏柜</option>
                                    <option value="3">冷冻柜</option>
                                </select>
                            <?php elseif ($list['cabinet_type']==3) : ?>
                                <select class="form-control parent" name="model[cabinet_type]" readonly>
                                    <option value="0"></option>
                                    <option value="1">常温柜</option>
                                    <option value="2">冷藏柜</option>
                                    <option value="3" selected>冷冻柜</option>
                                </select>
                            <?php endif; ?>
                        <?php else : ?>
                            <select class="form-control parent" name="model[cabinet_type]">
                                <option value="0"></option>
                                <option value="1">常温柜</option>
                                <option value="2">冷藏柜</option>
                                <option value="3">冷冻柜</option>
                            </select>
                        <?php endif; ?>
                        
                    
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">仓库分类</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control parent" name="model[wherehouse_id]">
                        <option value="0">选择仓库分类</option>
                        <?php foreach ($warehouse_list as $p) : ?>
                            <?php if ($list['wherehouse_id']== $p['id']): ?>
                                <option value="<?= $p['id'] ?>" selected><?= $p['warehouse_name'] ?></option>
                            <?php else : ?>
                                <option value="<?= $p['id'] ?>"><?= $p['warehouse_name'] ?></option>
                            <?php endif; ?>
                            
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">投放城市</label>
                    </div>
                    <div class="col-sm-6" >
                        <div class="input-group">
                            <select class="form-control province" style="float: left;"
                                    name="model[province]">
                                <?php foreach ($province_arr as $key => $val) : ?>
                                    <option value="<?= $val['province'] ?>" data-index="index"><?= $val['province'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-control city" style="float: left;" name="model[city]">
                            </select>
                        </div>
                    </div>
                </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">详细地址</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[address]" value="<?= $list['address'] ?>" maxlength="50">
                </div>
            </div>
            <input class="form-control" type="hidden" name="model[id]" value="<?= $list['id'] ?>" >
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // $(function () {
    //     $('#province, #city').citylist({
    //         data    : data,
    //         id      : 'id',
    //         children: 'cities',
    //         name    : 'name',
    //         metaTag : 'name',
    //     });
    // });
    $(function(){
        // var editAddress = new Vue({
        //     el: '#Address',
        //     data: {
        //         province:<?=$district?>,
        //         city: [],
        //         area: [],
        //         sender_province: "<?=$sender->province?>",
        //         sender_city: "<?=$sender->city?>",
        //         orderList: <?= Yii::$app->serializer->encode($list) ?>
        //     }
        // });
        var orderList=<?= Yii::$app->serializer->encode($list) ?>;
        var province_arr=<?= Yii::$app->serializer->encode($province_arr) ?>;
        var city_arr = <?= Yii::$app->serializer->encode($city_arr) ?>;

        // 弹框
        var sender_province = orderList.province
        var sender_city = orderList.city
        
        $('.province').find('option').each(function (i) {
            if ($(this).val() == sender_province) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        var cityHtml="";
        for (var i = 0; i < city_arr.length; i++) {
            if(sender_city==city_arr[i]['city']){
                cityHtml+="<option value="+city_arr[i]['city']+" data-index='i' selected >"+city_arr[i]['city']+"</option>";
            }else{
                cityHtml+="<option value="+city_arr[i]['city']+" data-index='i'>"+city_arr[i]['city']+"</option>";
            }
        }
        $(".city").html(cityHtml)
        

        $('#editAddress').modal('show');

        $(document).on('change', '.province', function () {
            var province = $(this).find('option:selected').val();
            $.ajax({
                type: "POST",
                url: '<?= $urlManager->createUrl(['mch/order/select-city']) ?>',
                dataType: "json",
                data: {
                    'province': province,
                    _csrf: _csrf
                }, 
                cache: false,
                success: function(data) {
                    if(data.code==0){
                        $(".city").empty();
                        $(".cabinet").empty();
                        var cityHtml="";
                        for (var i = 0; i < data.city_arr.length; i++) {
                            
                            cityHtml+="<option value="+data.city_arr[i]['city']+" data-index='i'>"+data.city_arr[i]['city']+"</option>";
                            
                        }
                        $(".city").html(cityHtml)
                    }else{
                        alert(data.msg)
                    }
                }
            });
            
        });
        
    })

</script>
<!--更新地址相关-->
<script>
    // console.log(<?=$district?>);
    // var editAddress = new Vue({
    //     el: '#editAddress',
    //     data: {
    //         province:<?=$district?>,
    //         city: [],
    //         sender_province: "<?=$sender->province?>",
    //         sender_city: "<?=$sender->city?>",
    //     }
    // });

    // 弹框
    // $(document).on("click", ".edit-address", function () {
    //     var sender_province = '<?= $list['province'] ?>';
    //     var sender_city = '<?= $list['city'] ?>';
    //     editAddress.sender_province = sender_province
    //     editAddress.sender_city = sender_city
    //     console.log(editAddress.sender_city);
    //     $('.province').find('option').each(function (i) {
    //         if ($(this).val() == editAddress.sender_province) {
    //             $(this).prop('selected', 'selected');
    //             return true;
    //         }
    //     });
    //     $('.city').find('option').each(function (i) {
    //         if ($(this).val() == editAddress.sender_city) {
    //             $(this).prop('selected', 'selected');
    //             return true;
    //         }
    //     });


    //     editAddress.city = editAddress.province[0].list;
    //     $(editAddress.province).each(function (i) {
    //         if (editAddress.province[i].name == editAddress.sender_province) {
    //             editAddress.city = editAddress.province[i].list;
    //             return true;
    //         }
    //     });

    //     $('#editAddress').modal('show');
    // });

    // $(document).on('change', '.province', function () {
    //     var index = $(this).find('option:selected').data('index');
    //     editAddress.city = editAddress.province[index].list;
    // });

</script>