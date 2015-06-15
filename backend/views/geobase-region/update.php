<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GeobaseRegion */

$this->title = 'Update Geobase Region: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Geobase Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="geobase-region-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
