<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Lot */

$this->title = 'Редактирование: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Лоты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="lot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'formRate' => $formRate,
        'cities' => $cities,
    ]) ?>

</div>
