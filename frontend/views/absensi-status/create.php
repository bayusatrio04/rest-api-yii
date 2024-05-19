<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AbsensiLog $model */

$this->title = 'Create Absensi Log';
$this->params['breadcrumbs'][] = ['label' => 'Absensi Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absensi-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
