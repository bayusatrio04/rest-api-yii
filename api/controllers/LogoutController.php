<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
class LogoutController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser) {
            return ['status' => 'error', 'message' => 'No user is currently logged in'];
        }

        Yii::$app->user->logout();
        return ['status' => 'success', 'message' => 'Logout successful'];
    }
}
