<?php

use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
//use frontend\widgets\Alert;
use yii2mod\alert\Alert;
?>
<?php Pjax::begin(['options' => ['id'=>'follower', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>


<p>
	У вас нет email, для того что бы участвувать в аукционе, вы должны задать email, на который будут приходить уведомления. 
</p>
<p>
	Напишите ваш email:
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
  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
		echo  Alert::widget();
	}
  	?>
<?php Pjax::end(); ?>