<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?= Html::a('Create Comment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            
            [
             	'attribute' => 'userSocial.name',
			    'value'=> function ($model){
			    	return Html::a($model->userSocial['name'],  Url::to($model->userSocial['link']), ['target' => '_blank']);
			    },
			    'format' => 'html',
			    'label' => 'Имя',
            ],
            [
             	'attribute' => 'lot.short_name',
			    'value'=> function ($model) {
			    	return $model->lot['short_name'];
			    },
			    'format' => 'html',
            ],
            //'public',
            [
            	'attribute' => 'public',
			    'value'=> function ($model){
			    	if($model->public){
						return 'Опубликован';
					}
					else
					{
						return 'Не опубликован';
					}
			    },
            ],
            'creation_time',
            //'text:ntext',
            //'update_time',
            // 'user2_id',
            // 'lot_id',

            //['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
