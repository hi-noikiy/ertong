<?php

/**
 * Created by PhpStorm.
 * User: shizhongying
 * Date: 2017/8/6
 * Time: 19:17
 */
use Grafika\Grafika;

class AliyunSms {
    //阿里云短信业务
    public function _sendNewDySms($sms_phone, $accessKeyId, $accessKeySecret, $signName, $templateCode, $templateParam, $outId) {
        include_once IA_ROOT . '/addons/amouse_wxapp_card/vender/aliyun-php-sdk-core/Config.php';
        include_once IA_ROOT . '/addons/amouse_wxapp_card/vender/aliyun-php-sdk-core/Request/V20170525/SendSmsRequest.php';
        include_once IA_ROOT . '/addons/amouse_wxapp_card/vender/aliyun-php-sdk-core/Request/V20170525/QuerySendDetailsRequest.php';
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region
        $region = "cn-hangzhou";
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient = new DefaultAcsClient($profile);
        $request = new SendSmsRequest;
        $request->setPhoneNumbers($sms_phone);
        $request->setSignName($signName);
        $request->setTemplateCode($templateCode);
        $request->setTemplateParam($templateParam);
        $request->setOutId($outId);//照填就行了
        try { //发起访问请求
            $acsResponse = $acsClient->getAcsResponse($request);
            WeUtility::logging('_sendNewDySms', var_export($acsResponse, true));
        } catch (Exception $e) {
            exit($e->getMessage());
        }

    }

    //阿里大于老接口
    public function _sendAliDaYuSms($_phone, $sms_user, $sms_secret, $sms_free_sign_name, $sms_template_code, $sms_param) {
        require_once IA_ROOT . '/addons/amouse_wxapp_card/vender/dayu/TopSdk.php';
        $c = new \TopClient($sms_user, $sms_secret);
        $c->appkey = $sms_user;
        $c->secretKey = $sms_secret;
        $req = new AlibabaAliqinFcSmsNumSendRequest();
        $req->setExtend('123456');
        $req->setSmsType('normal');
        $req->setSmsFreeSignName($sms_free_sign_name);
        $req->setSmsParam($sms_param);
        $req->setRecNum($_phone);
        $req->setSmsTemplateCode($sms_template_code);
        $resp = $c->execute($req);
    }
}