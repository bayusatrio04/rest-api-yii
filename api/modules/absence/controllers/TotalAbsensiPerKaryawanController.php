<?php

namespace api\modules\absence\controllers;

use frontend\resource\AbsensiLog;
use api\controllers\ActiveController;
use frontend\resource\AbsensiType;
use Yii;

class TotalAbsensiPerKaryawanController extends ActiveController
{
    public $modelClass = AbsensiLog::class;

    public function actionTotalCheckInMonthYear($year, $month)
    {
        $userId = Yii::$app->user->id;
    
        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }

        $totalAbsensiPerKaryawan = AbsensiLog::find()
        ->select([
            'created_by',
            'COUNT(*) AS total_absensi',
            'SUM(CASE WHEN waktu_absensi <= "08:15:00" THEN 1 ELSE 0 END) AS total_ontime',
            'SUM(CASE WHEN waktu_absensi > "08:15:00" THEN 1 ELSE 0 END) AS total_late',
            'MONTHNAME(tanggal_absensi) AS bulan',
            'YEAR(tanggal_absensi) AS tahun'
        ])
        ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
        ->andWhere(['id_absensi_type' => 1]) // Filter absensi berdasarkan tipe 'CHECK-IN'
        ->andWhere(['created_by' => $userId]) // Filter berdasarkan karyawan yang sedang login
        ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
        ->groupBy(['created_by', 'bulan', 'tahun'])
        ->asArray()
        ->all();
        $totalJamKerja = 0;

        // Mendapatkan semua log absensi untuk user dan bulan/tahun yang dipilih
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
            ->all();

        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;

            // Mencari log CHECK-IN dan CHECK-OUT untuk tanggal tersebut
            $checkInLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();

            $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null;

            if ($checkInTime !== null && $checkOutTime !== null) {
                $totalJamKerja += ($checkOutTime - $checkInTime) / (60 * 60); // Menghitung total jam kerja dalam jam

                // Aturan untuk keterlambatan CHECK-IN dan batas waktu kerja
                $checkInLimit = strtotime('08:15');
                $checkOutLimit = $this->isSaturday($tanggal) ? strtotime('14:00') : strtotime('16:00');

                if ($checkInTime > $checkInLimit) {
                    $lateForCheckIn = ($checkInTime - $checkInLimit) / 60;
                    $totalJamKerja -= $lateForCheckIn / 60; // Mengurangi keterlambatan dari total jam kerja
                }
            }
        }

        // Format hasil total jam kerja
        $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);

        if (empty($totalAbsensiPerKaryawan)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['messages' => '404 Not Found'];
        }
        // Format hasil sebagai array
        $result = [];
        foreach ($totalAbsensiPerKaryawan as $item) {
            $result[] = [
                'messages' => 'success',
                'ID Karyawan' => $item['created_by'],
                'Type' => "CHECK-IN",
                'Total' => $item['total_absensi'],
                'Working Hours'=> $totalJamKerjaFormatted,
                'Total Ontime' => $item['total_ontime'],
                'Total Late' => $item['total_late'],
                'Month' => $item['bulan'],
                'Year' => $item['tahun']
            ];
        }
    

    
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }
    public function actionTotalCheckOutMonthYear($year, $month)
    {
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }
        
        $totalAbsensiPerKaryawan = AbsensiLog::find()
        ->select([
            'created_by',
            'COUNT(*) AS total_absensi',
            'SUM(CASE WHEN waktu_absensi <= "16:35:00" THEN 1 ELSE 0 END) AS total_ontime',
            'SUM(CASE WHEN waktu_absensi > "16:35:00" THEN 1 ELSE 0 END) AS total_late',
            'MONTHNAME(tanggal_absensi) AS bulan',
            'YEAR(tanggal_absensi) AS tahun'
        ])
        ->where(['id_absensi_status' => 2]) // Filter absensi berdasarkan status 'Completed'
        ->andWhere(['id_absensi_type' => 2]) // Filter absensi berdasarkan tipe 'CHECK-OUT'
        ->andWhere(['created_by' => $userId]) // Filter berdasarkan karyawan yang sedang login
        ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
        ->groupBy(['created_by', 'bulan', 'tahun'])
        ->asArray()
        ->all();
        $totalJamKerja = 0;

        // Mendapatkan semua log absensi untuk user dan bulan/tahun yang dipilih
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
            ->all();

        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;

            // Mencari log CHECK-IN dan CHECK-OUT untuk tanggal tersebut
            $checkInLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();

            $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null;

            if ($checkInTime !== null && $checkOutTime !== null) {
                $totalJamKerja += ($checkOutTime - $checkInTime) / (60 * 60); // Menghitung total jam kerja dalam jam

                // Aturan untuk keterlambatan CHECK-IN dan batas waktu kerja
                $checkInLimit = strtotime('08:15');
                $checkOutLimit = $this->isSaturday($tanggal) ? strtotime('14:00') : strtotime('16:00');

                if ($checkInTime > $checkInLimit) {
                    $lateForCheckIn = ($checkInTime - $checkInLimit) / 60;
                    $totalJamKerja -= $lateForCheckIn / 60; // Mengurangi keterlambatan dari total jam kerja
                }
            }
        }

        // Format hasil total jam kerja
        $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);
        if (empty($totalAbsensiPerKaryawan)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['messages' => '404 Not Found'];
        }
            $result = [];
            foreach ($totalAbsensiPerKaryawan as $item) {
                $result[] = [
                    'messages'=> 'success',
                    'ID Karyawan' => $item['created_by'],
                    'Type'=> "CHECK-OUT",
                    'Working Hours'=> $totalJamKerjaFormatted,
                    'Total' => $item['total_absensi'],
                    'Total Ontime' => $item['total_ontime'],
                    'Total Late' => $item['total_late'],
                    'Month' => $item['bulan'],
                    'Year' => $item['tahun']
                ];
            }

     
            return $result;
    }
    public $selectedYear;  
    public $selectedMonth;
    public function actionTotalJamKerjaMonthYear($year, $month)
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }
        $totalKehadiran = AbsensiLog::find()
        ->where(['created_by' => $userId])
        ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
        ->count();

    // Jika tidak ada kehadiran, kembalikan pesan
    if ($totalKehadiran === 0) {
        return [ 'messages'=> "Tidak ada kehadiran di Bulan atau Tahun tersebut."];
    }
        // Inisialisasi total jam kerja
        $totalJamKerja = 0;

        // Mendapatkan semua log absensi untuk user dan bulan/tahun yang dipilih
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
            ->all();

        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;

            // Mencari log CHECK-IN dan CHECK-OUT untuk tanggal tersebut
            $checkInLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();

            $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null;

            if ($checkInTime !== null && $checkOutTime !== null) {
                $totalJamKerja += ($checkOutTime - $checkInTime) / (60 * 60); // Menghitung total jam kerja dalam jam

                // Aturan untuk keterlambatan CHECK-IN dan batas waktu kerja
                $checkInLimit = strtotime('08:15');
                $checkOutLimit = $this->isSaturday($tanggal) ? strtotime('14:00') : strtotime('16:00');

                if ($checkInTime > $checkInLimit) {
                    $lateForCheckIn = ($checkInTime - $checkInLimit) / 60;
                    $totalJamKerja -= $lateForCheckIn / 60; // Mengurangi keterlambatan dari total jam kerja
                }
            }
        }

        // Format hasil total jam kerja
        $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);

        // Kembalikan hasil sebagai respons dari endpoint API
        return [
            'messages'=> 'success',
            'ID Karyawan' => $userId,
            'Total Jam Kerja' => $totalJamKerjaFormatted,
            'Month' => date('F', mktime(0, 0, 0, $month, 10)),
            'Year' => $year
        ];
    }

    // Fungsi untuk memeriksa apakah tanggal adalah hari Sabtu
    private function isSaturday($tanggal)
    {
        return date('l', strtotime($tanggal)) === 'Saturday';
    }

    public function actionTotalOntimeMonthYear($year, $month)
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }

        // Menghitung jumlah kehadiran tepat waktu pada bulan dan tahun yang dipilih
        $totalOntime = AbsensiLog::find()
            ->where(['created_by' => $userId])
            ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
            ->andWhere('id_absensi_type = 1 AND waktu_absensi <= "08:15:00"')
            ->count();
        $totalLate = AbsensiLog::find()
            ->where(['created_by' => $userId])
            ->andWhere(['YEAR(tanggal_absensi)' => $year, 'MONTH(tanggal_absensi)' => $month])
            ->andWhere('id_absensi_type = 1 AND waktu_absensi > "08:15:00"')
            ->count();


        if ($totalOntime === 0) {
                $totalOntime = 0;
            }
        if ($totalLate === 0) {
                $totalLate = 0;
            }
        
        if ($totalOntime === 0 && $totalLate === 0) {
                return [
                    'ID Karyawan' => $userId,
                    'On-Time' => 0,
                    'Late' => 0,
                    'Month' => date('F', mktime(0, 0, 0, $month, 10)),
                    'Year' => $year
                ];
            }
        // Kembalikan hasil total kehadiran tepat waktu sebagai respons dari endpoint API
        return [
            'messages'=> 'success',
            'ID Karyawan' => $userId,
            'On-Time' => $totalOntime,
            'Late' => $totalLate,
            'Month' => date('F', mktime(0, 0, 0, $month, 10)),
            'Year' => $year
        ];
    }

}

    
    

    
    
    
    
    

