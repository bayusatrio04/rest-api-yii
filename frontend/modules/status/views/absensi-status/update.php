<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AbsensiLog $model */

$this->title = 'Update Absensi Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absensi Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absensi-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
