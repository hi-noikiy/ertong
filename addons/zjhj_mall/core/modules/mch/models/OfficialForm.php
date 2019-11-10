<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 19:32
 */

namespace app\modules\mch\models;

use app\models\Official;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * @property Official $model
 */
class OfficialForm extends MchModel
{

    public $official;
    public $is_show;
    public $keyword;

    public $official_title;
    public $official_sort;
    public $official_content;
    public $addtime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['official_title'], 'required'],
            [['official_sort',], 'integer','min'=>0 ,'max'=>99999999],
            [['official_content'], 'string'],
            [['official_title'], 'string', 'max' => 255],
            // [['addtime', 'is_show', 'is_delete'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'official_title' => '新闻标题',
            'official_content' => '新闻内容',
            'official_sort' => '新闻排序',
            'addtime' => '添加时间',
            'is_show' => '是否显示0未显示1未显示',
            'is_delete'=>'是否删除0未删除1已删除',
        ];
    }

    public function GetList()
    {
        $list=array();
        $is_show = $this->is_show;
        $keyword = $this->keyword;
        $query=Official::find()->where(['is_delete'=>0]);

        if ($is_show != null) {
            $query->andWhere('is_show=:is_show', [':is_show' => $is_show]);
        }
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'official_title', $keyword]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'route' => \Yii::$app->requestedRoute]);
        $list = $query->orderBy('official_sort ASC,addtime DESC')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        $officialList = ArrayHelper::toArray($list);
        foreach ($officialList as $key => $val) {
            $officialList[$key]['addtime']=date('Y-m-d H:i:s',$val['addtime']);
            if($val['is_show']==1){
                $officialList[$key]['status']=显示;
            }else{
                $officialList[$key]['status']=隐藏;
            }
            
        }
        return [
            'list' => $officialList,
            'pagination' => $pagination
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $_this_attributes = $this->attributes;

            if($_this_attributes['official_title'] === null || $_this_attributes['official_title'] === ''){
                return [
                    'code' => 1,
                    'msg' => '请填写新闻标题',
                ];
            }
            $official = $this->official;

            $official->official_title = $_this_attributes['official_title'];
            $official->official_sort = $_this_attributes['official_sort'];
            //去除部分emoji
            function userTextEncode($str){
                if(!is_string($str)) return $str;
                if(!$str || $str=='undefined')return '';
                $text = json_encode($str); //暴露出unicode
                $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
                    return addslashes($str[0]);
                   },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
                return json_decode($text);
            };

            $official->official_content = preg_replace('/\\\u[a-z0-9]{4}/', '', userTextEncode($_this_attributes['official_content']));

            $official->addtime = time();

            if ($official->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return $this->getErrorResponse($this->official);
            }
        } else {
            return $this->errorResponse;
        }
    }
}
