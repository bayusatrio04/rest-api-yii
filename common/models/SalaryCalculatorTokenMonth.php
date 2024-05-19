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
    
    public function calculateByTokenMonth()
    {
        $data=[];
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
        $mealAllowance = number_format($positionSalary->meal_allowance, 0, '', '') ; 
        $selectedYear = date('Y');
        $selectedMonth = date('m');
        // Ambil data absensi berdasarkan 'created_by' dari tabel absensi_log
        // $absensiLogs = AbsensiLog::find()->where(['created_by' => $userId, 'id_absensi_type' => 1])->all(); 
        $absensiLogs = AbsensiLog::find()
            ->where(['created_by' => $userId, 'id_absensi_type' => 1])
            ->andWhere(['YEAR(tanggal_absensi)' => $selectedYear, 'MONTH(tanggal_absensi)' => $selectedMonth])
            ->all();

        // Inisialisasi variabel untuk menyimpan total jam kerja
        $totalJamKerja = 0;
    
        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;
            $ket = $absensiLog->keterangan;
    
            // Panggil fungsi PengecekanHari untuk mendapatkan hasil pengecekan hari
            $hasilPengecekan = $this->PengecekanHari($tanggal);
    
            // Ambil waktu CHECK-IN dan CHECK-OUT
            $checkInLog = AbsensiLog::find()
            ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
            ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();
                $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null; // Waktu check-out dalam detik
                $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;

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
        // $totalSalaryDay = $totalJamKerja  * $hourlyRate;
        $totalBasicSalary =  number_format($hourlyRate, 0, '', '');
        $totalKehadiran = count($absensiLogs) . " Hari";
        $totalMealAllowance = count($absensiLogs) * $mealAllowance ;

        $data[] = 
            [
                'ID Karyawan' => $userId,
                'Total Kehadiran' => $totalKehadiran,
                'Total Jam Bekerja' => $totalJamKerjaFormatted,
                'Uang makan per-Day' => $mealAllowance,
                'Uang makan per-Month' => $totalMealAllowance,
                'Gaji Pokok' => $totalBasicSalary,
                'Total Gaji' => $totalMealAllowance +$totalBasicSalary,
               
         
            ];
   
        $response = [
            'Status' => '200',
            'Messages' => 'Success',
            'Month' => date('F Y'),
            'data' => $data,
        ];
        return  $response;
        
    }
    public function calculateByTokenFilter()
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
        $jabatan = EmployeesPosition::findOne(['id'=> $employee->position_id]);

        if (!$positionSalary) {
            return ['error' => 'Position salary not found'];
        }


        $absensiLogs = AbsensiLog::find()
        ->where(['created_by' => $userId, 'id_absensi_type' => 1, 
        'MONTH(tanggal_absensi)'=>$this->selectedMonth, 'YEAR(tanggal_absensi)'=>$this->selectedYear])
        ->all();

        $totalJamKerja = 0;
    
        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;
            $ket = $absensiLog->keterangan;
    
            // Panggil fungsi PengecekanHari untuk mendapatkan hasil pengecekan hari
            $hasilPengecekan = $this->PengecekanHari($tanggal);
    
            // Ambil waktu CHECK-IN dan CHECK-OUT
            $checkInLog = AbsensiLog::find()
            ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 1])
                ->one();
            $checkOutLog = AbsensiLog::find()
            ->where(['created_by' => $userId, 'tanggal_absensi' => $tanggal, 'id_absensi_type' => 2])
                ->one();
                $checkOutTime = $checkOutLog ? strtotime($checkOutLog->waktu_absensi) : null; // Waktu check-out dalam detik
                $checkInTime = $checkInLog ? strtotime($checkInLog->waktu_absensi) : null;

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
   
        $totalKehadiran = COUNT($absensiLogs) ;
        if($totalKehadiran > 0){
            $tax_percentage = (float)$positionSalary->tax_percentage;

            $basic_salary =  number_format($positionSalary->basic_salary, 0, '', '');
            $meal_per_day = number_format($positionSalary->meal_allowance, 0, '', ''); 

            if($positionSalary){
                $totalMealAllowance = count($absensiLogs) * $meal_per_day;
                $totalGaji = $basic_salary + $totalMealAllowance;

                if($totalGaji >= 4500000){

                    $tax_amount = $totalGaji * $tax_percentage;
                    $totalGaji -= $tax_amount;

                }else{

                    $tax_amount = 0;
                    
                }
            }else{
                $totalMealAllowance = 0;
                $totalGaji = 0;
                $basic_salary = 0;
                $tax_amount = 0;
            }
            $totalJamKerjaFormatted = sprintf('%d jam %d menit', floor($totalJamKerja), ($totalJamKerja - floor($totalJamKerja)) * 60);

            $data[] = 
            [
                'ID Karyawan' => $userId,
                'Jabatan Karyawan' => $jabatan->position,
                'Total Kehadiran' => $totalKehadiran,
                'Total Jam Bekerja' => $totalJamKerjaFormatted,
                'Tunjangan Makan per-Day' => (int)$meal_per_day,
                'Tunjangan Makan per-Month' => $totalMealAllowance,
                'Gaji Pokok' => (int)$basic_salary,
                'Pajak PPh21'=> $tax_amount,
                'Total Gaji' => $totalGaji,
               
         
            ];
            $dateString = $this->selectedYear . '-' . $this->selectedMonth . '-01';
            $formattedDate = date("F Y", strtotime($dateString));
            $response = [
                'Status' => '200',
                'Messages' => 'Success',
                'Month' => $formattedDate,
                'data' => $data,
            ];
            return  $response;

        }else{
             Yii::$app->response->statusCode = 204;
            return [
                'status'=>'204',
                'Message'=>'No Content',
                
                ];
        }
    }
}