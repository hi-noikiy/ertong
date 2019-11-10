<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 19:32
 */

namespace app\modules\api\models;

use app\models\Official;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * @property Official $model
 */
class OfficialForm extends MchModel
{


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
}
