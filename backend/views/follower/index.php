<?php

use yii\helpers\Html;
//use yii\grid\GridView;

use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FollowerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подписчики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="follower-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           // 'id',
            'mail',
            'date',
        ],
        'fontAwesome' => true,
        'dropdownOptions' => [
            'label' => 'Выгрузить все',
            'class' => 'btn btn-default'
        ],
        'batchSize' => 10,
        'target' => '_self',
        //'folder' => '@webroot/tmp', // this is default save folder on server
    ]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'mail',
            'date',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
