<?php


namespace api\controllers;


use api\resource\Comment;
use api\resource\Post;
use api\resource\AbsensiLog;
use common\models\EmployeesPosition;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\web\ForbiddenHttpException;
use yii\filters\Cors;
use yii\web\ErrorHandler;
/**
 * Class ActiveController
 *

 */
class ActiveController extends \yii\rest\ActiveController
{
    public $serializer =[
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope'=> 'items'
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();



        // Tambahkan filter CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];


        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
       
            ],
            // 'only' => ['create', 'view', 'update', 'delete', 'search'],
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
