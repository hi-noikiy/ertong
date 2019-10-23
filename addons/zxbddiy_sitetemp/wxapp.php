<?php
/**
 * 小程序测试模块小程序接口定义
 *
 * @author zofui
 * @url 
 */
global $_W;
defined('IN_IA') or exit('Access Denied');
define('ST_ROOT',IA_ROOT.'/addons/zxbddiy_sitetemp/');
define('ST_URL',$_W['siteroot'].'addons/zxbddiy_sitetemp/');
define('MODULE','zxbddiy_sitetemp');
require_once(ST_ROOT.'class/autoload.php');

class zxbddiy_sitetempModuleWxapp extends WeModuleWxapp {
	public function payResult($result) {
		if ($result['result'] == 'success') {
			//此处会处理一些支付成功的业务代码
			pay::payResult( $result ,$this->module['config']);
		}

		return true;
	}
	public function getOauthInfo($code = '') {
		global $_W, $_GPC;
		$app=pdo_fetch("select * from ".tablename("account_wxapp")."WHERE `uniacid`=:uniacid LIMIT 1",array(':uniacid' => $_W['uniacid']));
		if (!empty($_GPC['code'])) {
			$code = $_GPC['code'];
		}
	
		$settings = Util::getModuleConfig();
		$url = "https://openapi.baidu.com/nalogin/getSessionKeyByCode?client_id=".$settings['appkey']."&sk=".$_W['account']['oauth']['secret']."&code=".$code;	
		$response = $this->requestApi($url,$data);
		return $response;
	}
	
	protected function requestApi($url, $post = '') {
		$response = ihttp_request($url, $post);
		$result = @json_decode($response['content'], true);
		if(!$result['openid']) {
			return error($result['errcode'], "访问公众平台接口失败, 错误详情: {$this->errorCode($result['error_description'])}");
		}
		return $result;
	}
	//支付
	protected function pay($order) {
		global $_W, $_GPC;
		load()->model('payment');
		load()->model('account');
		$moduels = uni_modules();
		if(empty($order) || !array_key_exists($this->module['name'], $moduels)) {
			return error(1, '模块不存在');
		}
		$moduleid = empty($this->module['mid']) ? '000000' : sprintf("%06d", $this->module['mid']);
		$uniontid = date('YmdHis').$moduleid.random(8,1);
		$wxapp_uniacid = intval($_W['account']['uniacid']);

		$paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $this->module['name'], 'tid' => $order['tid']));
		if (empty($paylog)) {
			$paylog = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['openid'],
				'module' => $this->module['name'],
				'tid' => $order['tid'],
				'uniontid' => $uniontid,
				'fee' => floatval($order['fee']),
				'card_fee' => floatval($order['fee']),
				'status' => '0',
				'is_usecard' => '0',
				'tag' => iserializer(array('acid' => $_W['acid'], 'uid' => $_W['member']['uid']))
			);
			pdo_insert('core_paylog', $paylog);
			$paylog['plid'] = pdo_insertid();
		}
		if(!empty($paylog) && $paylog['status'] != '0') {
			return error(1, '这个订单已经支付成功, 不需要重复支付.');
		}
		if (!empty($paylog) && empty($paylog['uniontid'])) {
			pdo_update('core_paylog', array(
				'uniontid' => $uniontid,
			), array('plid' => $paylog['plid']));
			$paylog['uniontid'] = $uniontid;
		}

		$_W['openid'] = $paylog['openid'];

		$params = array(
			'tid' => $paylog['tid'],
			'fee' => $paylog['card_fee'],
			'user' => $paylog['openid'],
			'uniontid' => $paylog['uniontid'],
			'title' => $order['title'],
			//支付记录id
			'payid'=>$paylog['plid']
		);
//		$setting = uni_setting($wxapp_uniacid, array('payment'));
		$settings = Util::getModuleConfig();
		$wechat_payment = array(
			"dealId" => $settings['dealid'],
			"appKey"=> $settings['bd_appkey'],
		);
		return $this->wechat_build($params, $wechat_payment);
	}
protected function wechat_build($params, $wechat) {
	global $_W;	
	//应付多少钱
	$totalAmount=intval($params['fee']*100);
	$dealTitle=$params['title'];
	$tpOrderId=$params['tid'];
	/**
	 * 第一部分：从申请的私钥文件路径中读取出私钥的内容
	 * @notice1: 私钥文件可以从任意存储介质中读取 
	 */
	$settings = Util::getModuleConfig();
//	$rsaPrivateKey = $settings['rsa_private_key'];
	$rsaPrivateKey=file_get_contents($_W['siteroot'].'addons/zxbddiy_sitetemp/cert/'.$_W['uniacid'].'/rsa_private_key.pem');
	/**
	 * 第二部分：使用参数计算签名
	 */
//	 var_dump($params);die;
	$requestApiParamsArr = array('appKey'=>$wechat['appKey'],'dealId'=>$wechat['dealId'],'tpOrderId'=>$tpOrderId,'totalAmount'=>$totalAmount);
	//签名
	$rsaSign = NuomiRsaSign::genSignWithRsa($requestApiParamsArr ,$rsaPrivateKey);
	//需要返回的参数
//	$bizInfo='{
//				"tpData":{
//		        "appKey":"'.$wechat['appKey'].'",
//		        "dealId":"'.$wechat['dealId'].'",
//		        "tpOrderId":"'.$tpOrderId.'",
//		        "rsaSign":"'.$rsaSign.'",
//		        "totalAmount":"'.$totalAmount.'",
//		        "returnData":{
//		            "bizKey1":"第三方的字段1取值",
//		            "bizKey2":"第三方的字段2取值"
//		        },
//		        "displayData":{
//		            "cashierTopBlock":[
//		                [
//		                    {
//		                        "leftCol":"订单名称",
//		                        "rightCol":"'.$dealTitle.'"
//		                    },
//		                    {
//		                        "leftCol":"数量",
//		                        "rightCol":"1"
//		                    },
//		                    {
//		                        "leftCol":"小计",
//		                        "rightCol":"'.($totalAmount/100).'"
//		                    }
//		                ],
//		                [
//		                    {
//		                        "leftCol":"服务地址",
//		                        "rightCol":"北京市海淀区中关村南大街5号百度大厦"
//		                    },
//		                    {
//		                        "leftCol":"服务时间",
//		                        "rightCol":"'.date('Y/m/d H:i-H:i').'"
//		                    },
//		                    {
//		                        "leftCol":"服务人员",
//		                        "rightCol":"智享科技"
//		                    }
//		                ]
//		            ]
//		        }
//		    }
//		}';
	$returndata=array(
		"dealId" => $wechat['dealId'],
		"appKey"=> $wechat['appKey'],
		"totalAmount"=>$totalAmount,
        "tpOrderId"=>$tpOrderId,
        "dealTitle"=>$dealTitle,
        "rsaSign"=>$rsaSign,
		"bizInfo"=>'{}',
		"paylogid"=>$params['payid']
	);
	return $returndata;
	}
	//获取用户信息
protected function decrypt($ciphertext, $iv, $app_key, $session_key) {
    $session_key = base64_decode($session_key);
    $iv = base64_decode($iv);
    $ciphertext = base64_decode($ciphertext);

    $plaintext = false;
    if (function_exists("openssl_decrypt")) {
        $plaintext = openssl_decrypt($ciphertext, "AES-192-CBC", $session_key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
    } else {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, null, MCRYPT_MODE_CBC, null);
        mcrypt_generic_init($td, $session_key, $iv);
        $plaintext = mdecrypt_generic($td, $ciphertext);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
    }
    if ($plaintext == false) {
        return false;
    }

    // trim pkcs#7 padding
    $pad = ord(substr($plaintext, -1));
    $pad = ($pad < 1 || $pad > 32) ? 0 : $pad;
    $plaintext = substr($plaintext, 0, strlen($plaintext) - $pad);

    // trim header
    $plaintext = substr($plaintext, 16);
    // get content length
    $unpack = unpack("Nlen/", substr($plaintext, 0, 4));
    // get content
    $content = substr($plaintext, 4, $unpack['len']);
    // get app_key
    $app_key_decode = substr($plaintext, $unpack['len'] + 4);

    return $app_key == $app_key_decode ? $content : false;
}

}