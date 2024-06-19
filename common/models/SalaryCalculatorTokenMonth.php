<?php

// common/models/SalaryCalculatorToken.php
namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use common\models\EmployeesPositionSalaries;
use common\models\User;
use common\models\Employees; 
use common\models\EmployeesAllowance; 
use common\models\EmployeesTax; 
use Yii;

class SalaryCalculatorTokenMonth extends Model
{
    private $ptkp = [
        'TK0' => 54000000, // Tidak Kawin, 0 Tanggungan
        'K0' => 58500000,  // Kawin, 0 Tanggungan
        'K1' => 63000000,  // Kawin, 1 Tanggungan
        'K2' => 67500000,  // Kawin, 2 Tanggungan
        'K3' => 72000000,   // Kawin, 3 Tanggungan
        'K4' => 72000000,   // Kawin, 3 Tanggungan
        'K5' => 72000000,   // Kawin, 3 Tanggungan
        'K6' => 72000000,   // Kawin, 3 Tanggungan
        'K7' => 72000000,   // Kawin, 3 Tanggungan
        'K8' => 72000000,   // Kawin, 3 Tanggungan
        'K9' => 72000000,   // Kawin, 3 Tanggungan
        'K10' => 72000000,   // Kawin, 3 Tanggungan
        'K11' => 72000000,   // Kawin, 3 Tanggungan
        'K12' => 72000000,   // Kawin, 3 Tanggungan
    ];
    public $selectedMonth;
    public $selectedYear;
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
    public function calculatePKP($totalGaji, $statusKawin, $jumlahTanggungan)
    {
        // Hitung Penghasilan Bruto Bulanan
        $penghasilanBrutoBulanan = $totalGaji;

        // Hitung Biaya Jabatan maksimal 500 ribu / bulan atau 6juta / tahun
        $biayaJabatan = min(0.05 * $penghasilanBrutoBulanan, 500000);

        // Hitung Iuran Pensiun
        $iuranPensiun = 0.0475 * $penghasilanBrutoBulanan;

        // Hitung Penghasilan Netto Bulanan
        $penghasilanNettoBulanan = $penghasilanBrutoBulanan - $biayaJabatan - $iuranPensiun;

        // Hitung Penghasilan Netto Tahunan
        $penghasilanNettoTahunan = $penghasilanNettoBulanan * 12;


        $ptkpKey = $statusKawin . $jumlahTanggungan; // contoh TK0, TK1, TK2
        if (isset($this->ptkp[$ptkpKey])) {
            $ptkp = $this->ptkp[$ptkpKey];
        } else {
            $ptkp = 0;
        }
        

        // Hitung PKP
        $pkp = max(0, $penghasilanNettoTahunan - $ptkp);

        return [
            'iuranJabatan'=>$biayaJabatan,
            'iuranPensiun'=>$iuranPensiun,
            'penghasilanNettoTahunan' => $penghasilanNettoTahunan,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
            'ptkpKey' => $ptkpKey
        ];
    }
    public function calculateTax($pkp)
    {
        $pph21 = 0;

        if ($pkp <= 50000000) {
            $pph21 = 0.05 * $pkp;
        } elseif ($pkp <= 250000000) {
            $pph21 = 0.05 * 50000000 + 0.15 * ($pkp - 50000000);
        } elseif ($pkp <= 500000000) {
            $pph21 = 0.05 * 50000000 + 0.15 * 200000000 + 0.25 * ($pkp - 250000000);
        } else {
            $pph21 = 0.05 * 50000000 + 0.15 * 200000000 + 0.25 * 250000000 + 0.30 * ($pkp - 500000000);
        }

        return $pph21 / 12; // PPh 21 bulanan
    }

     public function calculateByTokenFilter()
    {
        $data = [];
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return ['error' => 'User not authenticated'];
        }

        $user = User::findOne($userId);
        $employeeId = $user->employee_id;
        $employee = Employees::findOne($employeeId);
        $getEmployeePosition = EmployeesPosition::findOne($employee->position_id);
        $getGajiPokok = EmployeesPositionSalaries::findOne(['id' => $getEmployeePosition->position_salary_id]);
        $gajiPokok = $getGajiPokok->gaji_pokok;

        if (!$gajiPokok) {
            return ['error' => 'Position salary not found'];
        }

        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $this->selectedYear, 'MONTH(tanggal_absensi)' => $this->selectedMonth])
            ->all();

        $totalJamKerja = 0;
        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;
            $hasilPengecekan = $this->PengecekanHari($tanggal);
            $checkInLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
                ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();
            $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null;
            $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;

            if ($checkOutTime !== null && $checkInTime !== null) {
                $totalJamKerja += ($checkOutTime - $checkInTime) / (60 * 60);
                $checkInLimit = strtotime('08:15');
                $checkOutLimit = $hasilPengecekan['hari'] === 'Saturday' ? strtotime('14:00') : strtotime('16:00');

                if ($checkInTime > $checkInLimit) {
                    $lateForCheckIn = ($checkInTime - $checkInLimit) / 60;
                    $checkInTime -= $lateForCheckIn * 60;
                }
            }
        }

        $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);
        $totalKehadiran = count($absensiLogs);

        $meal_allowance_day = 0;
        $tunjangan_jabatan = 0;
        $tunjangan_keluarga = 0;
        $tunjangan_transport = 0;
        $tunjangan_kehadiran = 0;
        $deductions_bpjs_kesehatan = 0;
        $deductions_bpjs_ketenagakerjaan = 0;
        $tax_percentage = 0;

        if ($employee->position_id == 1) {
            $get_allowance = EmployeesAllowance::findOne(['id' => 3]);
        } elseif ($employee->position_id == 8 || $employee->position_id == 9) {
            $get_allowance = EmployeesAllowance::findOne(['id' => 2]);
        } else {
            $get_allowance = EmployeesAllowance::findOne(['id' => 1]);
        }

        $meal_allowance_day = $get_allowance->tunjangan_makan;
        $tunjangan_jabatan = $get_allowance->tunjangan_jabatan;
        $tunjangan_keluarga = $get_allowance->tunjangan_keluarga;
        $tunjangan_transport = $get_allowance->tunjangan_transport;
        $tunjangan_kehadiran = $get_allowance->tunjangan_kehadiran;
        $deductions_bpjs_kesehatan = $get_allowance->bpjs_kesehatan;
        $deductions_bpjs_ketenagakerjaan = $get_allowance->bpjs_ketenagakerjaan;
        $get_tax_percentage = EmployeesTax::findOne(['id' => 5]);


        $totalMealAllowance = $meal_allowance_day * $totalKehadiran;
        $totalGaji = $totalMealAllowance+ $gajiPokok;
        $statusKawin = $employee->status_nikah;
        $jumlahTanggungan = $employee->jumlah_tanggungan;
        $pkpResult = $this->calculatePKP($totalGaji, $statusKawin, $jumlahTanggungan);
        $pkp = $pkpResult['pkp'];
        $pph21 = $this->calculateTax($pkp);

        if ($totalKehadiran > 20 || $this->selectedMonth == date('m', strtotime('last day of this month'))) {
            $data[] = [
                'ID Karyawan' => $userId,
                'Jabatan Karyawan' => $getEmployeePosition->position_name,
                'Status Nikah' => $employee->status_nikah,
                'Jumlah Tanggungan' => $employee->jumlah_tanggungan,
                'Gaji Pokok' => (int) ($gajiPokok),
                'Tunjangan Makan per-day' => (int) $meal_allowance_day,
                'Total Kehadiran' => $totalKehadiran . " Hari",
                'Total Jam Bekerja' => $totalJamKerjaFormatted,
                'Total Tunjangan Jabatan' => floatval($tunjangan_jabatan),
                'Total Tunjangan Keluarga' => floatval($tunjangan_keluarga),
                'Total Tunjangan Makan' => floatval($totalMealAllowance),
                'Total Tunjangan Transport' => floatval($tunjangan_transport),
                'Total Tunjangan Kehadiran' => floatval($tunjangan_kehadiran),
                'Total Deductions Bpjs Kesehatan' => floatval($deductions_bpjs_kesehatan),
                'Total Deductions Bpjs Ketenagakerjaan' => floatval($deductions_bpjs_ketenagakerjaan),
                'Total Deductions Biaya Jabatan' => $pkpResult['iuranJabatan'],
                'Total Deductions Iuran Pensiun' => $pkpResult['iuranPensiun'],
                'Total Gaji' => (int) ($totalGaji),
                'Total Netto Setahun' => number_format($pkpResult['penghasilanNettoTahunan'], 0, ',', '.'),
                'Golongan PTKP' => $pkpResult['ptkpKey'],
                'Total PTKP' => $pkpResult['ptkp'],
                'Total PKP' => number_format($pkpResult['pkp'], 0, ',', '.'),
                'Total PPh 21' => (int)($pph21),
                'Earnings' => (int) $totalGaji - (int) $pph21 - floatval($deductions_bpjs_kesehatan) - floatval($deductions_bpjs_ketenagakerjaan),
            ];

            return [
                'Status' => '200',
                'Messages' => 'Success',
                'Month' => $this->selectedMonth . ' ' . $this->selectedYear,
                'data' => $data,
            ];
        } else {
            return [
                'Status' => '204',
               'Messages' => 'Data penggajian belum tersedia. Silakan cek kembali nanti.',
               'data'=> [null]
            ];
        }
    }
}