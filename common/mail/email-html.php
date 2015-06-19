<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($mail['subject']) ?>,</p>

    <p>Follow the link below to reset your password:</p>
	
	<?= $mail['messege'];?>
   
</div>
