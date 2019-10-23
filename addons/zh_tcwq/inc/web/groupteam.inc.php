<?php
global $_GPC, $_W;

$GLOBALS['frames'] = $this->getMainMenu();

$sql=" select a.*,b.name as nick_name,b.img,c.store_name from".tablename('zhtc_group')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id left join ".tablename('zhtc_store')." c on a.store_id=c.id where a.id=:id";
$group=pdo_fetch($sql,array(':id'=>$_GPC['id']));
$sql2=" select a.*,b.name as nick_name from".tablename('zhtc_grouporder')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id where a.group_id=:id";
$order=pdo_fetchall($sql2,array(':id'=>$_GPC['id']));

include $this->template('web/groupteam');