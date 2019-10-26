<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/24
 * Time: 10:37
 */

namespace app\modules\api\models;

use app\models\SmsRecord;

class SmsMobileRecord extends ApiModel
{
    public $mobile;

    public $content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addtime'], 'integer'],
            [['mobile', 'tpl', 'ip'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 1000],
        ];
    }

    
    public function getMobileCode($mobile,$content){

        $sms_record = SmsRecord::find()->where(['mobile' => $mobile, 'tpl' => 'SMS_116750368'])->andWhere(['>','addtime',time()])->asArray()->one();
        
        $code_content=$sms_record['content'];
        $code=json_decode($code,true);
        
        if($content==$code['code']){
            return true;
        }else{
            return false;
        }
    }
}
