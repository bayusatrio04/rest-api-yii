<?php

namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use yii\db\Query;
class SalaryCalculator extends Model
{
    public $hourlyRate;

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
    
    public function calculateTotalSalaryPerMonth($createdBy, $month, $year)
    {
        // Misal ambil data absensi berdasarkan 'created_by' dari tabel absensi_log
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $createdBy, 'MONTH(tanggal_absensi)' => $month, 'YEAR(tanggal_absensi)' => $year])
            ->all();
    
        // Inisialisasi total gaji per bulan
        $totalSalaryPerMonth = 0;
    
        foreach ($absensiLogs as $absensiLog) {
            // Hitung gaji untuk setiap tanggal absensi
            $totalSalaryPerMonth += $this->calculateDailySalary($absensiLog);
        }
    
        return $totalSalaryPerMonth;
    }
    
    private function calculateDailySalary($absensiLog)
    {
        // Ambil waktu CHECK-IN
        $checkInTime = strtotime($absensiLog->waktu_absensi);
    
        // Cari data CHECK-OUT yang sesuai dengan tanggal dan 'created_by'
        $checkOutLog = AbsensiLog::find()
            ->where(['created_by' => $absensiLog->created_by, 'tanggal_absensi' => $absensiLog->tanggal_absensi, 'id_absensi_type' => 2])
            ->one();
    
        // Jika ditemukan data CHECK-OUT, dapatkan waktunya
        if ($checkOutLog !== null) {
            $checkOutTime = strtotime($checkOutLog->waktu_absensi);
    
            // Hitung total jam kerja
            $totalJamKerja = ($checkOutTime - $checkInTime) / (60 * 60); // Dalam jam
    
            // Hitung total gaji per hari
            $totalSalaryDay = $totalJamKerja * $this->hourlyRate;
    
            return $totalSalaryDay;
        }
    
        return 0; // Jika tidak ada data CHECK-OUT, maka gaji per hari adalah 0
    }

    
    
}
