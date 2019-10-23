<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$sql=" select a.*,b.name as nick_name from".tablename('zhtc_grouporder')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id where a.id=:id";
$data[':id']=$_GPC['id'];
$item=pdo_fetch($sql,$data);
//$item=pdo_get('zhtc_grouporder',array('id'=>$_GPC['id']));

include $this->template('web/sjgrouporderinfo');