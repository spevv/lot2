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
use  yii\web\View;

use common\models\Article;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);



$articles = Article::find()->all();
if($articles)
{
    $urls['organizations'] = [];
    $urls['o-proyekte'] = [];
    $urls['pravila-uchastija'] = [];
    $urls['kak-razvivatsya-deshevle'] = [];
    $urls['publichnaya-oferta'] = [];
    foreach($articles as $article)
    {
        switch( $article->id )
        {
            case 10:
                $urls['organizations'] = ['article/view', 'article' => $article->slug];
                break;
            case 5:
                $urls['o-proyekte'] = ['article/view', 'article' => $article->slug];
                break;
            case 7:
                $urls['pravila-uchastija'] = ['article/view', 'article' => $article->slug];
                break;
            case 8:
                $urls['kak-razvivatsya-deshevle'] = ['article/view', 'article' => $article->slug];
                break;
            case 9:
                $urls['publichnaya-oferta'] = ['article/view', 'article' => $article->slug];
                break;
        }
    }
}

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
                        <!--<a href="<?= Url::toRoute($urls['organizations']); ?>">Организаторам образования</a>-->
                        <!--<a href="<?= Url::toRoute(['comment/comments']); ?>">Отзывы о сайте</a>-->
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
                                <div><a id="toSweet" href="">Что такое авторизация через соцсеть?</a></div>
                                <!--<div><a href="<?= Url::toRoute(['article/view', 'article' => 'avtorizatsiya-cherez-sotsset']); ?>">Что такое авторизация через соцсеть?</a></div>-->
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
                            <div class="logo-hidden"></div>
                            <div class="logo"></div>
                        </a>
                        <div class="menu">
                            <?php
                            echo Nav::widget([
                                'items' => [
                                    [
                                        'label' => 'О проекте',
                                        'linkOptions' =>  ['class'=> 'm-about'],
                                        'url' => $urls['o-proyekte'],
                                    ],
                                    /*[
                                        'label' => 'Отзывы',
                                        'linkOptions' =>  ['class'=> 'm-comment'],
                                        'url' => ['article/view', 'article' => 'otzyvy'],
                                    ],*/
                                    [
                                        'label' => 'Правила участия',
                                        'linkOptions' =>  ['class'=> 'm-rule'],
                                        'url' => $urls['pravila-uchastija'],
                                    ],
                                    [
                                        'label' => 'Организаторам образования',
                                        'linkOptions' =>  ['class'=> 'm-next'],
                                        'url' => $urls['organizations'],
                                    ],
                                    /*[
                                        'label' => 'Как развиваться дешевле',
                                        'linkOptions' =>  ['class'=> 'm-next'],
                                        'url' => $urls['kak-razvivatsya-deshevle'],
                                    ],*/
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
                <div class="col-xs-3">
                    <div class="header-left-img"></div>
                </div>
                <div class="col-xs-6">
                    <div class="header-text"><div>Сделайте ставку</div>на свое образование!</div>
                </div>
                <div class="col-xs-3">
                    <div class="header-right-img"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php
        if(\Yii::$app->session->getFlash('success') or \Yii::$app->session->getFlash('error') or  \Yii::$app->session->getFlash('info')){
            echo  Alert::widget();
        }
        ?>
    </div>
</div>

<?= $content ?>



<div class="pre-footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <div class="pre-footer-logo"></div>
                <div class="pre-footer-logo-text">Интернет-аукцион бизнес образования</div>

                <div class="social-block">
                    <div class="prefooter-social-text">Узнавай о новых лотах <br>в наших группах</div>
                    <div class="prefooter-social-icon">
                        <a href="https://vk.com/eduhot" target="_blank"><div class="vk"></div></a>
                        <a href="https://www.facebook.com/EduHot.biz" target="_blank"><div class="fb"></div></a>
                        <a href="http://ok.ru/eduhot" target="_blank"><div class="od"></div></a>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="prefooter-url">
                    <a href="<?= Url::toRoute($urls['o-proyekte']); ?>">О проекте</a>
                    <a href="<?= Url::toRoute($urls['pravila-uchastija']); ?>">Правила участия</a>
                    <a href="<?= Url::toRoute($urls['publichnaya-oferta']); ?>">Публичная оферта</a>
                    <a href="<?= Url::toRoute($urls['organizations']); ?>">Организаторам образования</a>
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
                <div class="prefooter-pay-text prefooter-subscribe-text">Способы оплаты</div>
                <div class="prefooter-pay"></div>
            </div>
            <div class="col-xs-2">
                <div class="prefooter-phone">
                    <div class="prefooter-ph-block">
                        <div class="prefooter-phone-ph">8 (495) 123 45 67</div>
                        <div class="prefooter-phone-ph-text"> Техподдержка 24 часа</div>
                    </div>

                    <div class="prefooter-email-block">
                        <div class="prefooter-email-text"> Электронная почта:</div>
                        <a href="mailto:info@eduhot.ru">info@eduhot.ru</a>
                    </div>
                </div>
            </div>

        </div>
        <!--<div class="row-margin">
            <div class="row">
                <div class="col-xs-3">

                </div>
                <div class="col-xs-3"></div>


            </div>
        </div>-->
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


<?php if(\Yii::$app->session->getFlash('login')){

    switch(\Yii::$app->session->getFlash('login'))
    {
        case 's-vkontakte':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_success_vk');  ga('send', 'event', 'успешная авторизация вк', 'click', 'успешная авторизация вк', 4);";
            break;
        case 'f-vkontakte':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_fail_vk');  ga('send', 'event', 'неудачная авторизация вк', 'click', 'неудачная авторизация вк', 4);";
            break;
        case 's-facebook':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_success_fb');  ga('send', 'event', 'успешная авторизация фб', 'click', 'успешная авторизация фб', 4);";
            break;
        case 'f-facebook':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_fail_fb');  ga('send', 'event', 'неудачная авторизация фб', 'click', 'неудачная авторизация фб', 4);";
            break;
        case 's-odnoklassniki':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_success_ok');  ga('send', 'event', 'успешная авторизация ОК', 'click', 'успешная авторизация ОК', 4);";
            break;
        case 'f-odnoklassniki':
            $yandexCeli = "yaCounter32184009.reachGoal('ym_auth_fail_ok');  ga('send', 'event', 'неудачная авторизация ОК', 'click', 'неудачная авторизация ОК', 4);";
            break;
    }
    
    $this->registerJs($yandexCeli, View::POS_LOAD);
    ?>
    <!--<script type="text/javascript">
        <?= $yandexCeli; ?>
        console.log('login');
    </script>-->

<?php	} ?>


 <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter32184009 = new Ya.Metrika({
                    id:32184009,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/32184009" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-66830200-1', 'auto');
    ga('send', 'pageview');

</script>



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
