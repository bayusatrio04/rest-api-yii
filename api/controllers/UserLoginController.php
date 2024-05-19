<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

class UserLoginController extends Controller
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
        $user = Yii::$app->user->identity;
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }
        return $user;
    }
}
