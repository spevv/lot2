<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Админ панель',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Сайт', 'url'=>\Yii::$app->urlManagerFrontEnd->baseUrl, 'linkOptions' => ['target' => '_blank'],],
                ['label' => 'Статьи', 'url' => ['/article/index']],
                ['label' => 'Отрасли', 'url' => ['/branch/index']],
                ['label' => 'Тематики', 'url' => ['/subject/index']],
                ['label' => 'Категории', 'url' => ['/category/index']], 
                ['label' => 'Города', 'url' => ['/geobase-city/index']],
                //['label' => 'Ставки', 'url' => ['/rate/index']],
                ['label' => 'Пользователи', 'items' => [
                    ['label' => 'Победители', 'url' => ['/rate-winner/index']],
                    ['label' => 'Подписчики', 'url' => ['/follower/index']],
                    ['label' => 'Пользователи', 'url' => ['/user-social/index']],
                    ],
                ],
                ['label' => 'Комментарии', 'url' => ['/comment/index']],
                ['label' => 'Лоты', 'url' => ['/lot/index']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; EduHot.biz <?= date('Y') ?></p>
        <p class="pull-right">Разработано компанией <a href="http://awam-it.ru/" target="_blank">AWAM-IT.</a></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
