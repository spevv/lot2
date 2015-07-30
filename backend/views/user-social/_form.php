<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSocial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-social-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'client')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
