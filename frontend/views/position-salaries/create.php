<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PositionSalaries $model */

$this->title = 'Create Position Salaries';
$this->params['breadcrumbs'][] = ['label' => 'Position Salaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-salaries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
