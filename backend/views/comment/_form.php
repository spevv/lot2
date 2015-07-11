<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use dosamigos\switchinput\SwitchBox;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model backend\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin([
    	'formConfig' => [
		   	'showLabels'=> false,
		],
			        ]); ?>

  

	 <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>
  
    
    <?= $form->field($model, 'public')->widget(SwitchBox::className(),[
			    'options' => [
			        'label' => false
			    ],
			    'clientOptions' => [
			        'size' => 'small',
			        'onColor' => 'success',
			        'offColor' => 'danger',
			        'onText' => 'Вкл',
			        'offText' => 'Выкл'
			    ]
			]);?>



    <?= $form->field($model, 'user2_id')->textInput(['maxlength' => true])->hiddenInput(); ?>

    <?= $form->field($model, 'lot_id')->textInput(['maxlength' => true])->hiddenInput(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
