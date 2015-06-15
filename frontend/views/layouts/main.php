<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
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
							<a href="<?= Url::toRoute(['article/view', 'article' => 'otzyvy']); ?>">Отзывы о сайте</a>
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
								<a  href="<?= Url::toRoute(['/site/logout']) ?>" data-method="post">Выход ( <?= Yii::$app->user->identity->username; ?> )</a>
							<?php endif; ?>
							
							<a href="">Мой регион: Москва и МО</a>
							
							<!--<div class="dropdown">
								<a id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="">Мой регион: Москва и МО</a>
							  <div class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							    <div class="row">
							    	
									<?php
									$lot = ArrayHelper::map(Lot::find()->all(), 'id', 'city_id');
									$lot = array_unique($lot);
									
									//$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');
									$region = ArrayHelper::map(GeobaseCity::find()
										->where(['id' => $lot])
									    ->orderBy('name')
									    ->all(), 'id', 'name');
									?>
									<?php
									$countItemInRow = 2; 
									$rows = ceil(count($lot)/$countItemInRow);
									var_dump($rows);
									if($rows): 
										if($rows<4):	
											if($rows == 1): ?>
												<div class="col-xs-12">
									        		<ul class="drop-list">
										        		<?php foreach($region as $key => $value): ?>
										        			<li>
																<?= Html::a($value, ['city/view', 'id'=>$key], ['class' => '']); ?>
															</li>
										        		<?php endforeach; ?>
													</ul>
									        	</div>
									        <?php elseif($rows == 2): 
									        	
									        	?>
									        <?php elseif($rows == 3):  ?>
									        
									        	<div class="col-xs-12">
									        		<ul class="drop-list">
										        		<?php foreach($region as $key => $value): ?>
										        			<li>
																<?= Html::a($value, ['city/view', 'id'=>$key], ['class' => '']); ?>
															</li>
										        		<?php endforeach; ?>
													</ul>
									        	</div>
									        
											<?php endif; ?>	
										<?php else: ?>
										
										
										<?php endif; ?>
										
									<?php endif; ?>
									
							    </div>
							    
							    
							  </div>
							</div>-->
							
							
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
								<a href="<?= Url::toRoute(['article/view', 'article' => 'o-proyekte']); ?>"><div class="m-about"></div> О проекте</a>
								<a href="<?= Url::toRoute(['article/view', 'article' => 'otzyvy']); ?>"><span class="m-comment"></span> Отзывы</a>
								<a href="<?= Url::toRoute(['article/view', 'article' => 'pravila-uchastiya']); ?>"><span class="m-rule"></span> Правила участия</a>
								<a href="<?= Url::toRoute(['article/view', 'article' => 'kak-razvivatsya-deshevle']); ?>"><span class="m-next"></span> Как развиваться дешевле</a>                                                     
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
           /* NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Лоты', 'url' => ['/lot/index']],
                ['label' => 'Категории', 'url' => ['/category/index']],
                ['label' => 'Статьи', 'url' => ['/article/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();*/
        ?>
        <?php /* echo yii\authclient\widgets\AuthChoice::widget([
     'baseAuthUrl' => ['site/auth']
	])  */ ?>
        <?= Alert::widget() ?>
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
	
    <footer class="footer">
        <div class="container">
	        <div class="row">
	        	<div class="col-xs-12">
	        		<div class="footer-header">Города присутствия</div>
	        	</div>
	        	<div class="col-xs-12">
				<?php
				$lot = ArrayHelper::map(Lot::find()->all(), 'id', 'city_id');
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
        <!--<p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>-->
        </div>
    </footer>

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
