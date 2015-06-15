<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GeobaseCity */

$this->title = 'Create Geobase City';
$this->params['breadcrumbs'][] = ['label' => 'Geobase Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geobase-city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
