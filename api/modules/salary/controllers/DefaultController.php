<?php

namespace api\modules\salary\controllers;
use api\controllers\ActiveController;
use common\models\Salaries;
use yii\web\Controller;
use yii\web\Response;
use Yii;
use common\models\SalaryCalculator;
/**
 * Default controller for the `salary` module
 */
class DefaultController extends ActiveController
{
    public $modelClass = Salaries::class;
    /**
     * Renders the index view for the module
     * @return array
     */
    public function actionIndex()
    {
        // return $this->render('index');
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
