<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:87:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/index/home.html";i:1571855638;s:93:"/www/wwwroot/shop.ertongkeji.com/addons/sqtg_sun/application/admin/view/common/copyright.html";i:1571855638;}*/ ?>
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
    <link href="/web/resource//css/font-awesome.min.css" rel="stylesheet">
    <script src="/web/resource//js/app/util.js"></script>
    <script>var require = { urlArgs: 'v=20161011' };</script>
    <script src="/web/resource//js/require.js"></script>
    <script src="/web/resource//js/app/config.js"></script>

    <link href="/web/resource//css/common.css" rel="stylesheet">
    <script>
        // requireConfig.baseUrl = "/web/resource/js/app";
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
            overflow: auto!important;
        }
        /*数据块*/
        .data-block{
            text-align: center;
            padding: 10px 0;
        }
        .data-block .data-num{
            font-size: 2.25rem;
            line-height: 40px;
        }

        /*带图数据块*/
        .data-block.layui-row{
            height: 147px;
            border-right: 2px solid #f6f6f6;
            padding-top: 37px;
        }
        .data-block img{
            margin-top: 10px;
            margin-left: 60px;
        }

        /*用户*/
        .user-block{
            font-size: 0.8em;
        }
        .user-block img{
            width: 80px;
        }
    </style>
</head>
<body class="layui-layout-body" style="background-color: #f2f2f2">
<div class="layui-layout layui-layout-admin">
    <div class="layui-fluid" style="padding: 15px;">
        <div class="layui-row layui-col-space15">
            <?php if(!$_SESSION['admin']['store_id']): ?>
            <div class="layui-col-md4">
                <!--用户信息-->
                <div class="layui-card">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief">
                            用户信息
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-md3 layui-col-sm3 data-block">
                                <div class="data-num"><?php echo $user['today_count']; ?></div>
                                <div class="data-lavel">今日</div>
                            </div>
                            <div class="layui-col-md3 layui-col-sm3 data-block">
                                <div class="data-num"><?php echo $user['yesterday_count']; ?></div>
                                <div class="data-lavel">昨日</div>
                            </div>
                            <div class="layui-col-md3 layui-col-sm3 data-block">
                                <div class="data-num"><?php echo $user['month_count']; ?></div>
                                <div class="data-lavel">本月</div>
                            </div>
                            <div class="layui-col-md3 layui-col-sm3 data-block">
                                <div class="data-num"><?php echo $user['count']; ?></div>
                                <div class="data-lavel">总数</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; if($_SESSION['admin']['store_id']): ?>
            <div class="layui-col-md4">
                <!--商品信息-->
                <div class="layui-card">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief">
                            商品信息
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-md6 layui-col-sm6 data-block">
                                <div class="data-num"><?php echo $store->goods_count; ?></div>
                                <div class="data-lavel">在售商品数</div>
                            </div>
                            <div class="layui-col-md6 layui-col-sm6 data-block">
                                <div class="data-num"><?php echo $store->sale_count; ?></div>
                                <div class="data-lavel">已售商品数（已完成）</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="layui-col-md8">
                <!--订单信息-->
                <div class="layui-card">
                    <div class="layui-row">
                        <div class="layui-col-md4 layui-col-sm4">
                            <div class="data-block layui-row">
                                <div class="layui-col-md4 layui-col-sm4">
                                    <img src="/addons/sqtg_sun/public/static/images/paying.png">
                                </div>
                                <div class="layui-col-md8 layui-col-sm8">
                                    <div class="data-num"><?php echo $saledata8['dfk']; ?></div>
                                    <div class="data-lavel">待付款</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md4 layui-col-sm4">
                            <div class="data-block layui-row">
                                <div class="layui-col-md4 layui-col-sm4">
                                    <img src="/addons/sqtg_sun/public/static/images/sending.png">
                                </div>
                                <div class="layui-col-md8 layui-col-sm8">
                                    <div class="data-num"><?php echo $saledata8['dfh']; ?></div>
                                    <div class="data-lavel">待配送</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md4 layui-col-sm4">
                            <div class="data-block layui-row">
                                <div class="layui-col-md4 layui-col-sm4">
                                    <img src="/addons/sqtg_sun/public/static/images/aftersale.png">
                                </div>
                                <div class="layui-col-md8 layui-col-sm8">
                                    <div class="data-num"><?php echo $saledata8['dzt']; ?></div>
                                    <div class="data-lavel">待自提</div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="layui-col-md4 layui-col-sm4">-->
                            <!--<div class="data-block layui-row">-->
                                <!--<div class="layui-col-md4 layui-col-sm4">-->
                                    <!--<img src="/addons/sqtg_sun/public/static/images/moneyput.png">-->
                                <!--</div>-->
                                <!--<div class="layui-col-md8 layui-col-sm8">-->
                                    <!--<div class="data-num">1</div>-->
                                    <!--<div class="data-lavel">待提现</div>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
                <!--销售数据-->
                <div class="layui-card">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief" lay-filter="saledata">
                            销售数据
                            <ul class="layui-tab-title pull-right">
                                <li class="layui-this">今日</li>
                                <li>昨日</li>
                                <li>近 7 天</li>
                                <li>近 30 天</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-md4 layui-col-sm4 data-block">
                                <div class="data-num" id="salenum">0</div>
                                <div class="data-lavel">成交量（件）</div>
                            </div>
                            <div class="layui-col-md4 layui-col-sm4 data-block">
                                <div class="data-num" id="salemoney">0</div>
                                <div class="data-lavel">成交额（元）</div>
                            </div>
                            <div class="layui-col-md4 layui-col-sm4 data-block">
                                <div class="data-num" id="avesalemoney">0</div>
                                <div class="data-lavel">订单平均消费（元）</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--近七日走势图-->
                <div class="layui-card">
                    <?php 
                        $height = 348;
                        if($_SESSION['admin']['store_id']){
                            $height = 529;
                        }
                     ?>
                    <div id="container" style="height: <?php echo $height; ?>px;padding: 10px;"></div>
                    <script>
                        requireConfig.baseUrl = "/web/resource/js/app";
                        // requireConfig.paths.select2 = "/addons/sqtg_sun/public/static/bower_components/select2/dist/js/select2";
                        require.config(requireConfig);
                        require(['echarts'], function (echarts) {
                            var dom = document.getElementById("container");
                            var myChart = echarts.init(dom);
                            var app = {};
                            option = null;
                            option = {
                                title: {
                                    text: '近七日交易走势'
                                },
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data:['成交量','成交额']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                xAxis: {
                                    type: 'category',
                                    boundaryGap: false,
                                    data: <?php echo json_encode($saledata7['days']) ?>//['9-14','9-15','9-16','9-17','9-18','9-19','9-20']
                                },
                                yAxis: {
                                    type: 'value'
                                },
                                series: [
                                    {
                                        name:'成交量',
                                        type:'line',
                                        stack: '总量',
                                        data:<?php echo json_encode($saledata7['goods_counts']) ?>//[120, 132, 101, 134, 90, 230, 210],
                                    },
                                    {
                                        name:'成交额',
                                        type:'line',
                                        stack: '总量',
                                        data:<?php echo json_encode($saledata7['amount_sums']) ?>//[220, 182, 191, 234, 290, 330, 310]
                                    },
                                ]
                            };
                            ;
                            if (option && typeof option === "object") {
                                myChart.setOption(option, true);
                            }
                        });
                    </script>
                </div>

                <?php if(!$_SESSION['admin']['store_id']): ?>
                <!--用户购买力排行-->
                <div class="layui-card">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief" lay-filter="test1">
                            用户购买力排行
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <?php if(is_array($user_order) || $user_order instanceof \think\Collection || $user_order instanceof \think\Paginator): $i = 0; $__LIST__ = $user_order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div class="layui-col-md2 layui-col-sm2 user-block">
                                <img src="<?php echo $vo['img']; ?>">
                                <br><?php echo $vo['name']; ?> [ <?php echo $vo['amount']; ?> ] <br>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="layui-col-md6">
                <!--商品销售排行-->
                <div class="layui-card" style="height: 304px;">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief" lay-filter="saleorder">
                            商品销售排行
                            <ul class="layui-tab-title pull-right">
                                <li class="layui-this">今日</li>
                                <li>昨日</li>
                                <li>近 7 天</li>
                                <li>近 30 天</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <table class="table">
                            <thead>
                            <th>排名</th>
                            <th>商品名称</th>
                            <th>成交数量</th>
                            </thead>
                            <tbody id="goodsale">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--团长销售排行-->
                <div class="layui-card" style="height: 371px;">
                    <div class="layui-card-header">
                        <div class="layui-tab layui-tab-brief" lay-filter="leaderorder">
                            团长销售排行
                            <ul class="layui-tab-title pull-right">
                                <li class="layui-this">今日</li>
                                <li>本月</li>
                                <li>本年</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <table class="table">
                            <thead>
                            <th>排名</th>
                            <th>团长名称</th>
                            <th>成交额</th>
                            </thead>
                            <tbody id="leaderOrder">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('element', function(){
        var element = layui.element;
        //监听Tab切换，以改变地址hash值
        // element.on('tab(test1)', function(e){
        //     console.log(e);
        // });

        //销售数据
        function getSaleData(type) {
            $.ajax({
                url: "<?php echo adminurl('saleData'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {type: type},
                success: function (res) {
                    if(res.code== 0){
                        $('#salenum').html(res.data.salenum);
                        $('#salemoney').html(res.data.salemoney);
                        $('#avesalemoney').html(res.data.avesalemoney);
                    }
                }
            })
        }
        getSaleData(1);
        element.on('tab(saledata)', function(e){
            getSaleData(e.index+1);
        });
        //团长排行
        function getLeaderOrder(type) {
            $.ajax({
                url: "<?php echo adminurl('leaderOrder'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {type: type},
                success: function (res) {
                    if(res.code== 0){
                        var str = '';
                        for (var i in res.data) {
                            var num= i-0+1;
                            str +=  '<tr>' +
                                '<td>' + num + '</td>' +
                                '<td>' + res.data[i].leader_name + '</td>' +
                                '<td>' + res.data[i].amount + '</td>' +
                                '<tr>'
                        }
                        $('#leaderOrder').html(str);
                    }
                }
            })
        }
        getLeaderOrder(1)
        element.on('tab(leaderorder)', function(e){
            getLeaderOrder(e.index+1);
        });
        //商品销售排行
        function getSaleOrder(type) {
            $.ajax({
                url: "<?php echo adminurl('goodsSale'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {type: type},
                success: function (res) {
                    if(res.code== 0){
                        var str = '';
                        for (var i in res.data) {
                            var num= i-0+1;
                            str +=  '<tr>' +
                                '<td>' + num + '</td>' +
                                '<td>' + res.data[i].goods_name + '</td>' +
                                '<td>' + res.data[i].num + '</td>' +
                                '<tr>'
                        }
                        $('#goodsale').html(str);
                    }
                }
            })
        }
        getSaleOrder(1)
        element.on('tab(saleorder)', function(e){
            getSaleOrder(e.index+1);
        });
    });
    $(document).on('click',function () {
        $('body',top.document).click();
    })
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