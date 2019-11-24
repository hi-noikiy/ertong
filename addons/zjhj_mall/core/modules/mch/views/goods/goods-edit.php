<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 10:49
 */

$urlManager = Yii::$app->urlManager;
$this->title = '商品编辑';
$staticBaseUrl = Yii::$app->request->baseUrl . '/statics';
$this->params['active_nav_group'] = 2;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl([get_plugin_url() . '/goods']);
}
?>
<script src="<?= $staticBaseUrl ?>/mch/js/uploadVideo.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/datetime.js?v=2.5.8"></script>
<style>
    .cat-box {
        border: 1px solid rgba(0, 0, 0, .15);
    }

    .cat-box .row {
        margin: 0;
        padding: 0;
    }

    .cat-box .col-6 {
        padding: 0;
    }

    .cat-box .cat-list {
        border-right: 1px solid rgba(0, 0, 0, .15);
        overflow-x: hidden;
        overflow-y: auto;
        height: 10rem;
    }

    .cat-box .cat-item {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
        padding: .5rem 1rem;
        display: block;
        margin: 0;
    }

    .cat-box .cat-item:hover {
        background: rgba(0, 0, 0, .05);
    }

    .cat-box .cat-item.active {
        background: rgb(2, 117, 216);
        color: #fff;
    }

    .cat-box .cat-item input {
        display: none;
    }

    form .head {
        position: fixed;
        top: 100px;
        right: 1rem;
        left: calc(240px + 1rem);
        z-index: 1001;
        padding-top: 1rem;
        background: #f5f7f9;
        padding-bottom: 1rem;
    }

    form .head .head-content {
        background: #fff;
        border: 1px solid #eee;
        height: 40px;
    }

    .head-step {
        height: 100%;
        padding: 0 20px;
    }

    .step-block {
        position: relative;
    }

    form .body {
        padding-top: 45px;
    }

    .step-block > div {
        padding: 20px;
        background: #fff;
        border: 1px solid #eee;
        margin-bottom: 5px;
    }

    .step-block > div:first-child {
        padding: 20px;
        width: 120px;
        margin-right: 5px;
        font-weight: bold;
        text-align: center;
    }

    .step-block .step-location {
        position: absolute;
        top: -122px;
        left: 0;
    }

    .step-block:first-child .step-location {
        top: -140px;
    }

    form .short-row {
        width: 450px;
    }

    .attr-group {
        border: 1px solid #eee;
        padding: .5rem .75rem;
        margin-bottom: .5rem;
        border-radius: .15rem;
    }

    .attr-group-delete {
        display: inline-block;
        background: #eee;
        color: #fff;
        width: 1rem;
        height: 1rem;
        text-align: center;
        line-height: 1rem;
        border-radius: 999px;
    }

    .attr-group-delete:hover {
        background: #ff4544;
        color: #fff;
        text-decoration: none;
    }

    .attr-list > div {
        vertical-align: top;
    }

    .attr-item {
        display: inline-block;
        background: #eee;
        margin-right: 1rem;
        margin-top: .5rem;
        overflow: hidden;
    }

    .attr-item .attr-name {
        padding: .15rem .75rem;
        display: inline-block;
    }

    .attr-item .attr-delete {
        padding: .35rem .75rem;
        background: #d4cece;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
    }

    .attr-item .attr-delete:hover {
        text-decoration: none;
        color: #fff;
        background: #ff4544;
    }

    .panel {
        margin-top: calc(50px + 1rem);
    }

    form .form-group .col-3 {
        -webkit-box-flex: 0;
        -webkit-flex: 0 0 160px;
        -ms-flex: 0 0 160px;
        flex: 0 0 160px;
        max-width: 160px;
        width: 160px;
    }
    .input-group-addon-service{
        margin-bottom: 0;
        margin-right: 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.25;
        color: #464a4c;
        text-align: center;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: .25rem;
        padding: .35rem .7rem;

    }
    .cat-box .cat-item1 .active {
        background: rgb(2, 117, 216);
        color: #fff;
    }
    .cat-box .cat-item1 {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
        padding: .5rem 1rem;
        display: block;
        margin: 0;
    }
    .delelt-span{
        padding-left: 0.8rem;
    }
</style>


<div id="one_menu_bar">
    <div id="tab_bar">
        <ul>
            <li class="tab_bar_item" id="tab1" onclick="myclick(1)" style="background-color: #eeeeee">
                基础设置
            </li>
            <li class="tab_bar_item" <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> id="tab2" onclick="myclick(2)">
                分销价设置
            </li>
            <li class="tab_bar_item" <?= in_array(get_plugin_type(), [2,5]) ? 'hidden' : '' ?> id="tab3" onclick="myclick(3)">
                会员价设置
            </li>
        </ul>
    </div>
    <div id="page">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">
            <div class="tab_css" id="tab1_content" style="display: block">
                <div>
                    <div class="panel mb-3">
                        <div class="panel-header"><?= $this->title ?></div>
                        <div class="panel-body">

                            <div class="head">
                                <div class="head-content" flex="dir:left">
                                    <a flex="cross:center" class="head-step" href="#step1">选择分类</a>
                                    <a flex="cross:center" class="head-step" href="#step2">基本信息</a>
                                    <a flex="cross:center" <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="head-step" href="#step6">营销</a>
                                    <a flex="cross:center" <?= in_array(get_plugin_type(), [0,5]) ? 'hidden' : '' ?> class="head-step" href="#step9">砍价设置</a>
                                    <!-- <a flex="cross:center" <?= in_array(get_plugin_type(), [2,5]) ? 'hidden' : '' ?> class="head-step" href="#step7">快速购买</a> -->
                                    <a flex="cross:center" class="head-step" href="#step4">商品详情</a>
                                </div>
                            </div>

                            <div class="step-block" flex="dir:left box:first">
                                <div>
                                    <span>选择分类</span>
                                    <br>
                                    <a class="addcat" href="javascript:">添加新分类</a>
                                    <span class="step-location" id="step1"></span>
                                </div>
                                <div>
                                    <!-- <div class="form-group row" >
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">商品分类</label>
                                        </div>
                                        <div class="col-9">

                                            <?php if (count($goods_cat_list) > 0) : ?>
                                                
                                            <div class="input-group short-row">
                                                <select class="form-control short-row parents" name="model[cat_id][]">
                                                    <option value="0"></option>
                                                    <?php foreach ($cat_list as $p) : ?>
                                                        <?php if ($goods_cat_list[0]['cat_id']== $p->id): ?>
                                                            <option value="<?= $p->id ?>" selected><?= $p->name ?></option>
                                                        <?php else : ?>
                                                            <option value="<?= $p->id ?>"><?= $p->name ?></option>
                                                        <?php endif; ?>
                                                        
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-secondary" href="javascript:" data-toggle="modal" :data-index="i">选择一级分类</a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-danger delete-cat-parents" href="javascript:" :data-index="i">删除</a>
                                                </span>
                                            </div>
                                                <div class="input-group short-row" style="margin-top: .75rem;">
                                                    
                                                    <select class="form-control short-row son" name="model[cat_id][]">
                                                        <option value="0"></option>
                                                        <?php foreach ($goods_cat_son_list as $p) : ?>
                                                            <?php if ($goods_cat_list[1]['cat_id']== $p['id']): ?>
                                                                <option value="<?= $p['id'] ?>" selected><?= $p['name'] ?></option>
                                                            <?php else : ?>
                                                                <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>

                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-secondary" href="javascript:" data-toggle="modal" :data-index="i">选择二级分类</a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-danger delete-cat-son" href="javascript:" :data-index="i">删除</a>
                                                    </span>
                                                </div>
                                            <?php else : ?>
                                                <div class="input-group short-row">
                                                    <select class="form-control short-row parents" name="model[cat_id][]">
                                                        <option value="0"></option>
                                                        <?php foreach ($cat_list as $p) : ?>
                                                            <option
                                                                    value="<?= $p->id ?>" <?= $p->id == $goods['name'] ? 'selected' : '' ?>><?= $p->name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-secondary" href="javascript:" data-toggle="modal" :data-index="i">选择一级分类</a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-danger delete-cat-parents" href="javascript:" :data-index="i">删除</a>
                                                    </span>
                                                </div>
                                                <div class="input-group short-row" style="margin-top: .75rem;">
                                                    
                                                    <select class="form-control short-row son" name="model[cat_id][]">
                                                        <option value="0"></option>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-secondary" href="javascript:" data-toggle="modal" :data-index="i">选择二级分类</a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-danger delete-cat-son" href="javascript:" :data-index="i">删除</a>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div> -->
                                    <div class="form-group row" v-for="(item,i) in goods_cat_list">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">商品分类</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input readonly class="form-control cat-name" v-model="item.cat_name">
                                                <input type="hidden" name="model[cat_id][]" class="form-control cat-id"
                                                       v-model="item.cat_id">
                                                <span class="input-group-btn">
                                                    <a class="btn btn-secondary cat-modal" href="javascript:" data-toggle="modal"
                                                        data-target="#catModal" :data-index="i">选择分类</a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-danger delete-cat" href="javascript:" :data-index="i">删除</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="step-block" flex="dir:left box:first">
                                <div>
                                    <span>基本信息</span>
                                    <span class="step-location" id="step2"></span>
                                </div>
                                <div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">商品名称</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="text" name="model[name]"
                                                   value="<?= str_replace("\"", "&quot", $goods['name']) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">单位</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="text" name="model[unit]"
                                                   value="<?= $goods['unit'] ? $goods['unit'] : '件' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">商品简介</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="text" name="model[intro]"
                                                   value="<?= str_replace("\"", "&quot", $goods['intro']) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">商品排序</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="text" name="model[sort]"
                                                   value="<?= $goods['sort'] ?>">
                                            <div class="text-muted fs-sm">排序按升序排列</div>
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="form-group row" >
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">已出售量</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="number"
                                                   name="model[virtual_sales]"
                                                   value="<?= $goods['virtual_sales'] ?>" min="0" max="999999">
                                            <div class="text-muted fs-sm">前端展示的销量=实际销量+已出售量</div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">限购数量</label>
                                        </div>
                                        <div class="col-9">
                                            <input class="form-control short-row" type="number"
                                                   name="model[confine_count]"
                                                   value="<?= $goods['confine_count'] ?>" min="0" max="999999">
                                            <div class="text-muted fs-sm">设置为0则不限购，大于0则等于对应的限购数量</div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">重量</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control"
                                                       name="model[weight]"
                                                       value="<?= $goods['weight'] ? $goods['weight'] : 0 ?>">
                                                <span class="input-group-addon">克<span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class="col-form-label required">商品缩略图</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="upload-group short-row">
                                                <div class="input-group">
                                                    <input class="form-control file-input" name="model[cover_pic]"
                                                           value="<?= $goods->cover_pic ?>">
                                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary upload-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="上传文件">
                                            <span class="iconfont icon-cloudupload"></span>
                                        </a>
                                    </span>
                                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary select-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="从文件库选择">
                                            <span class="iconfont icon-viewmodule"></span>
                                        </a>
                                    </span>
                                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary delete-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="删除文件">
                                            <span class="iconfont icon-close"></span>
                                        </a>
                                    </span>
                                                </div>
                                                <div class="upload-preview text-center upload-preview">
                                                    <span class="upload-preview-tip">325&times;325</span>
                                                    <img class="upload-preview-img" src="<?= $goods->cover_pic ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">商品视频</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="video-picker"
                                                 data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                                <div class="input-group short-row">
                                                    <input class="video-picker-input video form-control"
                                                           name="model[video_url]"
                                                           value="<?= $goods['video_url'] ?>"
                                                           placeholder="请输入视频源地址或者选择上传视频">
                                                    <a href="javascript:"
                                                       class="btn btn-secondary video-picker-btn">选择视频</a>
                                                </div>
                                                <a class="video-check"
                                                   href="<?= $goods['video_url'] ? $goods['video_url'] : "javascript:" ?>"
                                                   target="_blank">视频预览</a>

                                                <div class="video-preview"></div>
                                                <div>
                                    <span
                                            class="text-danger fs-sm">支持格式mp4;支持编码H.264;视频大小不能超过<?= \app\models\UploadForm::getMaxUploadSize() ?>
                                        MB</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class="col-form-label required">商品图片</label>
                                        </div>
                                        <div class="col-9">
                                            <?php if ($goods->goodsPicList) :
                                                foreach ($goods->goodsPicList as $goods_pic) : ?>
                                                    <?php $goods_pic_list[] = $goods_pic->pic_url ?>
                                                <?php endforeach;
                                            else :
                                                $goods_pic_list = [];
                                            endif; ?>

                                            <div class="upload-group multiple short-row">
                                                <div class="input-group">
                                                    <input class="form-control file-input" readonly>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-secondary upload-file" href="javascript:"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom" title="上传文件">
                                                            <span class="iconfont icon-cloudupload"></span>
                                                        </a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn btn-secondary select-file" href="javascript:"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom" title="从文件库选择">
                                                            <span class="iconfont icon-viewmodule"></span>
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="upload-preview-list" id="sortList">
                                                    <?php if (count($goods_pic_list) > 0) : ?>
                                                        <?php foreach ($goods_pic_list as $item) : ?>
                                                            <div class="upload-preview text-center" flex="cross:center">
                                                                <input type="hidden" class="file-item-input"
                                                                       name="model[goods_pic_list][]"
                                                                       value="<?= $item ?>">
                                                                <span class="file-item-delete">&times;</span>
                                                                <span class="upload-preview-tip">750&times;750</span>
                                                                <img class="upload-preview-img" src="<?= $item ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <div class="upload-preview text-center">
                                                            <input type="hidden" class="file-item-input"
                                                                   name="model[goods_pic_list][]">
                                                            <span class="file-item-delete">&times;</span>
                                                            <span class="upload-preview-tip">750&times;750</span>
                                                            <img class="upload-preview-img" src="">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">售价</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control"
                                                       name="model[price]" min="0.01"
                                                       value="<?= $goods['price'] ? $goods['price'] : 1 ?>">
                                                <span <?= in_array(get_plugin_type(), [0,2]) ? 'hidden' : '' ?> class="input-group-addon">活力币</span>
                                                <span <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">成本价</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control"
                                                       name="model[cost_price]" min="0.01"
                                                       value="<?= $goods['cost_price'] ? $goods['cost_price'] : 1 ?>">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">原价</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control short-row"
                                                       name="model[original_price]" min="0"
                                                       value="<?= $goods['original_price'] ? $goods['original_price'] : 1 ?>">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">企业用户价</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control short-row"
                                                       name="model[merchant_price]" min="0"
                                                       value="<?= $goods['merchant_price'] ? $goods['merchant_price'] : 1 ?>">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">服务内容</label>
                                        </div>
                                        <div class="col-9">
                                            
                                            <div class="input-group short-row service1">
                                                <?php foreach ($service_arr as $p) : ?>
                                                <span class="input-group-addon-service"><?= $p['name'] ?><span class="delelt-span">×</span></span>
                                                <?php endforeach; ?>
                                                <a class="select-service addcat1" data-toggle="modal"
                                                        data-target="#catModal1" style="color: #0275d8;">选择</a>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden_x' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">运费设置</label>
                                        </div>
                                        <div class="col-9">
                                            <select class="form-control short-row" name="model[freight]">
                                                <option value="0">默认模板</option>
                                                <?php foreach ($postageRiles as $p) : ?>
                                                    <option
                                                            value="<?= $p->id ?>" <?= $p->id == $goods['freight'] ? 'selected' : '' ?>><?= $p->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">单品满件包邮</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" class="form-control short-row"
                                                       name="full_cut[pieces]"
                                                       value="<?= $goods['full_cut']['pieces'] ?>">
                                                <span class="input-group-addon">件</span>
                                            </div>
                                            <div class="fs-sm text-muted">如果设置0或空，则不支持满件包邮</div>
                                        </div>
                                    </div>

                                    <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label">单品满额包邮</label>
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group short-row">
                                                <input type="number" step="0.01" class="form-control short-row"
                                                       name="full_cut[forehead]"
                                                       value="<?= $goods['full_cut']['forehead'] ?>">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                            <div class="fs-sm text-muted">如果设置0或空，则不支持满额包邮</div>
                                        </div>
                                    </div>
                                    <div <?= in_array(get_plugin_type(), [2,5]) ? 'hidden' : '' ?> class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class="col-form-label">是否开启面议</label>
                                        </div>
                                        <div class="col-9">
                                            <label class="radio-label">
                                                <input <?= $goods['is_negotiable'] == 0 ? 'checked' : null ?>
                                                        value="0" name="model[is_negotiable]" type="radio"
                                                        class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">关闭</span>
                                            </label>
                                            <label class="radio-label">
                                                <input <?= $goods['is_negotiable'] == 1 ? 'checked' : null ?>
                                                        value="1" name="model[is_negotiable]" type="radio"
                                                        class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">开启</span>
                                            </label>

                                            <div class="fs-sm text-danger">如果开启面议，则商品无法在线支付</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?= $this->render('/layouts/attrs/attr_setting', [
                                'goods' => $goods,
                            ]) ?>
                            <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="step-block" flex="dir:left box:first">
                                <div>
                                    <span>商品存放类型</span>
                                    <span class="step-location" id="step6"></span>
                                </div>
                                <div>
                                    <?php if ($goods['storage_type']==1) : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input checked="checked" value="1" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">常温</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="2" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷藏</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="3" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷冻</span>
                                            </label>
                                        </div>
                                    <?php elseif ($goods['storage_type']==2) : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input value="1" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">常温</span>
                                            </label>
                                            <label class="radio-label">
                                                <input checked="checked" value="2" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷藏</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="3" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷冻</span>
                                            </label>
                                        </div>
                                    <?php elseif ($goods['storage_type']==3) : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input value="1" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">常温</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="2" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷藏</span>
                                            </label>
                                            <label class="radio-label">
                                                <input checked="checked" value="3" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷冻</span>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input value="1" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">常温</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="2" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷藏</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="3" name="model[storage_type]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">冷冻</span>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div <?= in_array(get_plugin_type(), [5]) ? 'hidden' : '' ?> class="step-block" flex="dir:left box:first">
                                <div>
                                    <span>营销</span>
                                    <span class="step-location" id="step6"></span>
                                </div>
                                <div>
                                    <?php if ($goods['marketing']==1) : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input checked="checked" value="1" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">新品</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="2" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">添加活动</span>
                                            </label>
                                        </div>
                                    <?php elseif ($goods['marketing']==2) : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input value="1" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">新品</span>
                                            </label>
                                            <label class="radio-label">
                                                <input checked="checked" value="2" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">添加活动</span>
                                            </label>
                                        </div>
                                    <?php else : ?>
                                        <div class="col-9 col-form-label">
                                            <label class="radio-label">
                                                <input checked="checked" value="1" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">新品</span>
                                            </label>
                                            <label class="radio-label">
                                                <input value="2" name="model[marketing]" type="radio" class="custom-control-input">
                                                <span class="label-icon"></span>
                                                <span class="label-text">添加活动</span>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!--额外设置-->
                            <div class="step-block" flex="dir:left box:first">
                                <?php foreach ($plugins as $plugin) : ?>
                                    <?= $this->render($plugin['url'], $plugin['data']); ?>
                                <?php endforeach; ?>
                            </div>
                            <!--额外设置-->
                            <div class="step-block" flex="dir:left box:first">
                                <div>
                                    <span>图文详情</span>
                                    <span class="step-location" id="step4"></span>
                                </div>
                                <div>
                                    <div class="form-group row">
                                        <div class="col-3 text-right">
                                            <label class=" col-form-label required">图文详情</label>
                                        </div>
                                        <div class="col-9">
                            <textarea class="short-row" id="editor"
                                      name="model[detail]"><?= $goods['detail'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>

            <!--多规格分销价-->
            <div class="tab_css" id="tab2_content">
                <div>
                    <?= $this->render('/layouts/attrs/attr_share_price', [
                        'goods' => $goods,
                        'attr' => $goods['attr']
                    ]) ?>
                </div>
            </div>

            <!--多规格会员价-->
            <div class="tab_css" id="tab3_content">
                <div>
                    <?= $this->render('/layouts/attrs/attr_member_price', [
                        'levelList' => $levelList,
                        'goods' => $goods,
                        'attr' => $goods['attr']
                    ]) ?>
                </div>
            </div>
            <input name="model[service]" id="service_name_arr" type="hidden" value="<?= $goods['service'] ?>">
            <div style="margin-left: 0;" class="form-group row text-center">
                <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                <input type="button" class="btn btn-default ml-4"
                       name="Submit" onclick="javascript:history.back(-1);" value="返回">
            </div>
        </form>

        <!-- 选择分类 -->
        <div class="modal fade" id="catModal1" tabindex="-1" role="dialog"
             aria-labelledby="catModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 30rem">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>选择服务内容</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="cat-box">
                            <div class="row" style="height: 9rem;">
                                <?php foreach ($option_arr as $index => $cat) : ?>
                                    <label class="input-group-addon-service cat-item select-service <?= $index == 0 ? 'active' : '' ?>" style="height: 2.5rem;margin-left: 0.5rem;margin-top: 0.5rem;">
                                        <?= $cat['service'] ?>
                                        <input value="<?= $cat['id'] ?>"
                                            <?= $index == 0 ? 'checked' : '' ?>
                                               type="checkbox"
                                               name="model[cat_id]">
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary cat-confirm1">确认</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="catModal" tabindex="-1" role="dialog"
             aria-labelledby="catModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>选择分类</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="cat-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="cat-list parent-cat-list">
                                        <?php foreach ($cat_list as $index => $cat) : ?>
                                            <label class="cat-item select-cat <?= $index == 0 ? 'active' : '' ?>">
                                                <?= $cat->name ?>
                                                <input value="<?= $cat->id ?>"
                                                    <?= $index == 0 ? 'checked' : '' ?>
                                                       type="radio"
                                                       name="model[cat_id]">
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="cat-list">
                                        <label class="cat-item select-cat" v-for="sub_cat in sub_cat_list">
                                            {{sub_cat.name}}
                                            <input v-bind:value="sub_cat.id" type="radio"
                                                   name="model[cat_id]">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary cat-confirm">确认</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('/layouts/attrs/common', [
    'page_type' => 'STORE',
    'goods' => $goods,
    'service_arr' => $service_arr,
    'goods_card_list' => $goods_card_list,
    'card_list' => $card_list,
    'goods_cat_list' => $goods_cat_list,
    'level_list' => $levelList,
]) ?>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js?v=1.9.6"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js?v=1.9.6"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>
<script>
    var Map = function () {
        this._data = [];
        this.set = function (key, val) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data[i].val = val;
                    return true;
                }
            }
            this._data.push({
                key: key,
                val: val,
            });
            return true;
        };
        this.get = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key)
                    return this._data[i].val;
            }
            return null;
        };
        this.delete = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data.splice(i, 1);
                }
            }
            return true;
        };
    };
    var map = new Map();

    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
    });
    // $(document).on("click", ".cat-item input", function () {
        
    //     if ($(this).prop("checked")) {
            
    //         // $(".cat-item").removeClass("active");
    //         $(this).parents().addClass("active");
    //         $(this).attr('checked','checked');
    //     } else {
    //         $(this).parent(".cat-item").removeClass("active");
    //         $(this).removeAttr('checked');
    //     }
    // });
    $(document).on("change", ".select-cat input", function () {
        if ($(this).prop("checked")) {
            $(".select-cat").removeClass("active");
            $(this).parent(".select-cat").addClass("active");
        } else {
            $(this).parent(".select-cat").removeClass("active");
        }
    });

    $(document).on("change", ".parent-cat-list input", function () {
        getSubCatList();
    });
    //多项选择服务内容
    $(document).on("click", ".select-service input", function () {
        
        if ($(this).prop("checked")) {
            
            // $(".cat-item").removeClass("active");
            $(this).parents().addClass("active");
            $(this).attr('checked','checked');
        } else {
            $(this).parent(".select-service").removeClass("active");
            $(this).removeAttr('checked');
        }
    });
    //服务内容设置
    $(document).on('click', '.addcat1', function () {
        page.select_i = $(this).data('index');
    });
    //选择分类
    // $(document).on("change", ".parents", function () {
    //     var parent_id=$(this).val();
    //     var html='<option value="0"></option>';
    //     $.ajax({
    //         url: "<?=$urlManager->createUrl(['mch/goods/get-cat-list'])?>",
    //         data: {
    //             parent_id: parent_id,
    //         },
    //         success: function (res) {
    //             console.log(res);
    //             if (res.code == 0) {
    //                 $(".son").empty()
    //                 for (var i = 0; i < res.data.length; i++) {
    //                     html+='<option value="'+res.data[i].id+'">'+res.data[i].name+'</option>';
    //                 }
    //                 $(".son").html(html)
    //             }
    //         }
    //     });
    // });
    //分类设置
    $(document).on('click', '.cat-modal', function () {
        page.select_i = $(this).data('index');
    });
    //选择分类
    $(document).on("click", ".cat-confirm", function () {
        var cat_name = $.trim($(".select-cat.active").text());
        var cat_id = $(".select-cat.active input").val();
        if (cat_name && cat_id) {
            page.goods_cat_list[page.select_i]['cat_id'] = cat_id;
            page.goods_cat_list[page.select_i]['cat_name'] = cat_name;
        }
        $("#catModal").modal("hide");
    });
    //删除父分类
    // $(document).on("click", ".delete-cat-parents", function () {
    //     $('.parents option:first').attr('selected','selected');
    //     $('.son option:first').attr('selected','selected');
    // });
    // //删除子分类
    // $(document).on("click", ".delete-cat-son", function () {
    //     $('.son option:first').attr('selected','selected');
    // });
    //添加新分类
    $(document).on('click', '.addcat', function () {
        var cat = {};
        cat.cat_name = '';
        cat.cat_id = '';
        page.goods_cat_list.push(cat);
    });
    //删除分类
    $(document).on('click', '.delete-cat', function () {
        page.goods_cat_list.splice($(this).data('index'), 1);
        if (page.goods_cat_list.length == 0) {
            var cat = {};
            cat.cat_name = '';
            cat.cat_id = '';
            page.goods_cat_list.push(cat);
        }
    });
    
    //选择分类
    $(document).on("click", ".cat-confirm1", function () {
        
        // var cat_id = new Array();
        // $(".cat-item.active input").each(function(i){
        //     cat_id[i] = $(this).val();
        // });
        

        var cat_name = new Array();
        $(".select-service.active").each(function(i){
            cat_name[i] = $.trim($(this).text());
        });
        var cat_name_vals = cat_name.join(",");
        $("#service_name_arr").val(cat_name_vals);
        var html='';
        $(".service1").empty()
        for (var i = 0; i < cat_name.length; i++) {
            html+='<span class="input-group-addon-service">'+cat_name[i]+'<span class="delelt-span">×</span></span>';
        }
        html+='<a class="select-service addcat" data-toggle="modal" data-target="#catModal1" style="color: #0275d8;">选择</a>';
        $(".service1").html(html)
        $("#catModal1").modal("hide");
    });
    //删除服务内容
    $(document).on('click', '.delelt-span', function () {
        $(this).parent('span').remove();
        var span_text=$(this).parent('span').text();

        var span_text=span_text.substring(0,span_text.length-1)

        var service_name_arr=$("#service_name_arr").val();
        var arr=service_name_arr.split(',');
        arr.splice($.inArray(span_text,arr),1);
        var cat_name_vals = arr.join(",");
        $("#service_name_arr").val(cat_name_vals);
        // for(var i in service_name_arr.split(',')){
        //     alert(arr[i])
        // }
    });
    //添加新分类
    // $(document).on('click', '.addcat', function () {
    //     var cat = {};
    //     cat.cat_name = '';
    //     cat.cat_id = '';
    //     page.goods_cat_list.push(cat);
    // });
    //删除分类
    // $(document).on('click', '.delete-cat', function () {
    //     page.goods_cat_list.splice($(this).data('index'), 1);
    //     if (page.goods_cat_list.length == 0) {
    //         var cat = {};
    //         cat.cat_name = '';
    //         cat.cat_id = '';
    //         page.goods_cat_list.push(cat);
    //     }
    // });

    function getSubCatList() {
        var parent_id = $(".parent-cat-list input:checked").val();
        page.sub_cat_list = [];
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/goods/get-cat-list'])?>",
            data: {
                parent_id: parent_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    page.sub_cat_list = res.data;
                }
            }
        });
    }

    getSubCatList();


    $(document).on("change", ".attr-select", function () {
        var name = $(this).attr("data-name");
        var id = $(this).val();
        if ($(this).prop("checked")) {
        } else {
        }
    });

    $(document).on("click", ".add-attr-group-btn", function () {
        var name = $(".add-attr-group-input").val();
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list.push({
            attr_group_name: name,
            attr_list: [],
        });
        $(".add-attr-group-input").val("");
        page.checked_attr_list = getAttrList();
    });

    $(document).on("click", ".add-attr-btn", function () {
        var name = $(this).parents(".attr-input-group").find(".add-attr-input").val();
        var index = $(this).attr("index");
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list[index].attr_list.push({
            attr_name: name,
        });

        // 如果是单规格的，添加新规格时不清空原先的数据
        page.old_checked_attr_list = page.checked_attr_list;
        page.attr_group_count = page.attr_group_list.length;
        var attrList = getAttrList();
        if (page.attr_group_list.length === 1) {
            for (var i in attrList) {
                if (i > page.old_checked_attr_list.length - 1) {
                    page.old_checked_attr_list.push(attrList[i])
                }
            }
            var newCheckedAttrList = page.old_checked_attr_list;
        } else if (page.attr_group_list.length === page.attr_group_count) {
            for (var pi in attrList) {
                var pAttrName = '';
                for (var pj in attrList[pi].attr_list) {
                    pAttrName += attrList[pi].attr_list[pj].attr_name
                }
                for (var ci in page.old_checked_attr_list) {
                    var cAttrName = '';
                    for (var cj in page.old_checked_attr_list[ci].attr_list) {
                        cAttrName += page.old_checked_attr_list[ci].attr_list[cj].attr_name;
                    }
                    if (pAttrName === cAttrName) {
                        attrList[pi] = page.old_checked_attr_list[ci];
                    }
                }
            }
            var newCheckedAttrList = attrList;
        } else {
            var newCheckedAttrList = attrList;
        }
        $(this).parents(".attr-input-group").find(".add-attr-input").val("");
        page.checked_attr_list = newCheckedAttrList;
    });


    $(document).on("click", ".attr-group-delete", function () {
        var index = $(this).attr("index");
        page.attr_group_list.splice(index, 1);
        page.checked_attr_list = getAttrList();
    });

    $(document).on("click", ".attr-delete", function () {
        var index = $(this).attr("index");
        var group_index = $(this).attr("group-index");
        page.attr_group_list[group_index].attr_list.splice(index, 1);

        // 如果是单规格的，删除规格时不清空原先的数据
        page.old_checked_attr_list = page.checked_attr_list;
        var attrList = getAttrList();
        if (page.attr_group_list.length === 1) {
            var newCheckedAttrList = [];
            for (var i in page.attr_group_list[0].attr_list) {
                var attrName = page.attr_group_list[0].attr_list[i].attr_name;
                for (j in page.old_checked_attr_list) {
                    var oldAttrName = page.old_checked_attr_list[j].attr_list[0].attr_name;
                    if (attrName === oldAttrName) {
                        newCheckedAttrList.push(page.old_checked_attr_list[j]);
                        break;
                    }
                }
            }
        } else if (page.attr_group_list.length === page.attr_group_count) {
            for (var pi in attrList) {
                var pAttrName = '';
                for (var pj in attrList[pi].attr_list) {
                    pAttrName += attrList[pi].attr_list[pj].attr_name
                }
                for (var ci in page.old_checked_attr_list) {
                    var cAttrName = '';
                    for (var cj in page.old_checked_attr_list[ci].attr_list) {
                        cAttrName += page.old_checked_attr_list[ci].attr_list[cj].attr_name;
                    }
                    if (pAttrName === cAttrName) {
                        attrList[pi] = page.old_checked_attr_list[ci];
                    }
                }
            }
            var newCheckedAttrList = attrList;
        } else {
            var newCheckedAttrList = attrList;
        }

        page.checked_attr_list = newCheckedAttrList;
    });


    function getAttrList() {
        var array = [];
        for (var i in page.attr_group_list) {
            for (var j in page.attr_group_list[i].attr_list) {
                var object = {
                    attr_group_name: page.attr_group_list[i].attr_group_name,
                    attr_id: null,
                    attr_name: page.attr_group_list[i].attr_list[j].attr_name,
                };
                if (!array[i])
                    array[i] = [];
                array[i].push(object);
            }
        }
        var len = array.length;
        var results = [];
        var indexs = {};

        function specialSort(start) {
            start++;
            if (start > len - 1) {
                return;
            }
            if (!indexs[start]) {
                indexs[start] = 0;
            }
            if (!(array[start] instanceof Array)) {
                array[start] = [array[start]];
            }
            for (indexs[start] = 0; indexs[start] < array[start].length; indexs[start]++) {
                specialSort(start);
                if (start == len - 1) {
                    var temp = [];
                    for (var i = len - 1; i >= 0; i--) {
                        if (!(array[start - i] instanceof Array)) {
                            array[start - i] = [array[start - i]];
                        }
                        if (array[start - i][indexs[start - i]]) {
                            temp.push(array[start - i][indexs[start - i]]);
                        }
                    }
                    var key = [];
                    for (var i in temp) {
                        key.push(temp[i].attr_id);
                    }
                    var oldVal = map.get(key.sort().toString());
                    if (oldVal) {
                        results.push({
                            num: oldVal.num,
                            price: oldVal.price,
                            no: oldVal.no,
                            pic: oldVal.pic,
                            attr_list: temp
                        });
                    } else {
                        var obj = {
                            num: 0,
                            price: 0,
                            no: '',
                            pic: '',
                            attr_list: temp,
                            share_commission_first: '',
                            share_commission_second: '',
                            share_commission_third: '',
                        };

                        var levelList = page.level_list;
                        for (var i = 0; i < levelList.length; i++) {
                            var keyName = 'member' + levelList[i].id;
                            obj[keyName] = '';
                        }

                        results.push(obj);
                    }
                }
            }
        }

        specialSort(-1);
        return results;
    }

    $(document).on("change", "input[name='model[quick_purchase]']", function () {
        setShareCommissions();
    });
    setShareCommissions();

    function setShareCommissions() {
        if ($("input[name='model[quick_purchase]']:checked").val() == 1) {
            $(".share-commissions").show();
        } else {
            $(".share-commissions").hide();
        }
    }

</script>
<script>
    $(document).on('change', '.video', function () {
        $('.video-check').attr('href', this.value);
    });
</script>
<script>
    $(document).on('click', '.copy-btn', function () {
        var btn = $(this);
        var url = $(btn.parent().prev()[0]).val();
        var error = $('.copy-error');
        error.prop('hidden', true);
        if (url == '' || url == undefined) {
            error.prop('hidden', false).html('请填写宝贝链接');
            return;
        }
        btn.btnLoading('信息获取中');
        $.myLoading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/goods/copy'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                url: url,
            },
            success: function (res) {
                $.myLoadingHide();
                btn.btnReset();
                if (res.code == 0) {
                    $("input[name='model[name]']").val(res.data.title);
                    $("input[name='model[virtual_sales]']").val(res.data.sale_count);
                    $("input[name='model[price]']").val(res.data.sale_price);
                    $("input[name='model[original_price]']").val(res.data.price);
                    page.attr_group_list = res.data.attr_group_list;
                    page.checked_attr_list = getAttrList();
                    ue.setContent(res.data.detail_info + "");
                    var pic = res.data.picsPath;

                    if (pic) {
                        var cover_pic = $("input[name='model[cover_pic]']");
                        var cover_pic_next = $(cover_pic.parent().next('.upload-preview')[0]).children('.upload-preview-img');
                        cover_pic.val(pic[0]);
                        $(cover_pic_next).prop('src', pic[0]);
                        if (pic.length > 1) {
                            var goods_pic_list = $(".upload-preview-list");
                            goods_pic_list.empty();
                            $(pic).each(function (i) {
                                var goods_pic = ' <div class="upload-preview text-center">' +
                                    '<input type="hidden" class="file-item-input" name="model[goods_pic_list][]" value="' + pic[i] + '"> ' +
                                    '<span class="file-item-delete">&times;</span> <span class="upload-preview-tip">750&times;750</span> ' +
                                    '<img class="upload-preview-img" src="' + pic[i] + '"> ' +
                                    '</div>';
                                goods_pic_list.append(goods_pic);
                            });
                        }
                    }


                } else {
                    error.prop('hidden', false).html(res.msg);
                }
            }
        });
    });

    //卡券设置
    $(document).on('click', '.card-add', function () {
        var index = $('.card-list').val();
        if (index == -1) {
            return;
        }
        page.goods_card_list.push(page.card_list[index]);
    });
    $(document).on('click', '.card-del', function () {
        var index = $(this).data('index');
        page.goods_card_list.splice(index, 1);
    })
</script>
<script>
    $(document).on('click', '.copy-btn-1', function () {
//        var url = $('.copy-url').val();
        var btn = $(this);
        var url = $(btn.parent().prev()[0]).val();
        var error = $('.copy-error');
        error.prop('hidden', true);
        if (url == '' || url == undefined) {
            error.prop('hidden', false).html('请填写宝贝链接');
            return;
        }
        var url_arr = url.split("?");
        var theRequest = {};
        if (url_arr.length >= 2) {
            var strs = url_arr[1].split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }
        var id = theRequest.id;
        btn.btnLoading('信息获取中');
        $.myLoading();
        $.ajax({
            url: "http://hws.m.taobao.com/cache/wdetail/5.0/?id=" + id,
            dataType: 'jsonp',
            beforeSend: function (request) {
                request.setRequestHeader("Referer", "http://hws.m.taobao.com");
            },
            success: function (res) {
                $.ajax({
                    url: "<?=$urlManager->createUrl(['mch/goods/tcopy'])?>",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        html: res,
                        _csrf: _csrf
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        btn.btnReset();
                        if (res.code == 0) {
                            $("input[name='model[name]']").val(res.data.title);
                            $("input[name='model[virtual_sales]']").val(res.data.sale_count);
                            $("input[name='model[price]']").val(res.data.sale_price);
                            $("input[name='model[original_price]']").val(res.data.price);
                            page.attr_group_list = res.data.attr_group_list;
                            page.checked_attr_list = res.data.checked_attr_list;
                            ue.setContent(res.data.detail_info + "");
                            var pic = res.data.picsPath;

                            if (pic) {
                                var cover_pic = $("input[name='model[cover_pic]']");
                                var cover_pic_next = $(cover_pic.parent().next('.upload-preview')[0]).children('.upload-preview-img');
                                cover_pic.val(pic[0]);
                                $(cover_pic_next).prop('src', pic[0]);
                                if (pic.length > 1) {
                                    var goods_pic_list = $(".upload-preview-list");
                                    goods_pic_list.empty();
                                    $(pic).each(function (i) {
                                        if (i == 0) {
                                            return true;
                                        }
                                        var goods_pic = ' <div class="upload-preview text-center">' +
                                            '<input type="hidden" class="file-item-input" name="model[goods_pic_list][]" value="' + pic[i] + '"> ' +
                                            '<span class="file-item-delete">&times;</span> <span class="upload-preview-tip">750&times;750</span> ' +
                                            '<img class="upload-preview-img" src="' + pic[i] + '"> ' +
                                            '</div>';
                                        goods_pic_list.append(goods_pic);
                                    });
                                }
                            }


                        } else {
                            error.prop('hidden', false).html(res.msg);
                        }
                    }
                });
            }
        });
    });
</script>
<!-- 规格图片 -->
<script>
    $(document).on('click', '.upload-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.upload_file({
            accept: 'image/*',
            start: function (res) {
                btn.btnLoading('');
            },
            success: function (res) {
                if (res.code === 1) {
                    $.alert({
                        content: res.msg
                    });
                    return;
                }
                input.val(res.data.url).trigger('change');
                page.checked_attr_list[index].pic = res.data.url;
            },
            complete: function (res) {
                btn.btnReset();
            },
        });
    });
    $(document).on('click', '.select-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.select_file({
            success: function (res) {
                input.val(res.url).trigger('change');
                page.checked_attr_list[index].pic = res.url;
            }
        });
    });
    $(document).on('click', '.delete-attr-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        input.val('').trigger('change');
        page.checked_attr_list[index].pic = '';
    });
</script>
<!--批量设置-->
<script>
    $(document).on('click', '.bat', function () {
        var type = $(this).data('index');
        var val = $($(this).parent().prev('input')).val();
        for (var i in page.checked_attr_list) {
            if (type == 0) {
                val >= 0 ? page.checked_attr_list[i].num = parseInt(val) : page.checked_attr_list[i].num = 1;
            } else if (type == 1) {
                val >= 0.01 ? page.checked_attr_list[i].price = val : page.checked_attr_list[i].price = 0.01;
            } else if (type == 2) {
                page.checked_attr_list[i].no = val
            }
        }
    });
</script>

<script>
    var sort = Sortable.create(document.getElementById('sortList'), {
        animation: 250,
    }); // That's all.

    $(document).on('click', '.mall-copy-btn', function () {
        var mall_id = $('.copy-mall-id').val();
        var btn = $(this);
        var error = $('.copy-error');
        error.prop('hidden', true);
        if (mall_id == '' || mall_id == undefined) {
            error.prop('hidden', false).html('请填写商城商品ID');
            return;
        }
        btn.btnLoading('信息获取中');
        $.myLoading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/goods/goods-copy'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                mall_id: mall_id,
            },
            success: function (res) {
                $('.no-mall-get').hide();
                $('.mall-get').show();
                $.myLoadingHide();
                btn.btnReset();
                if (res.code == 0) {
                    $("input[name='model[name]']").val(res.data.name);
                    $("input[name='model[virtual_sales]']").val(res.data.virtual_sales);
                    $("input[name='model[price]']").val(res.data.price);
                    $("input[name='model[original_price]']").val(res.data.original_price);
                    $("input[name='model[unit]']").val(res.data.unit);
                    $("input[name='model[weight]']").val(res.data.weight);
                    $("input[name='model[service]']").val(res.data.service);
                    $("input[name='model[sort]']").val(res.data.sort);
                    page.attr_group_list = JSON.parse(res.data.attr_group_list, true);//可选规格数据
                    page.checked_attr_list = JSON.parse(res.data.checked_attr_list, true);//已选规格数据
                    ue.setContent(res.data.detail + "");
                    var pic = res.data.pic;

                    if (pic) {
                        var cover_pic = $("input[name='model[cover_pic]']");
                        var cover_pic_next = $(cover_pic.parent().next('.upload-preview')[0]).children('.upload-preview-img');
                        cover_pic.val(res.data.cover_pic);
                        $(cover_pic_next).prop('src', res.data.cover_pic);
                    }
                    if (pic.length >= 1) {
                        var goods_pic_list = $(".upload-preview-list");
                        goods_pic_list.empty();
                        $(pic).each(function (i) {
                            var goods_pic = ' <div class="upload-preview text-center">' +
                                '<input type="hidden" class="file-item-input" name="model[goods_pic_list][]" value="' + pic[i] + '"> ' +
                                '<span class="file-item-delete">&times;</span> <span class="upload-preview-tip">750&times;750</span> ' +
                                '<img class="upload-preview-img" src="' + pic[i] + '"> ' +
                                '</div>';
                            goods_pic_list.append(goods_pic);
                        });
                    }

                } else {
                    error.prop('hidden', false).html(res.msg);
                }
            }
        });
    });
</script>
