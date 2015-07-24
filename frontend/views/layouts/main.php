<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use yii2mod\alert\Alert;
//use frontend\widgets\Alert;
use yii\helpers\Url;

use common\models\Lot;
use yii\helpers\ArrayHelper;
use common\models\GeobaseCity;
use yii\bootstrap\Modal;
use common\models\Follower;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;					    
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

Url::remember();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
   <!-- <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
  
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
    	<div class="head">
	        <div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="top-menu">
							<a href="<?= Url::toRoute(['article/view', 'article' => 'organizatoram-treningov']); ?>">Организаторам тренингов</a>
							<a href="<?= Url::toRoute(['comment/comments']); ?>">Отзывы о сайте</a>
							<?php if(Yii::$app->user->isGuest):	?>
								<?php Modal::begin([
										//'size' => 'modal-sm',
									    'header' => '<h2>Войди через любимую соцсеть</h2>',
									    'toggleButton' => ['label' => 'Авторизоваться', 'class'=>'button-href'],
									    'options' => ['class'=> 'aut-modal', 'id'=>'modal-auth'],
									]);
									echo \nodge\eauth\Widget::widget(array('action' => 'site/login'));
									?>
									<div class="eauth-link">
										<div>Авторизуясь, вы принимаете <a href="<?= Url::toRoute(['article/view', 'article' => 'pravila-auktsiona']); ?>">правила аукциона</a></div>
										<div><a href="<?= Url::toRoute(['article/view', 'article' => 'avtorizatsiya-cherez-sotsset']); ?>">Что такое авторизация через соцсеть?</a></div>
									</div>
									<?php
									Modal::end(); 
								?>
							<?php else: ?>
								<a  href="<?= Url::toRoute(['/site/logout']) ?>" data-method="post">Выход (<?= Yii::$app->user->identity->username; ?>)</a>
								<!--<div class="dropdown account-drop">
									<a id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href=""><?= Yii::$app->user->identity->username; ?></a>
							  		<div class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							  			<a  href="<?= Url::toRoute(['/account/active']) ?>">Личный кабинет</a><br>
							  			<a  href="<?= Url::toRoute(['/site/logout']) ?>" data-method="post">Выход</a>
							  		</div>
							  	</div>-->
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="logo-menu">
							<a href="/">
								<div class="logo"></div>
							</a>
							<div class="menu">
								<?php
						            echo Nav::widget([
									    'items' => [
									   		[
										    	'label' => 'О проекте',
												'linkOptions' =>  ['class'=> 'm-about'],
												'url' => ['article/view', 'article' => 'o-proyekte'],
											],
											[
										    	'label' => 'Отзывы',
												'linkOptions' =>  ['class'=> 'm-comment'],
												'url' => ['article/view', 'article' => 'otzyvy'],
											],
											[
										    	'label' => 'Правила участия',
												'linkOptions' =>  ['class'=> 'm-rule'],
												'url' => ['article/view', 'article' => 'pravila-uchastiya'],
											],
											[
										    	'label' => 'Как развиваться дешевле',
												'linkOptions' =>  ['class'=> 'm-next'],
												'url' => ['article/view', 'article' => 'kak-razvivatsya-deshevle'],
											],
									    ],
									    'options' => ['class' =>'nav nav-pills  nav-lot-menu'],
									]);
						        ?>                                                 
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="header-text">ИНТЕРНЕТ-АУКЦИОН <br>БИЗНЕС-ОБРАЗОВАНИЯ</div>
					</div>
				</div>
			</div>
		</div>

      
        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
          	<?php
  	if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error')){
		echo  Alert::widget();
	}
  	?>
        <?= $content ?>
        </div>
    </div>
	
	
	<div class="pre-footer">
		<div class="container">
			<div class="row">
				<div class="col-xs-3">
					<div class="pre-footer-logo"></div>
				</div>
				<div class="col-xs-3">
					<div class="prefooter-url">
						<a href="<?= Url::toRoute(['article/view', 'article' => 'o-proyekte']); ?>">О проекте</a>
						<a href="<?= Url::toRoute(['article/view', 'article' => 'pravila-uchastiya']); ?>">Правила участия</a>
					</div>
				</div>
				<div class="col-xs-3">
					<div class="prefooter-url">
						<a href="<?= Url::toRoute(['article/view', 'article' => 'publichnaya-oferta']); ?>">Публичная оферта</a>
						<a href="<?= Url::toRoute(['article/view', 'article' => 'organizatoram-treningov']); ?>">Организаторам тренингов</a>
					</div>
				</div>
				<div class="col-xs-3">
					<div class="prefooter-phone">
						<div>8 (495) 123 45 67</div>
						<a href="mailto:info@eduhot.ru">info@eduhot.ru</a>
					</div>
				</div>
			</div>
			<div class="row-margin">
				<div class="row">
					<div class="col-xs-4">
						<div class="prefooter-social-text">Узнавай о новых лотах в наших группах</div>
						<div class="prefooter-social-icon">
							<a href=""><div class="vk"></div></a>
							<a href=""><div class="fb"></div></a>
							<a href=""><div class="od"></div></a>
						</div>
						
					</div>
					<div class="col-xs-4">
						<div class="prefooter-subscribe-text">Подпишитесь на нашу рассылку</div>
						<div class="follower">
							<?php Pjax::begin(['options' => ['id'=>'follower', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?>
							    <?php  $form = new Follower(); ?>
							    <?php $form2 = ActiveForm::begin(['options' => ['data-pjax' => true], 'action'=> ['site/follower'], 'formConfig' => ['showLabels'=>false]]); ?>
							        
							        <?= $form2->field($form, 'mail', [
									    'addon' => [
									        'append' => [
									            'content' => Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'data-pjax' => '1', 'data-method' => 'post']), 
									            'asButton' => true
									        ]
									    ]
									])->input('email', ['placeholder'=>'Ваш e-mail']); ?>
							    <?php ActiveForm::end(); ?>
							<?php Pjax::end(); ?>
						</div><!-- follower -->
					</div>
					<div class="col-xs-4">
						<div class="prefooter-pay"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php if ($this->beginCache('footer', ['duration' => 600])): ?>
    	<footer class="footer">
        <div class="container">
	        <div class="row">
	        	<div class="col-xs-12">
	        		<div class="footer-header">Города присутствия</div>
	        	</div>
	        	<div class="col-xs-12">
				<?php
				$lot = ArrayHelper::map(Lot::find()->where(['public'=> 1])->andWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')])->all(), 'id', 'city_id');
				$lot = array_unique($lot);
				//$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');
				$region = ArrayHelper::map(GeobaseCity::find()
					->where(['id' => $lot])
				    ->orderBy('name')
				    ->all(), 'id', 'name');
				?>
	        		<ul>
		        		<?php foreach($region as $key => $value): ?>
		        			<li>
								<?= Html::a($value, ['city/view', 'id'=>$key], ['class' => '']); ?>
							</li>
		        		<?php endforeach; ?>
					</ul>
	        	</div>
	        </div>
        </div>
    </footer>
	<?php $this->endCache(); endif; ?>
    <?php $this->endBody() ?>
    
    <script type="text/javascript">
    /*<![CDATA[*/
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "http://connect.facebook.net/ru_RU/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
       /*]]>*/
    </script>
</body>
</html>
<?php $this->endPage() ?>
