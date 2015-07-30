<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSocialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-social-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // 'id',
            'name',
            'email',
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

            //'id',
            //'client',
            //'user_id',
            //'name',
            [
                'attribute' => 'name',
                //'value'=> 'name',
                'value'=> function($model){
                    //return $model->name;
                    return Html::a( $model->name,  $model->link, ['target' => '_blank']);
                },
                'format' => 'raw',
                //'contentOptions'=>['style'=>'width: 350px;'],
            ],
            'email:email',
            // 'image:ntext',
            // 'link',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
