<?php

// common/models/SalaryCalculatorToken.php
namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use common\models\PositionSalaries;
use common\models\User;
use common\models\Employees; 
use Yii;

class SalaryCalculatorTokenMonth extends Model
{
    public function PengecekanHari($tanggal)
    {
        list($tahun, $bulan, $hari) = explode('-', $tanggal);

        // Tentukan nama hari
        $namaHari = date('l', strtotime($tanggal)); // Format huruf lengkap (misal: Monday, Tuesday)

        return [
            'tanggal' => $tanggal,
            'hari' => $namaHari
        ];
    }
    
    public function calculateByTokenMonth()
    {
        // Ambil user ID dari token akses
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }

        // Dapatkan basic_salary dari PositionSalaries
        $user = User::findOne($userId);
        $employeeId = $user->employee_id;
        $employee = Employees::findOne($employeeId);
        $positionSalary = PositionSalaries::findOne(['position_id' => $employee->position_id]);

        if (!$positionSalary) {
            return ['error' => 'Position salary not found'];
        }

        $hourlyRate = $positionSalary->basic_salary ; 

        // Ambil data absensi berdasarkan 'created_by' dari tabel absensi_log
        $absensiLogs = AbsensiLog::find()->where(['created_by' => $userId, 'id_absensi_type' => 1])->all(); // Tambahkan all() untuk mendapatkan semua hasil

        // Inisialisasi variabel untuk menyimpan total jam kerja
        $totalJamKerja = 0;
    
        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;
            $ket = $absensiLog->keterangan;
    
            // Panggil fungsi PengecekanHari untuk mendapatkan hasil pengecekan hari
            $hasilPengecekan = $this->PengecekanHari($tanggal);
    
            // Ambil waktu CHECK-IN dan CHECK-OUT
            $checkInTime = strtotime($absensiLog->waktu_absensi); // Waktu check-in dalam detik
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null; // Waktu check-out dalam detik

            // Hitung total jam kerja jika terdapat data CHECK-OUT
            if ($checkOutTime !== null) {
                // Hitung selisih waktu antara CHECK-OUT dan CHECK-IN dalam satuan jam
                $totalJamKerja += ($checkOutTime - $checkInTime) / (60 * 60); // Konversi ke jam

                // Tentukan batas toleransi waktu CHECK-IN dan CHECK-OUT
                $checkInLimit = strtotime('08:15'); // Batas toleransi untuk check-in (8:15)
                $checkOutLimit = $hasilPengecekan['hari'] === 'Saturday' ? strtotime('14:00') : strtotime('16:00'); // Batas toleransi untuk check-out (14:00 for Saturday, 16:00 otherwise)

                // Periksa apakah waktu CHECK-IN dan CHECK-OUT berada dalam batas toleransi
                if ($checkInTime > $checkInLimit) {
                    // Hitung keterlambatan untuk check-in
                    $lateForCheckIn = ($checkInTime - $checkInLimit) / 60; // Hitung keterlambatan dalam menit
                    // Kurangi waktu check-in dengan keterlambatan
                    $checkInTime -= $lateForCheckIn * 60;
                }
            }
        }

        // Konversi total jam kerja ke format "jam menit"
        $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);

        // Hitung total gaji berdasarkan total jam kerja
        $totalSalaryDay = $totalJamKerja  * $hourlyRate;
        $totalKehadiran = count($absensiLogs) . " Hari";
        return [
            'Status'=> '200',
            'ID Karyawan' => $userId,
            'Month' => date('F Y'),
            'data' => [
                [
                    'Total Kehadiran' => $totalKehadiran,
                    'Total Jam Bekerja' => $totalJamKerjaFormatted,
                    'Total Gaji Pokok' => $totalSalaryDay
                ]
            ]
        ];
    }
}