<?php
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii2mod\alert\Alert;
?>


<?php Pjax::begin(['options' => ['id'=>'follower', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
	<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true],  'formConfig' => ['showLabels'=>false]]); ?>
			
		<?= $form->field($model, 'toWinner')->checkbox(array('label'=>'Моя ставка победила')); ?>
		<?= $form->field($model, 'toLoser')->checkbox(array('label'=>'Торги завершены')); ?>
		<?= $form->field($model, 'slewRate')->checkbox(array('label'=>'Мою ставку перебили')); ?>
		<?= $form->field($model, 'endsInMinutes')->checkbox(array('label'=>'Мои торги подходят к концу')); ?>
		<?= $form->field($model, 'successPay')->checkbox(array('label'=>'Оплата прошла успешно')); ?>
		<?= $form->field($model, 'interest')->checkbox(array('label'=>'Стартует новый экспресс-аукцион')); ?>
		<?= $form->field($model, 'toPayClose')->checkbox(array('label'=>'Оплата лота просрочена')); ?>
		
		<div class="form-group">
	        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'data-pjax' => '1', 'data-method' => 'post']) ?>
	    </div>
		
	<?php ActiveForm::end(); ?>
  	<?php
  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
		echo  Alert::widget();
	}
  	?>
<?php Pjax::end(); ?>

<!-- slewRate - Мою ставку перебили	
		toLoser  - Торги завершены	
		successPay - Оплата прошла успешно	
		toPay close - Оплата лота просрочена	
		toPay - Надо напомнить об оплате	
		toWinner - Моя ставка победила	
		endsInMinutes - Мои торги подходят к концу	
		interest - Стартует новый экспресс-аукцион	
		
		Появился ответ на мой вопрос
		Отписаться от всех оповещений
		Мне нужно получить ваучер -->