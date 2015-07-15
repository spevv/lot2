<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use dosamigos\switchinput\SwitchBox;
use backend\models\CKEditor;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
	<div class="category-form">

	    <?php $form = ActiveForm::begin(); ?>
 		<div class="col-xs-8">
	    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	    </div>
	    
	    <div class="col-xs-2">
    	    <?= $form->field($model, 'priority')->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => '9',
			    'clientOptions' => ['repeat' => 10, 'greedy' => false],
			]) ?>
	    	<?php // $form->field($model, 'priority')->textInput(['maxlength' => true]) ?>
	    </div>
	    
	    <div class="col-xs-2">
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
		</div>
		<div class="col-xs-12">
			<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xs-12">
		    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'full',
		    ]) ?>
	    </div>
		<div class="col-xs-12">
	    	<?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xs-12">
	   		<?= $form->field($model, 'meta_keyword')->textInput(['maxlength' => true]) ?>
		</div>
	   
		<div class="col-xs-12">
		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>
		</div>
		
	    <?php ActiveForm::end(); ?>

	</div>
</div>

<?php
$js = <<< JS

$("#category-name").syncTranslit({destination: "category-slug"});
	
JS;
$this->registerJs($js,  $this::POS_READY);
?>	