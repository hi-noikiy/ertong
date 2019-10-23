<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$item=pdo_get('zhtc_order',array('id'=>$_GPC['id']));
include $this->template('web/sjorderinfo');