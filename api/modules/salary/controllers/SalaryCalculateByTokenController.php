<?php
namespace api\modules\salary\controllers;

use api\controllers\ActiveController;
use common\models\Salaries;
use common\models\Employees;
use common\models\User;
use common\models\PositionSalaries;
use yii\web\Response;
use Yii;
use common\models\SalaryCalculatorToken;
use common\models\SalaryCalculatorTokenMonth;
use common\models\calculateByTokenFilter;

class SalaryCalculateByTokenController extends ActiveController
{
    public $modelClass = Salaries::class;
    public function actionCreate(){
        Yii::$app->response->format = Response::FORMAT_JSON;


        // Dapatkan basic_salary dari PositionSalaries
     


  
        // Buat instance dari SalaryCalculatorTokenMonth
        $calculator = new SalaryCalculatorTokenMonth();
        $monthlySalary = $calculator->calculateByTokenMonth();
        $model = new Salaries();
        $model->employee_id = $monthlySalary['ID Karyawan'];
        $model->salary_date = $monthlySalary['Month']; // Simpan bulan gaji
        $model->total_salary = $monthlySalary['data'][0]['Total Gaji']; // Simpan total gaji
        $model->save();
        return 'success';
    }
    public function actionCalculate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        // Buat instance dari SalaryCalculatorToken
        $calculator = new SalaryCalculatorToken();
    
        // Hitung gaji bulanan berdasarkan pengguna yang sedang login
        $monthlySalary = $calculator->calculateByToken();
    
        // Kembalikan respons dalam format JSON
        return [
            'Day_salary' => $monthlySalary,
        ];
    }
    public function actionCalculateMonth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        // Buat instance dari SalaryCalculatorTokenMonth
        $calculator = new SalaryCalculatorTokenMonth();
        $monthlySalary = $calculator->calculateByTokenMonth();

    
        // Kembalikan respons dalam format JSON
        return [
            'Status' => $monthlySalary['Status'],
            'ID Karyawan' => $monthlySalary['ID Karyawan'],
            'Month' => $monthlySalary['Month'],
            'data' => $monthlySalary['data'],
        ];
    }
    public function actionFilter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $calculator = new SalaryCalculatorTokenMonth();
        $request = Yii::$app->request;
        $calculator->selectedMonth = $request->post('selectedMonth');
        $calculator->selectedYear = $request->post('selectedYear');
        
        $monthlySalary = $calculator->calculateByTokenFilter();
        return $monthlySalary;
            
    }
}
