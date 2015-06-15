<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RateWinner */

$this->title = 'Update Rate Winner: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rate Winners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rate-winner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
