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
	                <?= Html::submitButton('Повторно выложить лот', ['class' => 'btn btn-primary clear-lot', 'data-method' => 'post', 'data-confirm' => 'Вы действительно хотите удалить все сделаные ставки этого лота?']) ?>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>
	</div>
	
	
<?php
$js = <<< JS

	/*$( ".clear-lot" ).on( "click", function(event) {
		
		event.preventDefault();
		
		swal({
		    title: "Повторно выложить лот?",
		    text: "Вы удалите все сделанные ставки данного лота.",
		    type: "warning",
		    showCancelButton: true,
		    confirmButtonColor: "#2A8FBD",
		    confirmButtonText: "Да, повторно выложить",
		    cancelButtonText: "Отмена",
		    closeOnConfirm: false,
		    closeOnCancel: true
		    },
		    function (isConfirm) {
		        if (isConfirm) {
		        	$("#form-rate-form").submit();
		        	swal("Удалено!", "Вы очистили все ставки лота.", "success");   
		        } 
		    }
		);	
	});*/
	

	
JS;
$this->registerJs($js,  $this::POS_READY);
?>	
<?php Pjax::end(); ?>