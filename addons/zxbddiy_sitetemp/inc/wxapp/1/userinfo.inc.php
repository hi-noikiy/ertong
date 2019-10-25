<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$account_api = WeAccount::create();
	$encrypt_data = $_GPC['encryptedData'];
	$iv = $_GPC['iv'];
	if (empty($_SESSION['session_key']) || empty($encrypt_data) || empty($iv)) {
		$account_api->result(1, '请先登录');
	}
	$sign = sha1(htmlspecialchars_decode($_GPC['rawData']).$_SESSION['session_key']);
	$userinfo = $this->decrypt($encrypt_data, $iv,$_W['current_module']['config']['appkey'],$_SESSION['session_key']);
	$userinfo=json_decode($userinfo, true);
	$fans = mc_fansinfo($userinfo['openid']);
	$fans_update = array(
		'nickname' => $userinfo['nickname'],
		'unionid' => $userinfo['unionId'],
		'tag' => base64_encode(iserializer(array(
			'subscribe' => 1,
			'openid' => $userinfo['openid'],
			'nickname' => $userinfo['nickname'],
			'sex' => $userinfo['sex'],
			'language' => $userinfo['language'],
			'city' => $userinfo['city'],
			'province' => $userinfo['province'],
			'country' => $userinfo['country'],
			'headimgurl' => $userinfo['headimgurl'],
		))),
	);
		if (!empty($userinfo['unionId'])) {
		$union_fans = pdo_get('mc_mapping_fans', array('unionid' => $userinfo['unionId'], 'openid !=' => $userinfo['openid']));
		if (!empty($union_fans['uid'])) {
			if (!empty($fans['uid'])) {
				
				pdo_delete('mc_members', array('uid' => $fans['uid']));
			}
			$fans_update['uid'] = $union_fans['uid'];
			$_SESSION['uid'] = $union_fans['uid'];
		}
	}
	pdo_update('mc_mapping_fans', $fans_update, array('fanid' => $fans['fanid']));
	pdo_update('mc_members', array('nickname' => $userinfo['nickname'], 'avatar' => $userinfo['headimgurl'], 'gender' => $userinfo['sex']), array('uid' => $fans['uid']));
	$member = mc_fetch($fans['uid']);
	unset($member['password']);
	unset($member['salt']);
	$account_api->result(0, '', $member);
