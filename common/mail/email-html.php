<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
$urlSite = Yii::$app->urlManager->createAbsoluteUrl(['site/index']);


if(isset($mail['follower']))
{
	$cancelLink = Yii::$app->urlManager->createAbsoluteUrl(['site/cancel-subscription', 'email' => $mail['email']]);
	$cancel = '<a href="'.$cancelLink.'" style="font-family: Arial; color: #f8f8f8; font-size: 15px; font-weight: 400; text-align: right;">Отписаться от рассылки.</a>';
}
else
{
	$cancel= '';
}
?>
<div class="email" style="width: 600px; margin: auto;">
	<div class="header" style="background: #212121; border-bottom: 4px solid #f55347; padding: 40px;">
		<a href="<?= $urlSite ?>"><div style="background: url(<?= $urlSite ?>image/footer-EduHot.png); width: 241px; height: 47px; display: inline-block;"></div></a>
		<div style="font-family: Arial; color: #8f9090; font-size: 16px; font-weight: 400; line-height: 21.003px; text-align: left;  display: inline-block;   margin-left: 50px;">
			Интернет-аукцион<br> бизнес-образования
		</div>
	</div>
	<div class="content" style="background: #fff; padding: 40px; font-family: Arial; color: #8f9090; font-size: 15px; font-weight: 400; line-height: 25.003px; text-align: left; ">
		<?= $mail['messege'];?>
		<br>
		<p>
		C Уважением команда EduHot<br>
		8 (495) 280-12-12
		</p>
	</div>
	<div class="footer" style="background: #212121; border-top: 4px solid #f55347; padding: 40px;">
		<div style=" display: inline-block; font-family: Arial; color: #f8f8f8; font-size: 15px; font-weight: 400; text-align: left; width: 170px;">
			Узнайте о новых лотах в наших группах
			<div>
				<a href="https://vk.com/eduhot" target="_blank" style="display: inline-block;   margin: 5px 5px 5px 0;"><div style="  background: url(<?= $urlSite ?>image/icon/Layer47_cr1.png) no-repeat;   width: 35px;   height: 35px;"></div></a>
				<a href="https://www.facebook.com/EduHot.biz" target="_blank" style="display: inline-block;   margin: 5px 5px;"><div style="  background: url(<?= $urlSite ?>image/icon/Layer47_cr2.png) no-repeat;   width: 35px;   height: 35px;"></div></a>
				<a href="http://ok.ru/eduhot" target="_blank" style="display: inline-block;   margin: 5px 5px;"><div style="  background: url(<?= $urlSite ?>image/icon/Layer47_cr3.png) no-repeat;   width: 35px;   height: 35px;"></div></a>
			</div>
		</div>
		
		<div style=" float: right; display: block; font-family: Arial; color: #f8f8f8; font-size: 15px; font-weight: 400; text-align: right;">
			Спасибо, что выбрали нас!<br>
			Письмо не является спамом.<br>
			<br>
			<?= $cancel ?>
		</div>
	</div>
</div>
