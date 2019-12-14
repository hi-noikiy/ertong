<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$cat = [
    1 => '关于我们',
    2 => '服务中心',
];
$cat_id = Yii::$app->request->get('cat_id', 2);
$urlManager = Yii::$app->urlManager;
$this->title = $cat[$cat_id];
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl(['mch/article/index', 'cat_id' => $cat_id]);
}
$this->params['page_navs'] = [
    [
        'name' => '关于我们',
        'active' => $cat_id == 1,
        'url' => $urlManager->createUrl(['mch/article/index', 'cat_id' => 1,]),
    ],
    [
        'name' => '服务中心',
        'active' => $cat_id == 2,
        'url' => $urlManager->createUrl(['mch/article/index', 'cat_id' => 2,]),
    ],
];
?>
<style>
    .col-sm-6{
        max-width: 24%;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">标题</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control cat-name" name="title" value="<?= $model['title'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control cat-name" name="sort"
                           value="<?= $model['sort'] ? $model['sort'] : 100 ?>">
                </div>
            </div>
            <?php if ($model['content']) :?>
                <?php foreach ($model['content'] as $key=>$item) : ?>
                    <span class="_listBox">
                        <div class="list-item">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label _question">问题<i><?= $key+1 ?></i></label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control cat-name question" name="content[question][]" value="<?= $item['question'] ?>" >
                                </div>
                            </div>
                        </div>
                        <div class="list-item">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label _answer">回答<i><?= $key+1 ?></i></label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control cat-name answer" name="content[answer][]" value="<?= $item['answer'] ?>" >
                                </div>
                            
                                <div class="col-sm-6">
                                    <a href="javascript:;" class="_move _up" data-type="up">上移</a>
                                    <a href="javascript:;" class="_move _down" data-type="down">下移</a>
                                    <a href="javascript:;" class="_delete">删除</a>
                                </div>
                            </div>
                        </div>
                    </span>
                <?php endforeach; ?>
            <?php else : ?>
                <span class="_listBox">
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _question">问题<i>1</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name question" name="content[question][]" >
                            </div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _answer">回答<i>1</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name answer" name="content[answer][]" >
                            </div>
                        
                            <div class="col-sm-6">
                                <a href="javascript:;" class="_move _up" data-type="up">上移</a>
                                <a href="javascript:;" class="_move _down" data-type="down">下移</a>
                                <a href="javascript:;" class="_delete">删除</a>
                            </div>
                        </div>
                    </div>
                </span>
                <span class="_listBox">
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _question">问题<i>2</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name question" name="content[question][]" >
                            </div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _answer">回答<i>2</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name answer" name="content[answer][]" >
                            </div>
                        
                            <div class="col-sm-6">
                                <a href="javascript:;" class="_move _up" data-type="up">上移</a>
                                <a href="javascript:;" class="_move _down" data-type="down">下移</a>
                                <a href="javascript:;" class="_delete">删除</a>
                            </div>
                        </div>
                    </div>
                </span>
                <span class="_listBox">
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _question">问题<i>3</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name question" name="content[question][]" >
                            </div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label _answer">回答<i>3</i></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control cat-name answer" name="content[answer][]" >
                            </div>
                        
                            <div class="col-sm-6">
                                <a href="javascript:;" class="_move _up" data-type="up">上移</a>
                                <a href="javascript:;" class="_move _down" data-type="down">下移</a>
                                <a href="javascript:;" class="_delete">删除</a>
                            </div>
                        </div>
                    </div>
                </span>
            <?php endif; ?>
            <div class="list-item">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label _question"></label>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:;" class="_addDiv">添加Q&A</a>
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script>
    $(function(){
        var question = {
            addDiv: function(){
                var index = $('._listBox').length;
                
                var html = `<span class="_listBox">
                                <div class="list-item">
                                    <div class="form-group row">
                                        <div class="form-group-label col-sm-2 text-right">
                                            <label class="col-form-label _question">问题<i>${index+1}</i></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control cat-name question" name="content[question][]" >
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item">
                                    <div class="form-group row">
                                        <div class="form-group-label col-sm-2 text-right">
                                            <label class="col-form-label _answer">回答<i>${index+1}</i></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control cat-name answer" name="content[answer][]" >
                                        </div>
                                    
                                        <div class="col-sm-6">
                                            <a href="javascript:;" class="_move _up" data-type="up">上移</a>
                                            <a href="javascript:;" class="_move _down" data-type="down">下移</a>
                                            <a href="javascript:;" class="_delete">删除</a>
                                        </div>
                                    </div>
                                </div>
                            </span>`;

                $(this).parents('.list-item').before(html)
                $('._listBox').each(function(index,item){
                    $(this).find('._up').show();
                    $(this).find('._down').show();
                })
                question.changeDiv()
            },
            questionsMove: function(){

                var type = $(this).data('type'),
                    $parent = $(this).parents('._listBox'),
                    index = $parent.index()-2;
                    changeIndex = type=='up'?index-1:index+1,
                    cloneDiv = $parent.html(),
                    question1 = $parent.find('.question').val(),
                    answer = $parent.find('.answer').val(),
                    changeDiv =  $('.panel-body ._listBox').eq(changeIndex).html();
                    // console.log(changeDiv)
                    // console.log(question1)
                    // console.log(answer)
                $parent.find('._question i').text(changeIndex+1);
                $parent.find('._answer i').text(changeIndex+1);

                $('.panel-body ._listBox').eq(changeIndex).html(cloneDiv);
            
                $parent.html(changeDiv);
                question.changeDiv()
            },
            deletedDiv: function(){
                $(this).parents('._listBox').remove();
                question.changeDiv()
            },
            changeDiv: function(){
                $('._move').show();
                $('._listBox').eq(0).find('._up').hide();
                
                $('._listBox:last').find('._down').hide();
            },
            init: function(){
                question.changeDiv()
            }
        }
        question.init();
        $(document)
            .on('click', '._addDiv', question.addDiv)
            .on('click', '._move', question.questionsMove)
            .on('click', '._delete', question.deletedDiv)
    })
</script>