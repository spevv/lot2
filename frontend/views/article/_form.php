<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii2mod\alert\Alert;
?>

<?php Pjax::begin(['options' => ['id'=>'organization', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
<div class="organization-bg1"></div>
<div class="organization-form">
	<div class="organization-header">Напишите нам!</div>
	<div class="organization-hr"></div>
	<div class="organization-text">Мы с радостью рассмотрим ваши предложения!</div>

	<?php $form = ActiveForm::begin([
		'method' => 'post',
		'options' => ['data-pjax' => 'true', 'id'=>'form-organization'],
		'formConfig' => [
			'showLabels'=> false,
		],
		'action'=> ['article/contact']
	]); ?>

		<?= $form->field($model, 'name', [])->input('name', ['placeholder'=>'Ваше имя']); ?>
	    <?= $form->field($model, 'email', [])->input('email', ['placeholder'=>'Ваш e-mail']); ?>
	    <?= $form->field($model, 'subject', [])->input('subject', ['placeholder'=>'Организация']); ?>
	    <?= $form->field($model, 'body', [
		    'inputOptions' => [
		        'placeholder' => 'Сообщение',
		    ],
	    ])->textArea(['rows' => '6']) ?>
	    <div class="form-group">
	        <?= Html::submitButton('Отправить', ['class' => 'organization-btn']) ?>
	    </div>
	<?php ActiveForm::end(); ?>
	
	
	<div class="organization-prefooter">Ваши данные в безопастности и не попадут третьим лицам!</div>
</div>
<div class="organization-bg2"></div>

<?php
  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
		echo  Alert::widget();
	}
  	?>
<?php Pjax::end(); ?>