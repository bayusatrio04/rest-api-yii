<?php

namespace api\modules\absence\controllers;

use frontend\resource\AbsensiLog;
use api\controllers\ActiveController;
use Yii;

class LastAttendanceController extends ActiveController
{
    public $modelClass = AbsensiLog::class;

    // Tidak membutuhkan autentikasi untuk akses

    // Action to get the last attendance of the logged-in user
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // Find the last attendance record of the user
        $lastAttendance = AbsensiLog::find()
            ->orderBy(['tanggal_absensi' => SORT_DESC])
            ->one(); // Mengambil hanya satu entri terbaru
    
        if ($lastAttendance !== null) {
            return $lastAttendance;
        } else {
            return ['error' => 'Tidak ada rekaman kehadiran yang ditemukan.'];
        }
    }
    
}
