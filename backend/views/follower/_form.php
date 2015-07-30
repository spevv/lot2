<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Follower */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="follower-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
