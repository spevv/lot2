<?php

use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
//use frontend\widgets\Alert;
use yii2mod\alert\Alert;

?>

<?php Pjax::begin(['options' => ['id'=>'follower', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>


	<p>
		На почту мы будем оперативно сообщать об изменениях в твоих торгах — ты ничего не упустишь и вовремя сделаешь правильный шаг.
	</p>
	<p>
	<?php if($user->email): ?>
		Сейчас мы пишем тебе на: <strong><?= $user->email; ?></strong>.
	<?php else:?>
		У вас не заполненое email.
	<?php endif;?>
	</p>
	<p>
		Изменить почту:
	</p>
	    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true], 'action'=> ['account/change-email'],  'formConfig' => ['showLabels'=>false]]); ?>
	        
	        <?= $form->field($user, 'email', [
			    'addon' => [
			        'append' => [
			            'content' => Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'data-pjax' => '1', 'data-method' => 'post']), 
			            'asButton' => true
			        ]
			    ]
			])->input('email', ['placeholder'=>'Ваш e-mail', 'required' => 'required']); ?>
	    <?php ActiveForm::end(); ?>
<?php
if(\Yii::$app->session->getFlash('success')){
$js = <<< JS
	$('#change-email').modal('hide');

	if($("#lot-left").length !== 0) {
	  $.pjax({container: '#lot-left', timeout: 0, scrollTo: false});
	}
JS;
$this->registerJs($js,  $this::POS_READY);
}
?>   
	    
	    
	<?php
	  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
			echo  Alert::widget();
		}
	?>

<?php Pjax::end(); ?>
