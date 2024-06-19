<?php

// common/models/SalaryCalculatorTokenYear.php
namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use common\models\EmployeesPositionSalaries;
use common\models\User;
use common\models\Employees; 
use common\models\EmployeesAllowance; 
use common\models\EmployeesTax; 
use Yii;

class SalaryCalculatorTokenYear extends Model
{
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

    public function calculatePKP($totalGaji)
    {
        $biayaJabatan = 0.05 * $totalGaji;
        $iuranPensiun = 0.0475 * $totalGaji;
        $pkp = $totalGaji - $biayaJabatan - $iuranPensiun;
        return $pkp;
    }

    public function calculateTax($pkp)
    {
        $pph21 = 0;

        if ($pkp <= 50000000) {
            $tax = EmployeesTax::findOne(['id' => 7]);
            $tax_0_5 = $tax->percentage;
            $pph21 = $tax_0_5 * $pkp;
        } elseif ($pkp <= 250000000) {
            $tax = EmployeesTax::findOne(['id' => 15]);
            $tax_1_5 = $tax->percentage;
            $pph21 = $tax_1_5 * $pkp;
        } elseif ($pkp <= 500000000) {
            $tax = EmployeesTax::findOne(['id' => 14]);
            $tax_2_5 = $tax->percentage;
            $pph21 =     $tax_2_5 * $pkp;
        } else {
            $tax = EmployeesTax::findOne(['id' => 13]);
            $tax_3_0 = $tax->percentage;
            $pph21 = $tax_3_0* $pkp;
        }

        return $pph21;
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
    
        // Ambil data absensi berdasarkan tahun yang dipilih
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $this->selectedYear])
            ->all();
    
        // Cek jika data absensi tidak ditemukan
        if (empty($absensiLogs)) {
            return [
                'Messages' => 'Data absensi tidak ditemukan untuk tahun yang dipilih.',
            ];
        }
    
        foreach (range(1, 12) as $month) {
            $totalKehadiran = 0;
            $totalJamKerja = 0;
            $startDate = date('Y-m-d', strtotime($this->selectedYear . '-' . $month . '-01'));
            $endDate = date('Y-m-d', strtotime('last day of ' . $startDate));
            foreach ($absensiLogs as $absensiLog) {
                if (date('n', strtotime($absensiLog->tanggal_absensi)) == $month) {
                    $totalKehadiran++;

                }
            }
    
            // Hitung gaji untuk bulan ini
            // ...
    
            // Tambahkan data gaji bulanan ke dalam array data
            if ($totalKehadiran > 20 ) {
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
                    $pkp = $this->calculatePKP($totalGaji);
                    $pph21 = $this->calculateTax($pkp);
                $data[] = [
                    'Month' => date('j', strtotime($startDate)) . '-' . date('j F', strtotime($endDate)),
                    'Total Hadir' => $totalKehadiran,
                    'Total Tunjangan Makan' => floatval($totalMealAllowance),
                    'Total PPh 21' => (int) ($pph21),
                    'Earnings' => (int) $totalGaji - (int) $pph21 - floatval($deductions_bpjs_kesehatan) - floatval($deductions_bpjs_ketenagakerjaan),
                ];
            } elseif ($totalKehadiran < 20 && date('n', strtotime($endDate)) != $month) {
                // Tidak menambahkan data jika total kehadiran kurang dari 20 dan bukan bulan terakhir
                continue;
            }
        }
    
        if (!empty($data)) {
            return [
                'Status' => '200',
                'Messages' => 'Success',
                'Year' => $this->selectedYear,
                'ID Employee'=> $userId,
                'Position'=> $getEmployeePosition->position_name,
                'data' => $data,
            ];
        } else {
            return [
                'Messages' => 'Data penggajian belum tersedia. Silakan cek kembali nanti.',
            ];
        }
    }
    
}
