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
class SalaryCalculateMonthController extends ActiveController
{
    public $modelClass = Salaries::class;

    public function actionCalculate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dated = date('Y-m-d');

        // Ambil ID karyawan yang memiliki id_absensi_type = 1 (CHECK-IN)
        $createdBy = AbsensiLog::find()->select('created_by')->where(['id_absensi_type' => 1])->column();

        $calculator = new SalaryCalculator();
        $result = $calculator->PengecekanHari($dated);

        $total = [];
        foreach ($createdBy as $employeeId) {
            $monthlySalary = $calculator->calculateMonthlySalaryPerEmployee($employeeId);
            $total[$employeeId] = $monthlySalary;
        }

        // Kembalikan respons dalam format JSON
        return [
            'total' => $total,
            'result' => $result,
        ];
    }
    
}
