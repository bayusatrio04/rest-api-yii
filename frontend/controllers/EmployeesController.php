<?php


namespace frontend\controllers;

use frontend\resource\Employees;

/**

 *

 * @package frontend\controllers
 */
class EmployeesController  extends ActiveController
{
    public $modelClass = Employees::class;

}
