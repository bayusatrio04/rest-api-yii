<?php


namespace api\modules\statusabsence\controllers;

use frontend\resource\AbsensiStatus;
use Yii;
use api\controllers\ActiveController;
use yii\web\Response;
/**

 *

 * @package frontend\controllers
 */
class StatusAbsenceController  extends ActiveController
{
    public $modelClass = AbsensiStatus::class;

}
