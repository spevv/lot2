<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Lot */

$this->title = 'Создание лота';
$this->params['breadcrumbs'][] = ['label' => 'Лоты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'formRate' => $formRate,
    ]) ?>

</div>
