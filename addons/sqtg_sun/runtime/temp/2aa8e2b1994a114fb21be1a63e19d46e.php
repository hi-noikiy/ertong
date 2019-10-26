<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/csystem/platform.html";i:1571855638;s:89:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/common/edit2.html";i:1571855638;s:93:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/common/copyright.html";i:1571855638;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>layui</title>
    <link rel="stylesheet" href="/addons/sqtg_sun/public/static/bower_components/layui/src/css/layui.css">
    <script src="/addons/sqtg_sun/public/static/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/addons/sqtg_sun/public/static/bower_components/layui/src/layui.js"></script>

    <link href="/addons/sqtg_sun/public/static/bower_components/select2/dist/css/select2.css" rel="stylesheet" />
    <script src="/addons/sqtg_sun/public/static/custom/pinyin.js"></script>

    <link href="/web/resource//css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="/web/resource//css/font-awesome.min.css" rel="stylesheet">-->
    <link href="/web/resource//css/common.css" rel="stylesheet">
    <script>

        window.sysinfo = {
            'siteroot': '<?php echo isset($_W['siteroot'])?$_W['siteroot']:''; ?>',
            'siteurl': '<?php echo isset($_W['siteurl'])?$_W['siteurl']:''; ?>',
            'attachurl': '<?php echo isset($_W['attachurl'])?$_W['attachurl']:''; ?>',
            'attachurl_local': '<?php echo isset($_W['attachurl_local'])?$_W['attachurl_local']:''; ?>',
            'attachurl_remote': '<?php echo isset($_W['attachurl_remote'])?$_W['attachurl_remote']:''; ?>',
            'cookie' : {'pre': '<?php echo isset($_W['config']['cookie']['pre'])?$_W['config']['cookie']['pre']:''; ?>'},
            'account' : <?php  echo json_encode($_W['account']) ?>
        };
    </script>
    <script src="/web/resource//js/app/util.js"></script>
    <script src="/web/resource//js/app/common.min.js"></script>
    <script>var require = { urlArgs: 'v=20161011' };</script>
    <script src="/web/resource//js/require.js"></script>
    <script src="/web/resource//js/app/config.js"></script>
    <script>
        requireConfig.baseUrl = "/web/resource/js/app";
        requireConfig.paths.select2 = "/addons/sqtg_sun/public/static/bower_components/select2/dist/js/select2";
        require.config(requireConfig);

        require(['select2','bootstrap'], function () {
            $.fn.select2.defaults.set("matcher",function(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                if (data.keywords && data.keywords.indexOf(params.term) > -1 || data.text.indexOf(params.term) > -1) {
                    return data;
                }
                return null;
            });
        });
    </script>
    <style>
        body{
            min-width: 0px !important;
        }
        .select2{
            width: 100%;
        }
        .select2 .select2-selection{
            height: 38px;
            border-radius: 2px;
            /*border-color: rgb(230,230,230);*/
        }
        .select2 .select2-selection__rendered{
            line-height: 38px!important;
        }
        .select2 .select2-selection__arrow{
            height: 36px!important;
        }

        .layui-form-item .layui-form-label{
            width: 180px;
        }
        .layui-form-item .layui-input-block{
            margin-left: 210px;
        }
        .layui-form-item .layui-input-inline{
            margin-left: 30px;
        }
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">
    <div style="padding: 15px;">
        <form class="layui-form" method="post" action="<?php echo adminurl('save'); ?>">
            <input type="hidden" name="id" value="<?php echo isset($info['id'])?$info['id']:''; ?>">
            
<div class="layui-form-item">
    <label class="layui-form-label">平台名称</label>
    <div class="layui-input-block">
        <input autocomplete="off" type="text" name="pt_name" placeholder="请输入名称" class="layui-input" value="<?php echo isset($info['pt_name'])?$info['pt_name']:''; ?>">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">平台封面图</label>
    <div class="layui-input-block">
        <?php echo tpl_form_field_image('pt_pic', isset($info['pt_pic'])?$info['pt_pic']:'','/web/resource/images/nopic.jpg'); ?>
        <div class="layui-form-mid layui-word-aux">建议尺寸：68*68</div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">首页自定义标题</label>
    <div class="layui-input-block">
        <input autocomplete="off" type="text" name="index_title" placeholder="请输入名称" class="layui-input" value="<?php echo isset($info['index_title'])?$info['index_title']:''; ?>">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">后台顶部标题</label>
    <div class="layui-input-block">
        <input autocomplete="off" type="text" name="ht_title" placeholder="请输入名称" class="layui-input" value="<?php echo isset($info['ht_title'])?$info['ht_title']:''; ?>">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">腾讯地图key</label>
    <div class="layui-input-block">
        <input autocomplete="off" type="text" name="map_key" placeholder="请输入地图key" class="layui-input" value="<?php echo isset($info['map_key'])?$info['map_key']:''; ?>">
        <div class="layui-form-mid layui-word-aux">申请地址：<a href="https://lbs.qq.com/console/key.html">https://lbs.qq.com/console/key.html</a></div>
        <div style="clear: both;color: #FF5722;">
            <p>1、申请开发者密钥</p>
            <p>2、设置安全域名，在"微信公众平台|小程序"->"开发"->"开发设置"->"服务器域名"中设置request合法域名，添加，添加https://apis.map.qq.com</p>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">商品海报背景图</label>
    <div class="layui-input-block">
        <?php echo tpl_form_field_image('goods_pic_b', isset($info['goods_pic_b'])?$info['goods_pic_b']:'','/web/resource/images/nopic.jpg'); ?>
        <div class="layui-form-mid layui-word-aux">建议尺寸：750*1334</div>
        <div style="clear: both;color: #FF5722;">
            <p>*如需生成海报，请登录小程序后台</p>
            <p>--> 开发 --> 开发设置 --> 服务器域名 --> downloadFile合法域名</p>
            <p>添加此域名：https://wx.qlogo.cn</p>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">配送方式</label>
    <div class="layui-input-block">
        <input type="radio" name="delivery_type" value="0" title="仅自提" <?php echo !empty($info['delivery_type'])?"" : "checked"; ?>>
        <input type="radio" name="delivery_type" value="1" title="仅配送" <?php echo $info['delivery_type']==1?"checked" :""; ?>>
        <input type="radio" name="delivery_type" value="2" title="自提和配送" <?php echo $info['delivery_type']==2?"checked" :""; ?>>
        <div class="layui-form-mid layui-word-aux" style="clear: both;">
            开启配送后，用户可能填写超过配送范围的地址
        </div>
    </div>
</div>
<blockquote class="layui-elem-quote">奇推设置（奇推——专注于小程序的粉丝推送平台
    小程序可订阅，访客沉淀为精准粉丝，消息精准推送，召回粉丝，增加平台销售额）</blockquote>

<div class="layui-form-item">
    <label class="layui-form-label">是否开启奇推</label>
    <div class="layui-input-block">
        <input type="radio" name="qt_isopen" value="0" title="关闭" <?php echo !empty($info['qt_isopen'])?"" : "checked"; ?>>
        <input type="radio" name="qt_isopen" value="1" title="开启" <?php echo $info['qt_isopen']==1?"checked" :""; ?>>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">奇推appkey</label>
    <div class="layui-input-block">
        <input autocomplete="off" type="text" name="qt_appkey" placeholder="请输入 appkey" class="layui-input" value="<?php echo isset($info['qt_appkey'])?$info['qt_appkey']:''; ?>">
        <div class="layui-form-mid layui-word-aux" style="clear: both;">
            *奇推对应的小程序appkey
        </div>
        <div style="clear: both;color: #FF5722;">
            <p>1、前往 奇推官网推官网 <a target="blank" href="http://www.7tui.net">www.7tui.net</a> 注册账 注册账号，并添加小程序，然后在小程序列表中，将生成的小程序appkey复制过来</p>
            <p>2、前往微信小程序管理后台，设置——开发设置——服务器域名中，找到“request合法域名”，添加以下两个域名：www.7tui.net、push.7tui.net</p>
        </div>
    </div>
</div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="">立即提交</button>
                    <!--<button class="layui-btn layui-btn-primary" id="btnCancel">取消</button>-->
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    //JavaScript代码区域
    layui.use(['element','form'], function(){
        var element = layui.element;
        var form = layui.form;
        
        // 新增界面、保存、取消事件
        form.on('submit', function(data){
            if(!$(data.elem).is('button')){
                return false;
            }
            var data = data.field;
            var url = "<?php echo adminurl('save'); ?>";
            $.post(url,data,function(res){
                if (typeof res == 'string'){
                    res = $.parseJSON(res);
                }
                if (res.code == 0) {
                    layer.msg('保存成功',{icon: 6,anim: 6});
                    location.reload();
                }else{
                    layer.msg(res.msg,{icon: 5,anim: 6});
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
        

        $('#btnCancel').click(function(e){
            var index=parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        })
    })
</script>
<script>
    if(self != top){
        $(document).on('click',function () {
            $('body',top.document).click();
        })
    }
</script>
<div class="container-fluid footer text-center" role="footer">
    <div class="friend-link">
        <?php if(empty($_W['setting']['copyright']['footerright'])): ?>
            <a href="http://www.we7.cc">微信开发</a>
            <a href="http://s.we7.cc">微信应用</a>
            <a href="http://bbs.we7.cc">微擎论坛</a>
            <a href="http://s.we7.cc">联系客服</a>
        <?php else: ?>
            <?php echo $_W['setting']['copyright']['footerright']; endif; ?>
    </div>
    <div class="copyright">
        <?php if(empty($_W['setting']['copyright']['footerleft'])): ?>Powered by <a href="http://www.we7.cc"><b>微擎</b></a>  &copy; 2014-2015 <a href="http://www.we7.cc">www.we7.cc</a><?php else: ?><?php echo $_W['setting']['copyright']['footerleft']; endif; ?></div>
        <?php if(!empty($_W['setting']['copyright']['icp'])): ?><div>备案号：<a href="http://www.miitbeian.gov.cn" target="_blank"><?php echo $_W['setting']['copyright']['icp']; ?></a></div><?php endif; ?>
    </div>
    <?php if(!empty($_W['setting']['copyright']['statcode'])): ?>
        <?php echo $_W['setting']['copyright']['statcode']; endif; if(!empty($_GPC['m']) && !in_array($_GPC['m'], array('keyword', 'special', 'welcome', 'default', 'userapi')) || defined('IN_MODULE')): ?>
        <script>
            if(typeof $.fn.tooltip != 'function' || typeof $.fn.tab != 'function' || typeof $.fn.modal != 'function' || typeof $.fn.dropdown != 'function') {
                require(['bootstrap']);
            }
        </script>
    <?php endif; ?>
</div>
<script type="text/javascript" src="<?php echo $_W['siteroot']; ?>web/index.php?c=utility&a=visit&do=showjs&type={FRAME}"></script>
</body>
</html>