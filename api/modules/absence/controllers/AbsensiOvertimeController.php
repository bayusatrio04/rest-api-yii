<?php
namespace api\modules\absence\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use Yii;
use yii\web\Response;

class AbsensiOvertimeController extends ActiveController
{
    public $modelClass = 'common\models\AbsensiOvertime';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Enable CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        // Enable HTTP Bearer Auth
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // Disable delete action
        unset($actions['delete']);

        return $actions;
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return true;
        }
        return false;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass;
        $params = Yii::$app->getRequest()->getBodyParams();

        $model->load($params, '');

        if ($model->save()) {
            return $model;
        } else {
            return [
                'status' => false,
                'data' => $model->errors,
            ];
        }
    }
}
