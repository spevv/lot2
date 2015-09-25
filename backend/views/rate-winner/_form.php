<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\switchinput\SwitchBox;
/* @var $this yii\web\View */
/* @var $model backend\models\RateWinner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rate-winner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pay')->textInput(['maxlength' => true]) ?>
    
    <?php /* $form->field($model, 'status', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Оплачено?  Если Вкл, то лот оплачен."]])->widget(SwitchBox::className(),[
			    'options' => [
			        'label' => false
			    ],
			    'clientOptions' => [
			        'size' => 'small',
			        'onColor' => 'success',
			        'offColor' => 'danger',
			        'onText' => 'Оплачен',
			        'offText' => 'Не оплачен'
			    ]
			]); */ ?>

    <?php // $form->field($model, 'rate_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
