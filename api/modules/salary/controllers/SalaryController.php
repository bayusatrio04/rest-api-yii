<?php

namespace api\modules\salary\controllers;

use api\controllers\ActiveController;
use api\modules\absence\controllers\TotalAbsensiPerKaryawanController;
use frontend\resource\AbsensiLog;
use frontend\resource\Employees;
use common\models\PositionSalaries;
use common\models\Salaries;
use yii\web\Response;
use Yii;
use common\models\SalaryCalculator;
class SalaryController extends ActiveController
{
    public function actionCalculate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        $request = Yii::$app->request;
        $hourlyRate = $request->post('hourlyRate');
    
        // Buat instance dari SalaryCalculator
        $calculator = new SalaryCalculator();
    
        // Atur nilai hourlyRate
        $calculator->hourlyRate = $hourlyRate;
    
        // Hitung gaji bulanan
        $monthlySalary = $calculator->calculateMonthlySalary();
    
        // Kembalikan respons dalam format JSON
        return ['monthlySalary' => $monthlySalary];
    }
    
}
