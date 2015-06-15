<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GeobaseRegion */

$this->title = 'Create Geobase Region';
$this->params['breadcrumbs'][] = ['label' => 'Geobase Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geobase-region-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
