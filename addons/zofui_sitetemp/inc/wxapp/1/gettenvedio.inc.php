<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	
	if( empty( $_GPC['url'] ) ) $this->result(3,'视频链接不正确');

    preg_match('/vid\=([^\&]*)($|\&)/', $_GPC['url'], $matches);

    $vid = $matches[1];

    if( empty( $vid ) ) {
    	preg_match('/\/([0-9a-zA-Z]+).html/', $_GPC['url'], $matches);
    }
    $vid = $matches[1];
    if( empty( $vid ) ) $this->result(3,'视频链接不正确');

	$api = 'http://vv.video.qq.com/getinfo?vids='.$vid.'&platform=101001&charge=0&otype=json';

	$res = Util::httpGet( $api );

	if( empty( $res ) ) $this->result(3,'视频链接不正确');

	$res = ltrim($res,'QZOutputJson=');
	$res = rtrim($res,';');
	$dataarr = json_decode( $res,true );

	if( empty( $dataarr ) || empty( $dataarr['vl'] ) || empty( $dataarr['vl']['vi'] ) || empty( $dataarr['vl']['vi'][0] ) ){
		$this->result(3,'视频链接不正确');
	}

	$resarr = $dataarr['vl']['vi'][0];

	if( empty( $resarr ) || empty( $resarr['ul']['ui'][0]['url'] ) || empty( $resarr['fn'] ) || empty( $resarr['fvkey'] ) ){
		$this->result(3,'视频链接不正确');
	}

	$url = $resarr['ul']['ui'][0]['url'] . $resarr['fn'] . '?vkey='.$resarr['fvkey'];

	$this->result(0,$url);
	
	$this->result(1, '提交失败');