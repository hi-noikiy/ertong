<?php
error_reporting(E_ERROR | E_PARSE);

if (!isset($_GET['r'])) {
    $_GET['r'] = 'mch/permission/passport/index';
}
// require __DIR__ . '/index.php';


require __DIR__ . '/../vendor/autoload.php';

$app = new app\hejiang\Application();
$app->run();


