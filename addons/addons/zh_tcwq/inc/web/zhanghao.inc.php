<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$seller_id=$_COOKIE["storeid"];
$uid=$_COOKIE["uid"];
$GLOBALS['frames'] = $this->getMainMenu($seller_id, $action='start',$uid);

if ($operation == 'display') {
    $strwhere = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
   $list = pdo_fetchall("SELECT a.*,b.username AS username,b.status AS status FROM " . tablename('zhtc_account') . " a LEFT JOIN
" . tablename('users') . " b ON a.uid=b.uid where a.weid={$_W['uniacid']} and a.role=2   ORDER BY id DESC LIMIT
" . ($pindex - 1) * $psize . ',' . $psize, $data);
    if (!empty($list)) {
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('zhtc_account') . " WHERE weid = :weid and role=2", array(':weid' => $_W['uniacid']));
        $pager = pagination($total, $pindex, $psize);
    }
}else if ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT * FROM " . tablename('zhtc_account') . " WHERE id = '$id'");
    if (empty($item)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('zhanghao', array('op' => 'display')), 'error');
    }
     pdo_delete('users', array('uid' => $item['uid']));
    pdo_delete('zhtc_account', array('id' => $id, 'weid' => $_W['uniacid']));
   
    message('删除成功！', $this->createWebUrl('zhanghao', array('op' => 'display')), 'success');
}
include $this->template('web/zhanghao');