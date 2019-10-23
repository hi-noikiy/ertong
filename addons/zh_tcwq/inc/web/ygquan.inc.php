<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
include $this->template('web/ygquan');