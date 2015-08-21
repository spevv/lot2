<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use yii2mod\alert\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
?>
<?php Pjax::begin(['options' => ['id'=>'form-rate', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
	<div class="row">
	    <div class="col-lg-12">
	        	<?php  $form = ActiveForm::begin([
				   'action' => ['/lot/clean-rate'],
			        'method' => 'post',
			        'options' => ['data-pjax' => 'true', 'id'=>'form-rate-form'],
			        //'id' => 'form-lot-rate', 
			        'formConfig' => [
			        	'showLabels'=> false,
			        ],
				]); ?>
	        	<?= $form->field($model, 'lot_id')->hiddenInput();  ?> 
	        	<?= $form->field($model, 'status')->hiddenInput();  ?> 
	            <div class="form-group">
	                <?= Html::submitButton('Повторно выложить лот', ["data-toggle"=>"tooltip", "title"=>"Вся история ставок будет обновлена. При повторном вылаживании лота не забудьте установить новое время лота.", 'class' => 'btn btn-primary clear-lot', 'data-method' => 'post', 'data-confirm' => 'Вы действительно хотите удалить все сделаные ставки этого лота?']) ?>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>
	</div>
<?php Pjax::end(); ?>