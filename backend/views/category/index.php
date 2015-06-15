<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'description:ntext',
            //'meta_description',
            //'meta_keyword',
            // 'public',

            ['class' => 'yii\grid\ActionColumn', 
            	'template' => '{update} {delete}',
            	'contentOptions'=>['style'=>'width: 50px;'],
            	/*'buttons' => [
		            'delete' => function ($url, $model, $key) {
		            	return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['branch/ajax-delete', 'id'=> $key], [
						        'class' => 'deleteBranch',
						    ]
						);
				    },
				],*/
            ],
        ],
    ]); ?>

</div>
