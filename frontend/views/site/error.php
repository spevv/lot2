<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="wrap3">
    <div class="container">
        <div class="row">
            <div class="col-xs12">
                <div class="site-error">

                    <h1><?= Html::encode($this->title) ?></h1>

                    <div class="alert alert-danger">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>

                    <p>
                        Перейдите на главную страницу и попробуйте сначала.
                    </p>
                    <p>
                        Пожалуйста, напишите нам, если вы думаете, что это ошибка сайта. Спасибо.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

