<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PositionSalaries $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="position-salaries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'position_id')->textInput() ?>

    <?= $form->field($model, 'basic_salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meal_allowance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
