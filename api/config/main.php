<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
  
        'absence' => [
            'class' => 'api\modules\absence\Module',
        ],
        'typeabsence' => [
            'class' => 'api\modules\typeabsence\Module',
        ],
        'statusabsence' => [
            'class' => 'api\modules\statusabsence\Module',
        ],
        'salary' => [
            'class' => 'api\modules\salary\Module',
        ],
    ],
    'components' => [
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization');
            },
        ],
        'corsFilter' => [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' =>[
                'application/json'=> 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
            'as authenticator' => [
                'class' => 'yii\filters\auth\HttpBearerAuth',
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'auth' => 'site/login',
                'auth/logout' => 'logout/index',
                'auth/user' => 'user-login/index',
                'auth/profile' => 'profile/search',
                'auth/profile/update' => 'profile/update',
                'search' => 'search-absensi-log/search',
                'GET /absence/absensi-log/search/status' => 'absence/absensi-log/search-by-status',
                'GET /absence/absensi-log/search/type' => 'absence/absensi-log/search-by-type',
                'last-attendance' => 'last-attendance/index',
                'GET /absence/absensi-log/latest' => 'absence/absensi-log/latest',
                'GET /absence/absensi-log/search' => 'absence/absensi-log/search',
                
                'GET /absence/absensi-log/total/checkin/all/completed' => 'absence/absensi-log/total-checkin-all-completed-by-user-login',
                'GET /absence/absensi-log/total/checkout/all/completed' => 'absence/absensi-log/total-checkout-all-completed-by-user-login',
                'GET /absence/absensi-log/total/checkin/all/completed/filter-by-month' => 'absence/absensi-log/total-checkin-all-completed-by-user-login-month',
                'GET /absence/absensi-log/total/checkout/all/completed  /filter-by-month' => 'absence/absensi-log/total-checkout-all-completed-by-user-login-month',
                
                'GET /absence/total-absensi-per-karyawan/all' => 'absence/total-absensi-per-karyawan/total-per-karyawan-all',
                'GET /absence/total-absensi-per-karyawan/month' => 'absence/total-absensi-per-karyawan/total-per-karyawan-month',
                'GET /absence/total-absensi-per-karyawan/year' => 'absence/total-absensi-per-karyawan/total-per-karyawan-year',
                'GET /absence/total-absensi-per-karyawan/month-year' => 'absence/total-absensi-per-karyawan/total-per-karyawan-month-year',
                'POST /salary/calculate-month' => 'salary/index',


                
                

            ],
        ]
        
        
    ],
    'params' => $params,
];
