<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use yii\captcha\Captcha;
use yii2mod\alert\Alert;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
?>
<?php Pjax::begin(['options' => ['id'=>'contact', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
	<div class="row">
	    <div class="col-lg-12">
	        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true], 'action'=> ['site/contact']]); ?>
	            <?= $form->field($model, 'name') ?>
	            <?= $form->field($model, 'email')->label('Ваш email') ?>
	            <?= $form->field($model, 'subject')->label('Организация') ?>
	            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
	            <div class="form-group">
	                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button', 'data-pjax' => '1', 'data-method' => 'post']) ?>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>
	</div>
 
 <?php
  if(\Yii::$app->session->getFlash('success')){
$js = <<< JS
	$('#contactFormPartner').modal('hide');
JS;
$this->registerJs($js,  $this::POS_READY);
}
?> 
	<?php if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error'))	echo  Alert::widget(); 	?>
<?php Pjax::end(); ?>