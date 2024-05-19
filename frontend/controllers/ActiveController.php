<?php


namespace frontend\controllers;


use frontend\resource\Comment;
use frontend\resource\Post;
use frontend\resource\AbsensiLog;
use common\models\EmployeesPosition;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\web\ForbiddenHttpException;
use yii\filters\Cors;
use yii\web\ErrorHandler;
/**
 * Class ActiveController
 *

 */
class ActiveController extends \yii\rest\ActiveController
{


    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     unset($behaviors['authenticator']);
    //     $behaviors['corsFilter'] = [
    //         'class' => Cors::class,
    //         'cors' => [
    //             'Origin' => ['*'],
    //             'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
    //             'Access-Control-Request-Headers' => ['*'],
    //         ],
    //     ];
    //     $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
    //     $behaviors['authenticator']['authMethods'] = [
    //         HttpBearerAuth::class
    //     ];


    //     return $behaviors;
    // }
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Hapus authenticator default jika tidak diperlukan
        unset($behaviors['authenticator']);

        // Tambahkan filter CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // Atur authenticator untuk action tertentu
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['create', 'view','update', 'delete'],
        ];

        // Tambahkan penanganan error
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     *
     *
     * @param string $action
     * @param Post|Comment|AbsensiLog $model
     * @param array $params
     * @throws ForbiddenHttpException
     
     */ 
    // public function checkAccess($action, $model = null, $params = [])
    // {
    //     // $pos = new EmployeesPosition();
    //     if (in_array($action, ['update', 'delete']) ) {
    //         throw new ForbiddenHttpException("You do not have permission to change this record");
    //     }
    // }
}
