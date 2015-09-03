<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use nirvana\infinitescroll\InfiniteScrollPager;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->name;
if(isset($model->meta_description)){
	$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($model->meta_description)], 'description');
}
if(isset($model->meta_keyword)){
	$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($model->meta_keyword)]); 
}


?>
<div class="wrap3">
    <div class="container">

        <div class="article-view">

            <div class="row">
                <div class="col-xs-12">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>

                <div class="col-xs-9">
                    <div class="article-left">
                        <div class="article-image">
                        <?php if(isset($model->image) and is_file(".".$model->image)){
                            $path = \Yii::$app->thumbler->resize(substr($model->image, 1),855,400);
                            echo Html::img(Url::to(Yii::getAlias('@thumbsPath/').$path, true));
                        }
                        ?>
                        </div>
                        <div class="article-description">
                            <?= $model->description; ?>

                             <?= ListView::widget([
                                'dataProvider' => $dataProvider,
                                'itemOptions' => ['class' => 'row comment-row'],
                                /*'itemView' => function ($model, $key, $index, $widget) {
                                    //return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                                    return $this->render('_lot', ['model' => $model]);
                                },*/
                                //'layout' => "{sorter}\n{summary}\n{items}\n{pager}",
                                'itemView' => '_comment',
                                //'layout' => "{items}\n{pager}",

                                'id' => 'articles-id',
                                'layout' => "<div class=\"items\">{items}</div>\n<div class=\"mypager\">{pager}</div>",
                                'pager' => [
                                    'class' => InfiniteScrollPager::className(),
                                    'widgetId' => 'articles-id',
                                    'itemsCssClass' => 'items',
                                    'contentLoadedCallback' => 'afterAjaxListViewUpdate',
                                    'nextPageLabel' => 'ЕЩЕ ОТЗЫВЫ ',
                                    'linkOptions' => [
                                        'class' => 'icon-more', //btn btn-lg btn-block
                                    ],
                                    'pluginOptions' => [
                                        'loading' => [
                                            'msgText' => "<em>Загрузка...</em>",
                                            'finishedMsg' => "<em>Все елементы загружены</em>",
                                        ],
                                        'behavior' => InfiniteScrollPager::BEHAVIOR_TWITTER,
                                    ],
                                ],

                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="article-right">
                        <div class="category-list">
                            <?php
                            echo Nav::widget([
                                'items' => $articles,
                                'options' => ['class' =>'nav nav-pills nav-stacked'],
                            ]);
                        ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>



