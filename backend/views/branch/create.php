<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Branch */

$this->title = 'Создание отрасли';
$this->params['breadcrumbs'][] = ['label' => 'Отрасли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
