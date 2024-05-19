<?php

namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use yii\db\Query;

class SalaryCalculatorDay extends Model
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
    
    public function calculateMonthlySalaryPerEmployee($createdBy)
    {
        // Misal ambil data absensi berdasarkan 'created_by' dari tabel absensi_log
        $absensiLogs = AbsensiLog::find()->where(['created_by' => $createdBy, 'id_absensi_type' => 1])->all(); // Tambahkan all() untuk mendapatkan semua hasil

        // Inisialisasi array untuk menyimpan hasil perhitungan per karyawan
        $result = [];
    
        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;
            $ket = $absensiLog->keterangan;
    
            // Panggil fungsi PengecekanHari untuk mendapatkan hasil pengecekan hari
            $hasilPengecekan = $this->PengecekanHari($tanggal);
    
            // Ambil waktu CHECK-IN dan CHECK-OUT
            $checkInTime = strtotime($absensiLog->waktu_absensi); // Waktu check-in dalam detik
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $createdBy, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null; // Waktu check-out dalam detik

            // Hitung total jam kerja jika terdapat data CHECK-OUT
            if ($checkOutTime !== null) {
                // Hitung selisih waktu antara CHECK-OUT dan CHECK-IN dalam satuan jam
                $totalJamKerja = ($checkOutTime - $checkInTime) / (60 * 60); // Konversi ke jam

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

                // Konversi total jam kerja ke format "jam menit"
                $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);

                // Simpan hasil perhitungan untuk setiap tanggal
                $result[] = [
                    'tanggal' => $tanggal,
                    'hari' => $hasilPengecekan['hari'],
                    'Waktu Check IN' => date('H:i', $checkInTime), // Konversi waktu check-in ke format jam:menit
                    'Keterangan' => $ket,
                    'Waktu Check OUT' => date('H:i', $checkOutTime), // Konversi waktu check-out ke format jam:menit
                    'totalJamKerja' => $totalJamKerjaFormatted,
                    'totalSalaryDay' => $totalJamKerja * $this->hourlyRate, 
                ];
            }
        }
        return $result;
    }
}
