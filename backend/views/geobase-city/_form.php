<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GeobaseCity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="geobase-city-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

     <?php //echo  $form->field($model, 'region_id')->textInput(['maxlength' => true]) ?>

     <?php //echo  $form->field($model, 'latitude')->textInput() ?>

     <?php //echo  $form->field($model, 'longitude')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
