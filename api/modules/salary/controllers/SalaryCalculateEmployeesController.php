<?php
namespace api\modules\salary\controllers;

use api\controllers\ActiveController;
use common\models\Salaries;
use common\models\Employees;
use common\models\User;
use common\models\PositionSalaries;
use yii\web\Response;
use Yii;
use common\models\SalaryCalculatorEmployees;


class SalaryCalculateEmployeesController extends ActiveController
{
    public $modelClass = Salaries::class;
    public function actionMonth()  {
        $generate = new SalaryCalculatorEmployees();
        $generate->GenerateAll();

        return $generate->GenerateAll();
    }
}
