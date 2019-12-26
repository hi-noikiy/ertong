<?php


namespace app\modules\api\controllers;


use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\Invoice;
use app\modules\api\models\InvoiceForm;
use app\modules\api\models\JWTForm;

header('content-type:application/json;charset=utf-8;');
class InvoiceController extends Controller
{
    private static $appid = 'commontesterCA';
    private static $baseUrl = 'https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/';

    private static $keyfile='https://shop.ertongkeji.com/addons/zjhj_mall/core/web/pro22/pro22.pfx';

    private static $blueApi = 'insertWithArray';
    
    //开具电子发票提交form表单页面接口
    public function actionFormSubmit(){
        $modul=\Yii::$app->request->post();
        // return new BaseApiResponse($modul);
        $GMF_MC=$modul['corporate_name'];//公司名称
        $total_sum=$modul['total_sum'];//总价格
        $email=$modul['email'];//邮箱地址
        $GMF_NSRSBH=$modul['number'];//纳税人识别号
        $fpqqlsh=$modul['order_no'];//订单流水号
        $XMMC=$modul['project_name'];//项目名称
        $SPBM=$modul['project_coding'];//项目编码
        
        // $form=new InvoiceForm();
        $invoice = new Invoice();
        $invoice->corporate_name=$GMF_MC;
        $invoice->total_sum=$total_sum;
        $invoice->email=$email;
        $invoice->number=$GMF_NSRSBH;
        $invoice->order_no=$fpqqlsh;
        $invoice->project_name=$XMMC;
        $invoice->project_coding=$SPBM;
        $invoice->type=1;
        $invoice->fpDm='';
        $invoice->fpHm='';
        $invoice->store_id=$this->store_id;
        $invoice->user_id=\Yii::$app->user->id;
        $invoice->status=0;
        $invoice->addtime=time();
        // return $invoice->save();
        if(!$invoice->save()){
            $error=$invoice->getErrors();
            return new BaseApiResponse($error);
            return new BaseApiResponse([
                'code'=>1,
                'msg'=> '提交失败',
            ]);
        }else{
            $id=$invoice->id;
        }

        $XSF_NSRSBH="201609140000001";//销售方纳税人识别号 (税号)
        // $ORGCODE="20160914001";
        // $GMF_MC="天津国联鸿泰科技有限公司";//购买方名称  (公司名称)
        // $GMF_DZDH="天津市河北区王串场街王串场四号路4号增19号 86-022-84847456";//购买方地址、电话 (选填)
        // $GMF_YHZH="中国建设银行股份有限公司天津河北支行 12050166080000000517";//购买方地址、电话 (购买方银行账号)
        // $ZDYBZ="这是放射所报名费xx单号的开票";//自定义备注  (选填)
        // $total_sum=100;
        $SL=0.03;//税率
        $money=$total_sum*0.03;
        $JSHJ=$money+$total_sum;//价税合计
        // $email='306555276@qq.com';
        // $GMF_NSRSBH='123456789';
        // $fpqqlsh="2018052615511000006";//流水号

        
        $blueInvoice_arr=array(
            "GMF_NSRSBH" =>$GMF_NSRSBH,
            "XSF_NSRSBH" => $XSF_NSRSBH,
            "GMF_MC" => $GMF_MC,
            "JSHJ" => $JSHJ,
            "email"=>$email,
            "SL"=>$SL,
            "fpqqlsh"=>$fpqqlsh,
            "XMMC"=>$XMMC,
            "SPBM"=>$SPBM,
        );
        
        $result=$this->blueInvoice($blueInvoice_arr);
        $result_arr=json_decode($result,true);
        
        if($result_arr['code']==0000){
            $invoice = Invoice::findOne(['id' => $id, 'store_id' => $this->store_id]);
            if($invoice){
                $invoice->status=1;
                $invoice->save();
                
            }
        }
        return new BaseApiResponse($result_arr);
        // return $result;
    }
    //开蓝票接口
    public function blueInvoice($blueInvoice_arr=array()) {
        $fpqqlsh = $blueInvoice_arr['fpqqlsh'];
        $requestdatas = array(
            array(
                "FPQQLSH" => $fpqqlsh,
                "XSF_NSRSBH" => $blueInvoice_arr['XSF_NSRSBH'],
                "GMF_MC" => $blueInvoice_arr['GMF_MC'],
                "GMF_NSRSBH"=>$blueInvoice_arr['GMF_NSRSBH'],
                "JSHJ" => $blueInvoice_arr['JSHJ'],
                "items" => array(
                    array(
                        "XMMC" => $blueInvoice_arr['XMMC'],//项目名称
                        "SPBM" => $blueInvoice_arr['SPBM'],//项目编码
                        "XMJSHJ" => $blueInvoice_arr['JSHJ'],//项目价税合计
                        "SL" => $blueInvoice_arr['SL'],//税率

                    )
                )
            )
        );
        $url = array(
            array(
                "fpqqlsh"=>$fpqqlsh,
                "url" => "http://bjxr246.cname.zaojiaojia.net/callback.php?callbackUrl"
            )
        );
        $email=array(
             array(
              "fpqqlsh"=>$fpqqlsh,
              "address"=>$blueInvoice_arr['email']
             ) 
        );
        $params = array(
            'requestdatas'=>json_encode($requestdatas),
            'email'=>json_encode($email),          
            'url'=>json_encode($url),
            "autoAudit" => 'false'
        );
        $url="https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/insertWithArray";
        $api=$url . "?appid=" . self::$appid;
        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        $result=self::post($api, $params, $options);
        return $result;
    }
    //查询开票状态
    public function actionStateQuery(){
        $modul=\Yii::$app->request->post();
        $fpqqlsh=$modul['fpqqlsh'];//订单流水号
        // $fpqqlsh="2018052615511000005";
        $requestdatas =array('fpqqlsh'=>$fpqqlsh);
        $params = array(
            'requestdatas'=>json_encode($requestdatas),
        );
        $url="https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/queryInvoiceStatus";
        $api=$url . "?appid=" . self::$appid;
        // $api = self::$baseUrl . $api . "?appid=" . self::$appid;

        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        $result=self::post($api, $requestdatas, $options);
        return new BaseApiResponse($result);
    }
    //发票红冲请求接口
    public function actionSubmitRed(){
        /*$fpqqlsh="2018052615511100005";
        $fpDm="211008635813";
        $fpHm="94530330";
        $email="306555276@qq.com";*/
        $modul=\Yii::$app->request->post();
        $fpqqlsh=$modul['order_no'];//订单流水号
        $fpDm=$modul['fpDm'];//蓝字发票代码
        $fpHm=$modul['fpHm'];//蓝字发票号码
        $email=$modul['email'];//邮箱

        $invoice = new Invoice();
        $invoice->email=$email;
        $invoice->order_no=$fpqqlsh;
        $invoice->type=2;
        $invoice->fpDm=$fpDm;
        $invoice->fpHm=$fpHm;
        $invoice->store_id=$this->store_id;
        $invoice->user_id=\Yii::$app->user->id;
        $invoice->status=0;
        $invoice->addtime=time();
        // return $invoice->save();
        if(!$invoice->save()){
            return [
                'code'=>1,
                'msg'=> '提交失败',
            ];
        }else{
            $id=$invoice->id;
        }

        $requestdatas = array(
            array(
                "FPQQLSH" => $fpqqlsh,//发票请求流水号  注意不是蓝字的发票请求流水号，是本次发票红冲的请求流水号
                "fpDm" => $fpDm,//蓝字发票代码  被红冲的发票代码
                "fpHm" => $fpHm,//蓝字发票号码  被红冲的发票号码
            )
        );
        $url = array(
            array(
                "fpqqlsh"=>$fpqqlsh,
                "url" => "http://bjxr246.cname.zaojiaojia.net/callback.php?callbackUrl"
            )
        );
        $email=array(
             array(
              "fpqqlsh"=>$fpqqlsh,
              "address"=>$email
             ) 
        );
        $params = array(
            'requestdatas'=>json_encode($requestdatas),
            'email'=>json_encode($email),          
            'url'=>json_encode($url),
            "autoAudit" => 'false'
        );
        $url="https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/red";
        $api=$url . "?appid=" . self::$appid;
        // $api = self::$baseUrl . $api . "?appid=" . self::$appid;

        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        $result=self::post($api, $params, $options);
        $result_arr=json_decode($result,true);
        
        if($result_arr['code']==0000){
            $invoice = Invoice::findOne(['id' => $id, 'store_id' => $this->store_id]);
            if($invoice){
                $invoice->status=1;
                $invoice->save();
                
            }
        }
        return $result;
    }
    //发票申请审核通过接口
    public function actionSubmitIssue(){
        // $fpqqlsh="2018052615511000005";
        // $total_sum=100;
        // $SL=0.03;//税率
        // $money=$total_sum*0.03;
        // $JSHJ=$money+$total_sum;//价税合计
        $XSF_NSRSBH="201609140000001";

        $modul=\Yii::$app->request->post();
        $fpqqlsh=$modul['order_no'];//订单流水号
        $total_sum=$modul['total_sum'];//合计金额
        $requestdatas = array(
            array(
                "FPQQLSH" => $fpqqlsh,//发票请求流水号
                "XSF_NSRSBH" => $XSF_NSRSBH,//销售方纳税人识别号
                "JSHJ" => $JSHJ,//价税合计
            )
        );
        
        $params = array(
            'requestdatas'=>json_encode($requestdatas)
        );
        $url="https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/issue";
        $api=$url . "?appid=" . self::$appid;
        // $api = self::$baseUrl . $api . "?appid=" . self::$appid;

        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        $result=self::post($api, $params, $options);
        $result_arr=json_decode($result,true);
        
        if($result_arr['code']==0000){
            $invoice = Invoice::findOne(['order_no' => $fpqqlsh, 'status'=>0, 'store_id' => $this->store_id]);
            if($invoice){
                $invoice->status=2;
                $invoice->save();
                // return $invoice->getErrors();
            }
        }
        return $result;
    }
    //电子发票部分红冲
    public function actionSubmitPartRed(){
        /*$fpqqlsh="2018052615511200005";
        $total_sum=-100.00;
        $SL=0.03;//税率
        $money=$total_sum*0.03;
        $JSHJ=$money+$total_sum;//价税合计

        $fpDm="000007656164";
        $fpHm="66624044";
        $email="306555276@qq.com";*/

        $modul=\Yii::$app->request->post();
        $fpqqlsh=$modul['order_no'];//订单流水号
        $total_sum=$modul['total_sum'];//合计金额
        $fpDm=$modul['fpDm'];//蓝字发票代码
        $fpHm=$modul['fpHm'];//蓝字发票号码
        $email=$modul['email'];//邮箱
        $XMMC=$modul['project_name'];//项目名称
        $SPBM=$modul['project_coding'];//项目编码

        $invoice = new Invoice();
        $invoice->total_sum=$total_sum;
        $invoice->email=$email;
        $invoice->order_no=$fpqqlsh;
        $invoice->project_name=$XMMC;
        $invoice->project_coding=$SPBM;
        $invoice->type=2;
        $invoice->fpDm=$fpDm;
        $invoice->fpHm=$fpHm;
        $invoice->store_id=$this->store_id;
        $invoice->user_id=\Yii::$app->user->id;
        $invoice->status=0;
        $invoice->addtime=time();
        // return $invoice->save();
        if(!$invoice->save()){
            return [
                'code'=>1,
                'msg'=> '提交失败',
            ];
        }else{
            $id=$invoice->id;
        }
        // return $id;
        $requestdatas = array(
            array(
                "FPQQLSH" => $fpqqlsh,//发票请求流水号  注意不是蓝字的发票请求流水号，是本次发票红冲的请求流水号
                "fpDm" => $fpDm,//蓝字发票代码  被红冲的发票代码
                "fpHm" => $fpHm,//蓝字发票号码  被红冲的发票号码
                "JSHJ" => $JSHJ,//价税合计
                "items" => array(
                    array(
                        "XMMC" => $XMMC,//项目名称
                        "SPBM" => $SPBM,//项目编码
                        "XMJSHJ" => $JSHJ,//项目价税合计
                        "SL" => $SL,//税率

                    )
                )
            )
        );
        
        $url = array(
            array(
                "fpqqlsh"=>$fpqqlsh,
                "url" => "http://bjxr246.cname.zaojiaojia.net/callback.php?callbackUrl"
            )
        );
        $email=array(
             array(
              "fpqqlsh"=>$fpqqlsh,
              "address"=>$email
             ) 
        );
        $params = array(
            'requestdatas'=>json_encode($requestdatas),
            'email'=>json_encode($email),          
            'url'=>json_encode($url),
            "autoAudit" => 'true'
        );
        $url="https://yesfp.yonyoucloud.com/invoiceclient-web/api/invoiceApply/part-red";
        $api=$url . "?appid=" . self::$appid;
        // $api = self::$baseUrl . $api . "?appid=" . self::$appid;

        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        $result=self::post($api, $params, $options);
        $result_arr=json_decode($result,true);
        
        if($result_arr['code']==0000){
            $invoice = Invoice::findOne(['id' => $id, 'store_id' => $this->store_id]);
            if($invoice){
                $invoice->status=1;
                $invoice->save();
                
            }
        }
        return $result;
    }
    //获取发票请求流水号(唯一)
    private function buildFpqqlsh(){
        return "2018052615511000005";
    }
    protected function exec($api, array $params) {
        $api=$api . "?appid=" . self::$appid;
        // $api = self::$baseUrl . $api . "?appid=" . self::$appid;

        $options = array(
            'header'=>array(
                'sign'=>$this->sign($params),
            )
        );
        return self::post($api, $params, $options);
    }
    //jwt签名
    private function sign(array $params){
        // require \Yii::$app->basePath .'/vendor/autoload.php';
        // include(\Yii::$app->basePath .'/JWT.php');
        $JWT=new JWTForm();
        $ts = time();
        $signParams = array(
            'sub'=>'tester',
            'iss'=>'einvoice',
            'aud'=>'einvoice',
            'jti'=>$ts,
            'iat'=>$ts,
            'exp'=>$ts+300,
            'nbf'=>$ts-300
        );
        // 需要将表单参数requestdatas的数据进行md5加密，然后放到签名数据的requestdatas中。
        // 此签名数据必须存在，否则在验证签名时会不通过。
        $requestdatas=$params['requestdatas'];
        if(!empty($requestdatas)) {
            $signParams['requestdatas'] = md5($requestdatas);
        }
        //读取CA证书与PEM格式证书需要根据实际证书使用情况而定,目前这两种都支持 
        $privateKey = $this->loadPrivateKeyOfCA(self::$keyfile);
        // $privateKey = $this->loadPrivateKeyOfPem(self::$keyfile);     
        $sign = $JWT->encode($signParams, $privateKey, 'RS256');
        return $sign;
    }

    //读取PEM编码格式
    private function loadPrivateKeyOfPem($file) {
        if(!file_exists($file)) {
            throw new \Exception("Error: Key file $file is not exists.");
        }
        if(!$key = file_get_contents($file)) {
            throw new \Exception("Error: Key file $file is empty.");
        }
        return $key;
    }
    //读取证书私钥
    private function loadPrivateKeyOfCA($file) {
        // if(!file_exists($file)) {
        //     throw new \Exception("Error: Cert file $file is not exists.");
        // }
        if (!$cert_store = file_get_contents($file)) {
            throw new \Exception("Error: Unable to read the cert file $file .");
        }
        if (openssl_pkcs12_read($cert_store, $cert_info, "password")) {
            return $cert_info['pkey'];
        } else {
            throw new \Exception("Error: Unable to read the cert store from $file .");
        }
    }

    private static function post($url, $params, array $options=null) {
        $ch = curl_init();
        self::setOption($ch, $options);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, count($params));
        $params = http_build_query($params);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $content = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($content, 0, $headerSize);
            $body = substr($content, $headerSize);
        }
        $errorCode = curl_errno($ch);
        curl_close($ch);
        return $body;
        return array($errorCode, $content);
    }

    private static function setOption($ch, array $options=null) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if($options === null) {
            $options = array();
        }
        if(isset($options["cookie"]) && is_array($options["cookie"])) {
            $cookieArr = array();
            foreach($options["cookie"] as $key=>$value) {
                $cookieArr[] = "$key=$value";
            }
            $cookie = implode("; ", $cookieArr);
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        $timeout = 30;
        if(isset($options["timeout"])) {
            $timeout = $options["timeout"];
        }
        if(isset($options["ua"])) {
            curl_setopt($ch, CURLOPT_USERAGENT, $options["ua"]);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if(isset($options['header'])) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            $header = array();
            foreach($options['header'] as $k=>$v) {
                $header[] = $k.": ".$v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
    }
    //发票列表接口
    public function actionInvoiceList(){
        $result=Invoice::find()->where(['user_id'=>\Yii::$app->user->id, 'store_id' => $this->store_id])->asArray()->all();
        if(empty($result)){
            return new BaseApiResponse((object)[
                'code' => 1,
                'msg' => '未找到发票数据',
                
            ]);
        }else{
            
            foreach ($result as $key => $val) {
                $result[$key]['add_time']=date('Y-m-d H:i:s',$val['addtime']);
            }
            
            return new BaseApiResponse([
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $result,
                ],               
            ]);
        }
    }
    //发票详情接口
    public function actionInvoiceDetail(){
        $modul=\Yii::$app->request->post();
        $result=Invoice::find()->where(['id'=>$modul['id'], 'store_id' => $this->store_id])->asArray()->one();
        if(empty($result)){
            return new BaseApiResponse((object)[
                'code' => 1,
                'msg' => '未找到发票数据',
                
            ]);
        }else{
            
            $result['add_time']=date('Y-m-d H:i:s',$result['addtime']);
            
            return new BaseApiResponse([
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $result,
                ],               
            ]);
        }
    }
}