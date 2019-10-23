<?php

class Message 
{
	
	
	// 表单数据提醒
	public static function orderMessage($openid,$mid,$url,$fid,$title,$fee,$status) {
		
		$time = date('Y-m-d H::s',TIMESTAMP);

        $msg_json = <<<div
			{
			  "touser": "{$openid}",  
			  "template_id": "{$mid}", 
			  "page": "{$url}",    
			  "form_id": "{$fid}",
			  "data": {
			      	"keyword1": {
			          	"value": "{$title}", 
			          	"color": "#173177"
			      	}, 
			      	"keyword2": {
			          	"value": "{$fee}", 
			          	"color": "#173177"
			      	},
			      	"keyword3": {
			          	"value": "{$status}",
			          	"color": "#173177"
			      	}, 
			      	"keyword4": {
			          	"value": "{$time}",
			          	"color": "#173177"
			      	}			      	
			  	}
			}
div;

		return self::commonPostMessage($msg_json);
	}


	public static function apporderMessage($openid,$mid,$url,$fid,$title,$status,$odesc){
		
		$time = date('Y-m-d H::s',TIMESTAMP);

        $msg_json = <<<div
			{
			  "touser": "{$openid}",
			  "template_id": "{$mid}",
			  "page": "{$url}",
			  "form_id": "{$fid}",
			  "data": {
			      	"keyword1": {
			          	"value": "{$title}", 
			          	"color": "#173177"
			      	}, 
			      	"keyword2": {
			          	"value": "{$time}", 
			          	"color": "#173177"
			      	},
			      	"keyword3": {
			          	"value": "{$odesc}",
			          	"color": "#173177"
			      	}, 
			      	"keyword4": {
			          	"value": "{$status}",
			          	"color": "#173177"
			      	}			      	
			  	}
			}
div;

		return self::commonPostMessage($msg_json);
	}

	
	// 表单数据提醒
	public static function sendmessage($openid,$messageid) {
		
		$time = date('Y-m-d H::s',TIMESTAMP);

        $msg_json = <<<div
			{
			  "touser": "{$openid}",  
			  "template_id": "{$messageid}", 
			  "page": "zxbddiy_sitetemp/pages/form/form",    
			  "form_id": "FORMID",
			  "data": {
			      "keyword1": {
			          "value": "有人提交了一项表单数据,点击查看", 
			          "color": "#173177"
			      }, 
			      "keyword2": {
			          "value": "{$time}", 
			          "color": "#173177"
			      } 
			  }
			}
div;

		return self::commonPostMessage($msg_json);
	}

	
	//模板消息url
	static function getUrl1(){
		global $_W;
		
		load() -> model('account');
		$account = WeAccount::create( $_W['acid'] );
		$access_token = $account->getAccessToken();
		
		$url1 = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;		
		return $url1;
		
	}
	
	static function commonPostMessage($msg_json){
		$url1 = self::getUrl1();
		$res = Util::httpPost($url1, $msg_json,11);
		$res = json_decode((string)$res,true);
		
		if($res['errmsg'] == 'ok') {
			return array('status'=>true);
		}else{
			return array('status'=>false,'msg'=>$res['errmsg']);
		}
	}	
	



/*************以下是发消息******************/	





	
}
