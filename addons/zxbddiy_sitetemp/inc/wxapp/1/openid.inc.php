<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$account_api = WeAccount::create();
	$code = $_GPC['code'];
	if (empty($_W['account']['oauth']) || empty($code)) {
		exit('通信错误，请在微信中重新发起请求');
	}
	$oauth = $this->getOauthInfo($code);
//	var_dump($oauth);die;
	if (!empty($oauth) && !is_error($oauth)) {
		$_SESSION['openid'] = $oauth['openid'];
		$_SESSION['session_key'] = $oauth['session_key'];
		$fans = mc_fansinfo($oauth['openid']);
		if (empty($fans)) {
			$record = array(
				'openid' => $oauth['openid'],
				'uid' => 0,
				'acid' => $_W['acid'],
				'uniacid' => $_W['uniacid'],
				'salt' => random(8),
				'updatetime' => TIMESTAMP,
				'nickname' => '',
				'follow' => '1',
				'followtime' => TIMESTAMP,
				'unfollowtime' => 0,
				'tag' => '',
			);
			$email = md5($oauth['openid']).'@we7.cc';
			$email_exists_member = pdo_getcolumn('mc_members', array('email' => $email), 'uid');
			if (!empty($email_exists_member)) {
				$uid = $email_exists_member;
			} else {
				$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
				$data = array(
					'uniacid' => $_W['uniacid'],
					'email' => $email,
					'salt' => random(8),
					'groupid' => $default_groupid,
					'createtime' => TIMESTAMP,
					'password' => md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']),
					'nickname' => '',
					'avatar' => '',
					'gender' => '',
					'nationality' => '',
					'resideprovince' => '',
					'residecity' => '',
				);
				pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
			}
			$record['uid'] = $uid;
			$_SESSION['uid'] = $uid;
			pdo_insert('mc_mapping_fans', $record);
		}
		$account_api->result(0, '', array('sessionid' => $_W['session_id']));
	} else {
		$account_api->result(1, $oauth['message']);
	}
