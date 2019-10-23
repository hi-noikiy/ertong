<?php
namespace wx;
class Refund {
    protected $appid;
    protected $mch_id;
    protected $key;
    protected $out_trade_no;
    protected $out_refund_no;
    protected $total_fee;
    protected $refund_fee;
    protected $notify_url;
    protected $certpath;
    protected $keypath;

    function __construct($appid, $mch_id, $key,$out_trade_no,$out_refund_no,$total_fee,$refund_fee,$certpath,$keypath,$notify_url='https://weixin.qq.com/notify/') {
        $this->appid = $appid;
        $this->mch_id = $mch_id;
        $this->key = $key;
        $this->out_trade_no = $out_trade_no;
        $this->out_refund_no = $out_refund_no;
        $this->total_fee = $total_fee;
        $this->refund_fee = $refund_fee;
        $this->notify_url=$notify_url;
        $this->certpath = $certpath;
        $this->keypath = $keypath;
    }

    public function run(){
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

        $nonce_str = Tool::createNoncestr(); //随机字符串
        $parameters = array(
            'appid' => $this->appid, //小程序ID
            'mch_id' => $this->mch_id, //商户号
            'nonce_str' => $nonce_str,
            'out_trade_no'=> $this->out_trade_no,
            'out_refund_no'=>$this->out_refund_no,
            'total_fee' => $this->total_fee,
            'refund_fee' => $this->refund_fee,
            'notify_url' =>$this->notify_url, //通知地址  确保外网能正常访问
        );
        //签名
        $parameters['sign'] = Tool::getSign($parameters,$this->key);
        $xmlData = Tool::arrayToXmls($parameters);
        $return = Tool::postXmlCurl($xmlData, $url, 60,$this->certpath,$this->keypath);
        $return = Tool::xmlToArray($return);
        return $return;
    }
}		
			
		
