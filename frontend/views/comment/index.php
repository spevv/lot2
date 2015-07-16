<?php

use yii\helpers\Html;
//use frontend\widgets\Alert;
use yii2mod\alert\Alert;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $form ActiveForm */

$this->title = $name;
?>
<?php Pjax::begin(['options' => ['id'=>'comment', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?> 
<div class="comment-main">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $text; ?>
    <?php
  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
		echo  Alert::widget();
	}
  	?>
    <?= $content ?>

</div>
<?php Pjax::end(); ?>


<?php
if (isset($identity->profile)) {
	$service = $identity->profile['service'];	
}
else{
	$service = '';
}
$js = <<< JS

obj = {
		service: '$service',
	}

// если не авторизирован, то переход к авторизации
if(!obj.service)
{
	swal({
		    title: "Вам нужно авторизироваться",
		    text: "Для того, что бы оставить отзыв, вы должны авторизироваться.",
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
	


JS;
$this->registerJs($js,  $this::POS_READY);
?>	


