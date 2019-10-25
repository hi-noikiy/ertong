<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$where=" where a.weid = :weid AND a.role=1 $strwhere";
$data[':weid']=$_W['uniacid'];

$GLOBALS['frames'] = $this->getMainMenu();
 $users = user_single($_W['user']['uid']);
include $this->template('web/log');