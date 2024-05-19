<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\AbsensiLog $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_absensi_type')->textInput() ?>

    <?= $form->field($model, 'id_absensi_status')->textInput() ?>

    <?= $form->field($model, 'tanggal_absensi')->textInput() ?>

    <?= $form->field($model, 'waktu_absensi')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bukti_hadir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
