<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use frontend\widgets\Alert;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
?>
<?php Pjax::begin(['options' => ['id'=>'contact', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
	<div class="row">
	    <div class="col-lg-12">
	     	<?= Alert::widget() ?>
	        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true], 'action'=> ['lot/contact']]); ?>
	            <?= $form->field($model, 'name') ?>
	            <?= $form->field($model, 'email') ?>
	            <?= $form->field($model, 'subject') ?>
	            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
	            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
	                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
	            ]) ?>
	            <div class="form-group">
	                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button','data-pjax' => '1', 'data-method' => 'post']) ?>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>
	</div>
<?php Pjax::end(); ?>