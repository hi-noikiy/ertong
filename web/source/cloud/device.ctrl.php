<?php
/**
 * [美仑授权系统 System] Copyright (c) 2018 WEBY.CC
 * 美仑授权系统 is NOT a free software, it under the license terms, visited http://www.weby.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
if ($do == 'online') {
	header(base64_decode('TG9jYXRpb246IC8vY2xvdWQubWVpbC52aXAvYXBwL2FwaS5waHA/cmVmZXJyZXI9').$_W['setting']['site']['key']);
	exit;
} elseif ($do == 'offline') {
	header(base64_decode('TG9jYXRpb246IC8vY2xvdWQubWVpbC52aXAvYXBwL2FwaS5waHA/cmVmZXJyZXI9').$_W['setting']['site']['key'].'&standalone=1');
	exit;
} else {
}
template('cloud/device');
