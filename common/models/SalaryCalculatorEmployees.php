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
            ->andWhere(['YEAR(tanggal_absensi)'=>$year, 'MONTH(tanggal_absensi)'=> $month, ])
            ->all();
            $total_kehadiran_karyawan = COUNT($absensi_logs);

            if ($total_kehadiran_karyawan > 0) {
                $position_employeess = EmployeesPosition::find()
                  ->where(['id' => $employee->position_id])
                  ->one();
                $jabatan = $position_employeess->position;
        
                $salaries = PositionSalaries::find()
                  ->where(['position_id' => $employee->position_id])
                  ->one();
        
                $basic_salary =  number_format($salaries->basic_salary, 0, '', '');
                $meal_per_day = number_format($salaries->meal_allowance, 0, '', '');
               

            
            if ($salaries) {
                $total_meal_month = $total_kehadiran_karyawan * $salaries->meal_allowance;
                $basic_salary = $salaries->basic_salary;
                $total_salary_month = $basic_salary + $total_meal_month;

                if ($total_salary_month >= 4500000) {
                    $tax_percentage = (float) $salaries->tax_percentage;
                    $tax_amount = $total_salary_month * $tax_percentage;
                    $total_salary_month -= $tax_amount;
                } else {
                    $tax_amount = 0;
                }
            } else {
                $total_meal_month = 0;
                $basic_salary = 0;
                $total_salary_month = 0;
                $tax_amount = 0;
            }
            $data[] = [
                'ID Karyawan' => $employee->id,
                'Jabatan Karyawan' => $jabatan,
                'Total Hadir' => $total_kehadiran_karyawan,
                'Tunjangan Makanan per-Hari' => (int) $meal_per_day,
                'Gaji Pokok' => (int) $basic_salary,
                'Total Tunjangan Makan' => $total_meal_month,
                'Total Pajak PPh21' => $tax_amount,
                'Total Gaji Karyawan' => $total_salary_month,

            ];
        }
    }

            $response = [
                'Status' => '200',
                'Messages' => 'Success',
                'Month' => $formattedDate,
                'data' => $data,
            ];
            return $response;
    
    }
}