<?php

$basePath = dirname(__DIR__);

$config = [
    'id' => 'basic',
    'language' => 'zh-CN',
    'timeZone' => env('TIME_ZONE', 'PRC'),
    'basePath' => $basePath,
    'bootstrap' => ['log','queue'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'class' => 'app\hejiang\Request',
            'cookieValidationKey' => env('COOKIE_KEY', '123'),
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
        'admin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true,
            'idParam' => '__admin_id',
            'identityCookie' => [
                'name' => '_admin_identity',
                'httpOnly' => true,
            ],
        ],
        'mchRoleAdmin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\MchRoleAdmin',
            'enableAutoLogin' => true,
            'idParam' => '__mchRoleAdmin_id',
            'identityCookie' => [
                'name' => '_mchRoleAdmin_identity',
                'httpOnly' => true,
            ],
        ],
        'errorHandler' => [
            'class' => 'app\hejiang\ErrorHandler',
            'errorView' => __DIR__ . '/../views/error/error.php',
            'exceptionView' => __DIR__ . '/../views/error/exception.php',
        ],
        'mailer' => [
            // 'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 'useFileTransport' => true,
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['*']
                ],
                [
                    'class' => 'yii\log\FileTarget', // 表示文件系统记录日志
                    'categories' => ['pay'],// 分类名称，你也可以['pay\*']标识pay\开头的所有
                    'levels' => ['error', 'warning'],// 错误名
                    'logVars' => ['*'], // 输出内容，*标识只输出文字，不写的话会输出session server get post等信息
                    'logFile' => '@runtime/logs/app.log' // 日志输出的文件路径@标识根目录
                ],
            ],
        ],
        'cache' => require __DIR__ . '/cache.php',
        'db' => require __DIR__ . '/db.php',
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
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
        'mch' => [
            'class' => 'app\modules\mch\Module',
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
    'params' => require __DIR__ . '/params.php',
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