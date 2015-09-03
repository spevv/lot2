<?php

use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'Настройка email';
?>

<div class="wrap3">
    <div class="container">
        <div class="row account-row">
            <div class="col-xs-3">
                <?= $menu; ?>
            </div>
            <div class="col-xs-9">
                <h1>
                    <?= $this->title; ?>
                </h1>
                <?= $emailForm; ?>

            </div>

        </div>
    </div>
</div>