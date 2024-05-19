<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use common\models\Employees;

class ProfileController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionSearch()
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $employee = Employees::findOne(['id' => $user->employee_id]);
        if (!$employee) {
            throw new NotFoundHttpException('Employee not found.');
        }

        return $employee;
    }
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $employee = Employees::findOne(['id' => $user->employee_id]);
        if (!$employee) {
            throw new NotFoundHttpException('Employee not found.');
        }

        // Ambil data yang dikirimkan dalam permintaan
        $requestData = Yii::$app->getRequest()->getBodyParams();

        // Lakukan validasi data
        $employee->load($requestData, '');

        // Simpan perubahan jika data valid
        if ($employee->save()) {
            return $employee;
        } else {
            // Jika validasi gagal, kembalikan pesan kesalahan validasi
            return $employee->getErrors();
        }
    }

}
