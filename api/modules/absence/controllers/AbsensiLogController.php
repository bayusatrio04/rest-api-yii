<?php

namespace api\modules\absence\controllers;

use api\controllers\ActiveController;
use frontend\resource\AbsensiLog;
use yii\web\Response;
use yii\web\UploadedFile;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `absence` module
 */
class AbsensiLogController extends ActiveController
{
    public $modelClass = AbsensiLog::class;
    public function actionCreate()
    {
        $model = new AbsensiLog();
        $model->load(Yii::$app->request->post(), '');
        
        
        $dated = date('l');
        $uploadedFile = UploadedFile::getInstanceByName('bukti_hadir');
        if ($uploadedFile && $dated) {
            $model->day =$dated;
            $model->bukti_hadir = $uploadedFile;
        }

        // Save the model
        if ($model->save()) {
            return ['success' => true, 'message' => 'Absensi log created successfully'];
        } else {
            return ['success' => false, 'errors' => $model->errors];
        }
    }
    public function actionCheckInToday()
    {
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }

        $today = date('Y-m-d');
        
        $checkInLogs = AbsensiLog::find()
            ->where(['created_by' => $userId])
            ->andWhere(['tanggal_absensi' => $today])
            ->andWhere(['id_absensi_type' => 1]) // 1 untuk check-in
            ->all();
        
            if (empty($checkInLogs)) {
                return ["messages"=>"Tidak ada catatan kehadiran hari ini."];
            } else {
                return $checkInLogs;
            }
    }
    public function actionGetImage($uploadedFile)
    {
        $filePath = Yii::getAlias('@webroot') . '/uploads/' . $uploadedFile; // Sesuaikan dengan lokasi gambar di server
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new \yii\web\NotFoundHttpException('The requested image does not exist.');
        }
    }
    public function actionSearch()
    {
        // Memeriksa apakah pengguna telah login
        if (!Yii::$app->user->isGuest) {
            // Mendapatkan ID pengguna yang sedang login
            $created_by = Yii::$app->user->identity->id;
    
            // Membuat query untuk AbsensiLog
            $query = AbsensiLog::find();
    
            // Menerapkan filter berdasarkan created_by
            $query->andFilterWhere(['created_by' => $created_by]);
    
            // Mengurutkan catatan berdasarkan waktu_absensi atau tanggal_absensi (terbaru dulu)
            $query->orderBy(['tanggal_absensi' => SORT_DESC]);
    
            // Memuat data menggunakan data provider
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                // Pagination jika diperlukan
                //'pagination' => [
                //    'pageSize' => 10, // Atur ukuran halaman yang diinginkan
                //],
            ]);
    
            // Memuat data provider
            $dataProvider->prepare();
    
            // Mengembalikan data provider sebagai JSON
            return $dataProvider;
        } else {
            // Pengguna belum login, kembalikan pesan kesalahan atau lakukan tindakan lain yang sesuai
            return ['error' => 'Pengguna belum login.'];
        }
    }
    public function actionSearchByStatus($id_absensi_status)
    {
        // Memeriksa apakah pengguna telah login
        if (!Yii::$app->user->isGuest) {
            // Mendapatkan ID pengguna yang sedang login
            $created_by = Yii::$app->user->identity->id;
    
            // Membuat query untuk AbsensiLog
            $query = AbsensiLog::find();
    
            // Menerapkan filter berdasarkan created_by
            $query->andFilterWhere([
                'created_by' => $created_by,
                'id_absensi_status' => $id_absensi_status
            ]);
    
            // Mengurutkan catatan berdasarkan waktu_absensi atau tanggal_absensi (terbaru dulu)
           
    
            // Memuat data menggunakan data provider
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                // Pagination jika diperlukan
                //'pagination' => [
                //    'pageSize' => 10, // Atur ukuran halaman yang diinginkan
                //],
            ]);
    
            // Memuat data provider
            $dataProvider->prepare();
    
            // Mengembalikan data provider sebagai JSON
            return $dataProvider;
        } else {
            // Pengguna belum login, kembalikan pesan kesalahan atau lakukan tindakan lain yang sesuai
            return ['error' => 'Pengguna belum login.'];
        }
    }
    public function actionSearchByType($id_absensi_type)
    {
        // Memeriksa apakah pengguna telah login
        if (!Yii::$app->user->isGuest) {
            // Mendapatkan ID pengguna yang sedang login
            $created_by = Yii::$app->user->identity->id;
    
            // Membuat query untuk AbsensiLog
            $query = AbsensiLog::find();
    
            // Menerapkan filter berdasarkan created_by
            $query->andFilterWhere([
                'created_by' => $created_by,
                'id_absensi_type' => $id_absensi_type
            ]);
    
            // Mengurutkan catatan berdasarkan waktu_absensi atau tanggal_absensi (terbaru dulu)
           
    
            // Memuat data menggunakan data provider
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                // Pagination jika diperlukan
                //'pagination' => [
                //    'pageSize' => 10, // Atur ukuran halaman yang diinginkan
                //],
            ]);
    
            // Memuat data provider
            $dataProvider->prepare();
    
            // Mengembalikan data provider sebagai JSON
            return $dataProvider;
        } else {
            // Pengguna belum login, kembalikan pesan kesalahan atau lakukan tindakan lain yang sesuai
            return ['error' => 'Pengguna belum login.'];
        }
    }
    public function actionLatest()
    {
        if (!Yii::$app->user->isGuest) {
        $userId = Yii::$app->user->identity->id;
        $latestData = AbsensiLog::find()
            ->where(['created_by' => $userId])
            ->orderBy(['tanggal_absensi' => SORT_DESC, 'waktu_absensi' => SORT_DESC])
            ->one();

        return $latestData;
        } else {
    
            return ['error' => 'Harus login ya.'];
        }
    }
    public function actionTotalCheckinAllCompletedByUserLogin()
    {
        if (!Yii::$app->user->isGuest) {
        $userId = Yii::$app->user->identity->id;
        $total = AbsensiLog::find()
        ->where([
            'id_absensi_type' => 1, // CHECK-IN
            'id_absensi_status' => 2, // Completed
            'created_by' => $userId // Terkait dengan pengguna yang sedang login
        ])
        ->count();

        return ['total_check_in_completed_by_current_user' => $total];
        } else {
    
            return ['error' => 'Harus login ya.'];
        }
    }
    public function actionTotalCheckoutAllCompletedByUserLogin()
    {
        if (!Yii::$app->user->isGuest) {
        $userId = Yii::$app->user->identity->id;
        $total = AbsensiLog::find()
        ->where([
            'id_absensi_type' => 2, // CHECK-OUT
            'id_absensi_status' => 2, // Completed
            'created_by' => $userId // Terkait dengan pengguna yang sedang login
        ])
        ->count();

        return ['total_check_out_completed_by_current_user' => $total];
        } else {
    
            return ['error' => 'Harus login ya.'];
        }
    }
    public function actionTotalCheckinAllCompletedByUserLoginMonth($month, $year)
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            
            // Query untuk menghitung total karyawan dengan id_absensi_type == 1 (CHECK-IN)
            // dan id_absensi_status == 2 (Completed) yang terkait dengan pengguna yang sedang login
            // pada bulan dan tahun yang dipilih
            $total = AbsensiLog::find()
                ->where([
                    'id_absensi_type' => 1, // CHECK-IN
                    'id_absensi_status' => 2, // Completed
                    'created_by' => $userId // Terkait dengan pengguna yang sedang login
                ])
                ->andWhere(['MONTH(tanggal_absensi)' => $month])
                ->andWhere(['YEAR(tanggal_absensi)' => $year])
                ->count();
    
            return ['total_check_in_completed_by_current_user_in_selected_month_and_year' => $total];
        } else {
            return ['error' => 'Anda harus login.'];
        }
    }
    public function actionTotalCheckoutAllCompletedByUserLoginMonth($month, $year)
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            
            // Query untuk menghitung total karyawan dengan id_absensi_type == 2 (CHECK-OUT)
            // dan id_absensi_status == 2 (Completed) yang terkait dengan pengguna yang sedang login
            // pada bulan dan tahun yang dipilih
            $total = AbsensiLog::find()
                ->where([
                    'id_absensi_type' => 2, // CHECK-OUT
                    'id_absensi_status' => 2, // Completed
                    'created_by' => $userId // Terkait dengan pengguna yang sedang login
                ])
                ->andWhere(['MONTH(tanggal_absensi)' => $month])
                ->andWhere(['YEAR(tanggal_absensi)' => $year])
                ->count();
    
            return ['total_check_out_completed_by_current_user_in_selected_month_and_year' => $total];
        } else {
            return ['error' => 'Anda harus login.'];
        }
    }
    
    

}
