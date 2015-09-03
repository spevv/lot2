<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;

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
                        </div>
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="article-right">
                        <?php if(!$contactForm):?>
                        <div class="category-list">
                            <?php
                            echo Nav::widget([
                                'items' => $articles,
                                'options' => ['class' =>'nav nav-pills nav-stacked'],
                            ]);
                        ?>
                        </div>
                        <?php else: ?>
                        <?= $contactForm; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
