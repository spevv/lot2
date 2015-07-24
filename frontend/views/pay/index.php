<?php

use yii\helpers\Html;
use yii2mod\alert\Alert;
//use frontend\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $form ActiveForm */

$this->title = $name;
?>

<div class="comment-main">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $content ?>
    <?php if($button): ?>
    	<div class="pay-button-wrapper">
    		<div class="pay-button">Оплатить</div>
    	</div>
    <?php endif; ?>
</div>

<?php
if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
	echo  Alert::widget();
}
?>

<?php
$identity = Yii::$app->getUser()->getIdentity();
if (isset($identity->profile)) {
	$service = $identity->profile['service'];	
}
else{
	$service = '';
}
$js = <<< JS

obj = {
		service: '$service',
		social: $social,
	}
	//location.href = "$url";
	ReturnAlert = {
		lotSuccess: function(){
			//$("#form-lot-left").submit();
			// через несколько секунд переход на страницу оплаты
			location.href = "$url";
			swal({
				title: "Поздравляем!",
				text: "Через несколько секунд вы перейдете на страницу платежного шлюза.",
				type: "success",
				timer: 3000,
				confirmButton: false,
				confirmButtonColor: "#2A8FBD",
			    confirmButtonText: "Ставка принята",
			});
		},
		
		lotWait: function(){
			swal({
				title: "Пожалуйста, подождите!",
				text: "Подтвердите размещение записи в вашей соцсети, которе показано в новом окне браузера. Если у вас не появилось окно, то выш браузер блокирует его.",
				type: "warning",
				timer: 60000,
				showConfirmButton: false
			});
		},
		
		lotError: function(){
			swal({
				title: "Не удалось разместить запись!",
				text: "Чтобы все получилось, разместите запись в соцсети.",
				type: "error",
				timer: 3000,
				confirmButtonColor: "#2A8FBD",
			    confirmButtonText: "Закрыть",
			});
		},
	}
	
	
$( ".pay-button" ).on( "click", function(event) {
	event.preventDefault();
		swal({
		    title: "Получите доступ к оплате",
		    text: "По правилам аукциона нужно рассказать о победе в соцсети, что бы перейти к оплате.",
		    type: "warning",
		    showCancelButton: true,
		    confirmButtonColor: "#2A8FBD",
		    confirmButtonText: "Перейти к оплате",
		    cancelButtonText: "Отмена",
		    closeOnConfirm: false,
		    closeOnCancel: true
	    },
	    function (isConfirm) {
	        if (isConfirm) {
	        	if(obj.service){
	        		console.log(obj.service);
	        		switch(obj.service){
						case 'facebook':
							ReturnAlert.lotWait();
							ShareSocial.fb(obj.social.fb);
							ShareSocial.wiretapping(ReturnAlert);
							break;
							
						case 'vkontakte':
							ReturnAlert.lotWait();
							ShareSocial.vk(obj.social.vk);
							ShareSocial.wiretapping(ReturnAlert);
							break;
							
						case 'odnoklassniki':
							ReturnAlert.lotWait();
							ShareSocial.ok(obj.social.ok.url);
							ShareSocial.wiretapping(ReturnAlert);
							break;
					}
				}
	        }
	    });	
});
	
	


// если не авторизирован, то переход к авторизации
if(!obj.service)
{
	swal({
		    title: "Вам нужно авторизироваться",
		    text: "Для того, что бы перейти к оплате, вы должны авторизироваться.",
		    type: "warning",
		    showCancelButton: false,
		    confirmButtonColor: "#2A8FBD",
		    confirmButtonText: "Перейти к авторизации",
		    closeOnConfirm: true
	    },
	    function (isConfirm) {
	        if (isConfirm) {
	        	$('#modal-auth').modal();
	        }
	    });	
}

if($social){
	ShareSocial.vkInit(obj.social.vk);
	ShareSocial.fbInit(obj.social.fb);
}
	


// поп ап - Я победил!

JS;
$this->registerJs($js,  $this::POS_READY);
?>	


