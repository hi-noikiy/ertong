<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%official}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $pic_url
 * @property string $content
 * @property integer $is_delete
 * @property integer $addtime
 */
class Official extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%official}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['official_title', 'official_content'], 'required'],
            [['official_sort',], 'integer','min'=>0 ,'max'=>99999999],
            [['official_content'], 'string'],
            [['official_title'], 'string', 'max' => 255],
            [['addtime', 'is_show', 'is_delete'],'integer'],
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
