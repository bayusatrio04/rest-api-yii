<?php

namespace api\modules\absence\controllers;

use api\controllers\ActiveController;
use frontend\resource\AbsensiLog;
use yii\web\Response;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
/**
 * Default controller for the `absence` module
 */
class SearchAbsensiLogController extends ActiveController
{
    public $modelClass = AbsensiLog::class;

    public function actionSearch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $searchModel = new AbsensiLog();
        $searchModel->load(Yii::$app->request->get(), '');

        $query = AbsensiLog::find();

        if ($searchModel->id) {
            $query->andWhere(['id' => $searchModel->id]);
        }
        if ($searchModel->tanggal_absensi) {
            $query->andWhere(['tanggal_absensi' => $searchModel->tanggal_absensi]);
        }
        if ($searchModel->waktu_absensi) {
            $query->andWhere(['waktu_absensi' => $searchModel->waktu_absensi]);
        }
        if ($searchModel->created_by) {
            $query->andWhere(['created_by' => $searchModel->created_by]);
        }

        $result = $query->all();

        return $result;
    }
    public function actionView($id = null, $created_by = null)
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    if ($id !== null) {
        $model = AbsensiLog::findOne($id);
    } elseif ($created_by !== null) {
        $model = AbsensiLog::find()->where(['created_by' => $created_by])->all();
    } else {
        throw new BadRequestHttpException('Missing required parameters: id or created_by');
    }

    if ($model !== null) {
        return $model;
    } else {
        throw new NotFoundHttpException('Data not found');
    }
}

}
