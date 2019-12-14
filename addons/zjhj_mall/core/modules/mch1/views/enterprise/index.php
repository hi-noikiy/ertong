<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: wxf
 * Date: 2017/6/19
 * Time: 16:52
 */
use yii\widgets\LinkPager;
$this->title = '企业用户审核';
$urlManager = Yii::$app->urlManager;
?>
<style>
    .sea{
        width:100%;
        height:auto;
        border:1px solid #999;
        border-top:none;
        list-style-type:none;
        margin:0;
        padding:0;
    }
    .seali{
        width:100%;
        height:2rem;
        line-height:2rem;
        font-size:14px;
    }
    .seali:hover{
        background:#ddd;
    }
</style>
<div class="panel mb-3">
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>昵称</th>
                <th>绑定手机号</th>
                <th>上级</th>
                <th>申请日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $key => $item) : ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $item['username'] ?></td>
                    <td><?= $item['binding'] ?></td>
                    <td><?= $item['nickname'] ?></td>
                    <td><?= $item['addtime'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/enterprise/detail', 'id' => $item['id']]) ?>">查看</a>
                        
                        <?php if ($item['status']==1): ?>
                            <a class="btn btn-sm btn-success rechangeBtn"
                                data-toggle="modal"
                                href="javascript:;"
                                data-parentid="<?= $item['parent_user_id'] ?>"
                                data-userid="<?= $item['user_id'] ?>"
                                data-id="<?= $item['id'] ?>">通过</a>
                            <a class="btn btn-sm btn-primary rechargeMoney"
                                data-toggle="modal" data-target="#refuse"
                                href="javascript:;"
                                data-id="<?= $item['id'] ?>">拒绝</a>
                        <?php endif; ?>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
                ?>
            </nav>
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
    </div>
</div>
<!-- 通过 -->
<div class="modal" id="adopt" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">通过审核</h5>
                <button type="button" class="close close-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group short-row">
                    <label class="custom-control custom-radio">
                        <input name="rechangeType" type="radio" class="custom-control-input relation" value="1">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">不关联上级</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input name="rechangeType" type="radio" class="custom-control-input unrelated" value="2">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">关联上级</span>
                    </label>
                </div>
                <div class="form-group row">
                    
                    <div class="col-sm-6">
                        <input class="form-control address" value="">
                        <ul class="sea"></ul>
                    </div>
                </div>
                <div class="form-group row">
                    确认通过该企业的注册审核，并绑定所选上级？
                </div>
                <input type="hidden" id="user_id" value="">
                <input type="hidden" id="ent_id" value="">
                <input type="hidden" id="parent_id" value="">
                <div class="form-error text-danger mt-3 rechange-error" style="display: none">ddd</div>
                <div class="form-success text-success mt-3" style="display: none">sss</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-1" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary save-rechange">提交</button>
            </div>
        </div>
    </div>
</div>
<!-- 拒绝 -->
<div class="modal fade" data-backdrop="static" id="refuse">
        <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <b class="modal-title">拒绝审核</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input class="order-id" type="hidden">
                    <input class="url" type="hidden">
                    <div class="form-group row">
                        <label class="col-4 text-right col-form-label">请填写拒绝原因</label>
                        <div class="col-11" style="margin-left: 1rem;">
                        <textarea id="reason" name="reason" cols="90"
                                  rows="5"
                                  style="width: 100%;"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="uid" value="">
                    <div class="form-error text-danger mt-3 servicer-error" style="display: none">ddd</div>
                    <div class="form-success text-success mt-3" style="display: none">sss</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary remarks">提交</button>
                </div>
            </div>
        </div>
    </div>
<script>
    //未通过
    $(document).on('click', '.rechargeMoney', function () {
        var a = $(this);
        var id = a.data('id');
        $('#uid').val(id);
    });

    $(document).on('click', '.remarks', function () {
        var reason = $("#reason").val();
        console.log(reason)
        var id=$("#uid").val();
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/enterprise/edit-status']) ?>",
            type: "post",
            data: {
                reason: reason, id: id, status: 3, _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    $('.servicer-error').css('display', 'block');
                    $('.servicer-error').text(res.msg);
                }
            }
        });
    });
    //通过
    $(document).on('click', '.close-1', function () {
        $("#adopt").hide();
    });
    $(document).on("click", ".rechangeBtn", function () {
        var a = $(this);
        var id = a.data('id');
        var userid = a.data('userid');
        var parent_id=a.data('parentid');
        $('#ent_id').val(id);
        $('#user_id').val(userid);
        var reason='';
        if(parent_id <= 0){
            $("#adopt").show();
        }else{
            $.confirm({
                content: "确认通过该企业的注册审核？",
                confirm: function () {
                    $.ajax({
                        url: "<?= Yii::$app->urlManager->createUrl(['mch/enterprise/edit-status']) ?>",
                        type: "post",
                        data: {
                            reason: reason, id: id, status: 2, _csrf: _csrf
                        },
                        dataType: "json",
                        success: function (res) {
                            
                            if (res.code == 0) {
                                window.location.reload();
                            } else {
                                $('.servicer-error').css('display', 'block');
                                $('.servicer-error').text(res.msg);
                            }
                        }
                    });
                },
                cancel: function(){
                    console.log('111');
                }
            });
        }
        
        return false;
    });

    $(".address").bind("input", function() { 
        if($(this).val().length>0){
            search();
        }else{
            $(".sea").html('');
     
        }
    })
    function search(){
        $.ajax({
            type:"post",
            url:"<?= $urlManager->createUrl(['mch/enterprise/invitation-code-list']) ?>",
            data:{
                user_id:$("#user_id").val(),
                invit_code:$(".address").val(),
                _csrf: _csrf
            },
            dataType: "json",
            
            success:function(response){
                console.log(response)
                var str="";
                if(response.code==0){
                    for(var i=0;i<response.data.length;i++){
                        str += "<li class='seali' attr-id='"+ response.data[i].id +"'><div style='text-align:left;padding-left:0.5rem;'><span>" + response.data[i].username + "</span></div></li>";
                    }
                }
                
 
                $(".sea").html(str);
            }
        })
    }
    $(document).on("click", ".seali", function () {
        $(".sea").hide()
        $(".address").val($(this).text())
        $("#parent_id").val($(this).attr('attr-id'))
        $(".address").attr('readonly',true);
        console.log($(this).text())
        // alert($(this).html())
    });
    $(document).on('click', '.save-rechange', function () {
        var rechangeType=$('input[name="rechangeType"]:checked').val();
        
        var id=$("#ent_id").val();
        var parent_id=$("#parent_id").val();
        var address=$(".address").val();
        console.log(parent_id)
        if(rechangeType!=2){
            alert('请选择是否关联上级')
            return false;
        }
        if(address==''){
            alert('请输入有效的邀请码')
            return false;
        }
        if(parent_id==''){
            alert('请选择要关联的上级')
            return false;
        }
        
        
        var reason='';
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/enterprise/edit-status']) ?>",
            type: "post",
            data: {
                reason: reason, id: id, status: 2, parent_id: parent_id, _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    $('.servicer-error').css('display', 'block');
                    $('.servicer-error').text(res.msg);
                }
            }
        });
    });
</script>
