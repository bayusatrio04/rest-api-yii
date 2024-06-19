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
use common\models\SalaryCalculatorTokenYear;
use common\models\calculateByTokenFilter;

class SalaryCalculateByTokenController extends ActiveController
{
    public $modelClass = Salaries::class;

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
    public function actionYear()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $calculator = new SalaryCalculatorTokenYear();
        $request = Yii::$app->request;

        $calculator->selectedYear = $request->post('selectedYear');
        
        $YearSalary = $calculator->calculateByTokenFilter();
        return $YearSalary;
            
    }
}
