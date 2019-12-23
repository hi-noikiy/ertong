<?php

$params = require(__DIR__ . '/params.php');
// $db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','queue'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => require __DIR__ . '/cache.php',
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\Users',
            'enableAutoLogin' => TRUE,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,//错误日志 默认为 console/runtime/logs/app.log
            'redis' => 'redis', // 连接组件或它的配置
            'channel' => 'queue', // Queue channel key
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            // ...
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'routeParam' => 'r',
            'rules' => [],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'name' => 'DBSESSIONID',
        ],
        'serializer' => [
            'class' => 'app\hejiang\Serializer',
        ],
        'sentry' => [
            'class' => 'app\hejiang\Sentry',
            'options' => [
                'dsn' => 'https://72fae1b1870b45f6ac603f1a5b34f556:74a490e3ba1a464d802855ac47cac826@sentry.io/1212625',
                'timeout' => 5,
                'app_path' => $basePath,
                'prefixes' => [$basePath],
                'excluded_app_paths' => [$basePath . '/vendor'],
                'release' => hj_core_version(),
                'excluded_exceptions' => [
                    'yii\web\HttpException',
                    'yii\db\Exception' => '/Connection refused/i',
                    'yii\db\Exception' => '/Connection timed out/i',
                    'yii\db\Exception' => '/Access denied for user/i',
                ],
            ],
        ],
        'storage' => [
            'class' => 'Hejiang\Storage\Components\StorageComponent',
            'basePath' => env('STORAGE_BASEPATH', 'web/uploads'),
        ],
        'storageTemp' => [
            'class' => 'Hejiang\Storage\Components\StorageComponent',
            'basePath' => env('STORAGE_TEMPPATH', 'runtime/temp'),
            'driver' => [
                'class' => 'Hejiang\Storage\Drivers\Local',
            ],
        ],
        'eventDispatcher' => [
            'class' => 'Hejiang\Event\EventDispatcher',
        ],
        'task' => [
            'class' => 'app\hejiang\task\Task',
        ],
        'db' => require __DIR__ . '/db.php',
    ],
    'params' => require __DIR__ . '/params.php',
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', env('DEBUG_ALLOW_IP', '127.0.0.1')],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
} else {
    // $config['bootstrap'][] = 'sentry';
}

return $config;
