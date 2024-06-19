<?php

// common/models/SalaryCalculatorToken.php
namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;
use common\models\PositionSalaries;
use common\models\EmployeesPosition;
use common\models\User;
use common\models\Employees; 
use yii\web\Response;
use Yii;

class SalaryCalculatorEmployees extends Model
{
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
    
    public function calculatePKP($totalGaji)
    {
        $biayaJabatan = 0.05 * $totalGaji;
        $iuranPensiun = 0.0475 * $totalGaji;
        $pkp = $totalGaji - $biayaJabatan - $iuranPensiun;
        return ['pkp' => $pkp, 'iuran_pensiun' => $iuranPensiun];
    }

    public function calculateTax($pkp)
    {
        $pph21 = 0;

        if ($pkp <= 50000000) {
            $tax = EmployeesTax::findOne(['id' => 7]);
            $tax_0_5 = $tax->percentage;
            $pph21 = $tax_0_5 * $pkp;
        } elseif ($pkp <= 250000000) {
            $pph21 = 0.15 * $pkp;
        } elseif ($pkp <= 500000000) {
            $pph21 = 0.25 * $pkp;
        } else {
            $pph21 = 0.30 * $pkp;
        }

        return $pph21;
    }

    public function GenerateAll()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = [];
        $year = date('Y');
        $month = date('m');
        $dateString = $year . '-' . $month . '-01';
        $formattedDate = date("F Y", strtotime($dateString));
        $employees = Employees::find()->all();
        foreach ($employees as $employee) {

            $absensi_logs = AbsensiLog::find()
            ->where(['created_by' => $employee->id, 'id_absensi_type'=> 1])
            ->andWhere(['YEAR(tanggal_absensi)'=>$year, 'MONTH(tanggal_absensi)'=>03 , ])
            ->all();
            $total_kehadiran_karyawan = count($absensi_logs);
            if ($total_kehadiran_karyawan === 0) {
                Yii::info('No absence logs found for employee ID: ' . $employee->id);
                continue;
            }

            if ($total_kehadiran_karyawan > 0) {
                    $get_position = EmployeesPosition::find()
                    ->where(['id' => $employee->position_id])
                    ->one();
                if (!$get_position) {
                    Yii::error('Position not found for employee ID: ' . $employee->id);
                    continue;
                }   
                $jabatan = $get_position->position_name;
        

                $position_id = $get_position->position_salary_id;
                $get_salaries = EmployeesPositionSalaries::find()->where(['id' => $position_id])->one();
                if (!$get_salaries) {
                    Yii::error('Salaries not found for position salary ID: ' . $position_id);
                    continue;
                }
                $gaji_pokok = $get_salaries->gaji_pokok;
      
        
                $get_allowance = null;
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
                
                $total_meal_month = $total_kehadiran_karyawan * $meal_allowance_day;
                $total_salary_month = $gaji_pokok + $total_meal_month;

                // Menghitung PKP dan iuran pensiun
                $pkp_iuran = $this->calculatePKP($total_salary_month);
                $pkp = $pkp_iuran['pkp'];
                $iuran_pensiun = $pkp_iuran['iuran_pensiun'];

                // Menghitung PPh21
                $pph21 = $this->calculateTax($pkp);

                // Mengurangi PPh21 dari total gaji
                $total_salary_month -= $pph21;
                
                $data[] = [
                    'ID Karyawan' => $employee->id,
                    'Jabatan Karyawan' => $jabatan,
                    'Total Hadir' => $total_kehadiran_karyawan,
                    'Tunjangan Makanan per-Hari' => (int)$meal_allowance_day,
                    'Gaji Pokok' => (int)$gaji_pokok,
                    'Iuran Pensiun' => floatval($iuran_pensiun),
                    'Total Tunjangan Jabatan' => floatval($tunjangan_jabatan),
                    'Total Tunjangan Keluarga' => floatval($tunjangan_keluarga),
                    'Total Tunjangan Makan' => floatval($total_meal_month),
                    'Total Tunjangan Transport' => floatval($tunjangan_transport),
                    'Total Tunjangan Kehadiran' => floatval($tunjangan_kehadiran),
                    'Total Deductions Bpjs Kesehatan' => floatval($deductions_bpjs_kesehatan),
                    'Total Deductions Bpjs Ketenagakerjaan' => floatval($deductions_bpjs_ketenagakerjaan),
                    'Total Deductions Pajak PPh21'=> (int)$pph21,
                    'Earnings' => (int)($total_salary_month),

                ];
            }
        }

        if (empty($data)) {
            return [
                'Status' => '200',
                'Messages' => 'Data sedang diproses atau tidak ada data tersedia',
                'Month' => $formattedDate,
                'data' => []
            ];
        }

        return [
            'Status' => '200',
            'Messages' => 'Success',
            'Month' => $formattedDate,
            'data' => $data,
        ];
    }
}
