<?php

namespace api\modules\absence\controllers;

use frontend\resource\AbsensiLog;
use api\controllers\ActiveController;
use Yii;

class TotalAbsensiPerKaryawanController extends ActiveController
{
    public $modelClass = AbsensiLog::class;


    public function actionTotalPerKaryawanAll()
    {
    // Query untuk menghitung total absensi per karyawan dengan status 'Completed' (id_absensi_status == 1) dan tipe 'CHECK-IN' (id_absensi_type == 1)  (id_absensi_type == 2)tipe 'CHECK-OUT'
    $totalAbsensiPerKaryawan = AbsensiLog::find()
        ->select(['created_by', 'COUNT(*) AS total_absensi'])
        ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
        ->andWhere(['id_absensi_type' => [1]])// Filter absensi berdasarkan tipe 'CHECK-IN dan CHECK-OUT'
        ->groupBy('created_by')
        ->asArray()
        ->all();

    // Mengubah struktur data sesuai kebutuhan
    $result = [];
    foreach ($totalAbsensiPerKaryawan as $item) {
        $result[] = [
            'ID Karyawan' => $item['created_by'],
            'Total' => $item['total_absensi']
        ];
    }

    return $result;
    }
    public function actionTotalPerKaryawanMonth()
    {
        // Query untuk menghitung total absensi per karyawan berdasarkan bulan dari tanggal_absensi
        $totalAbsensiPerKaryawan = AbsensiLog::find()
            ->select([
                'created_by',
                'COUNT(*) AS total_absensi',
                'DATE_FORMAT(tanggal_absensi, "%M") AS month_name'
            ])
            ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
            ->andWhere(['id_absensi_type' => [1, 2]]) // Filter absensi berdasarkan tipe 'CHECK-IN' (1) dan 'CHECK-OUT' (2)
            ->groupBy(['created_by', 'month_name'])
            ->asArray()
            ->all();
    
        // Mengubah struktur data sesuai kebutuhan
        $result = [];
        foreach ($totalAbsensiPerKaryawan as $item) {
            $result[] = [
                'ID Karyawan' => $item['created_by'],
                'Total' => $item['total_absensi'],
                'Bulan' => $item['month_name']
            ];
        }
    
        // Kembalikan hasil sebagai respons dari endpoint API
        return $result;
    }
    public function actionTotalPerKaryawanYear()
    {
    // Query untuk menghitung total absensi per karyawan berdasarkan tahun dari tanggal_absensi
    $totalAbsensiPerKaryawan = AbsensiLog::find()
        ->select([
            'created_by',
            'COUNT(*) AS total_absensi',
            'YEAR(tanggal_absensi) AS year'
        ])
        ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
        ->andWhere(['id_absensi_type' => [1]]) // Filter absensi berdasarkan tipe 'CHECK-IN' (1) dan 'CHECK-OUT' (2)
        ->groupBy(['created_by', 'year'])
        ->asArray()
        ->all();

    // Mengubah struktur data sesuai kebutuhan
    $result = [];
    foreach ($totalAbsensiPerKaryawan as $item) {
        $result[] = [
            'ID Karyawan' => $item['created_by'],
            'Total' => $item['total_absensi'],
            'Tahun' => $item['year']
        ];
    }

    // Kembalikan hasil sebagai respons dari endpoint API
    return $result;
    }
    public function actionTotalPerKaryawanMonthYear()
{
    // Query untuk menghitung total absensi per karyawan berdasarkan bulan dan tahun dari tanggal_absensi
    $totalAbsensiPerKaryawan = AbsensiLog::find()
        ->select([
            'created_by', 
            'COUNT(*) AS total_absensi', 
            'MONTHNAME(tanggal_absensi) AS bulan', 
            'YEAR(tanggal_absensi) AS tahun'
        ])
        ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
        ->andWhere(['id_absensi_type' => [1]]) // Filter absensi berdasarkan tipe 'CHECK-IN' (1) dan 'CHECK-OUT' (2)
        ->groupBy(['created_by', 'bulan', 'tahun'])
        ->asArray()
        ->all();

    // Mengubah struktur data sesuai kebutuhan
    $result = [];
    foreach ($totalAbsensiPerKaryawan as $item) {
        $result[] = [
            'ID Karyawan' => $item['created_by'],
            'Total' => $item['total_absensi'],
            'Bulan' => $item['bulan'],
            'Year' => $item['tahun']
        ];
    }

    // Kembalikan hasil sebagai respons dari endpoint API
    return $result;
}

    
    

    
    
    
    
    
}
