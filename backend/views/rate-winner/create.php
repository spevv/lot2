<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RateWinner */

$this->title = 'Create Rate Winner';
$this->params['breadcrumbs'][] = ['label' => 'Rate Winners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-winner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
