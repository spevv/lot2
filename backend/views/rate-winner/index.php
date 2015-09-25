<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RateWinnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Победители';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-winner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?= Html::a('Create Rate Winner', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
			[
             	'attribute' => 'rate.user.name',
			    'value'=> function ($model){
			    	return Html::a($model->rate->user['name'],  Url::to($model->rate->user['link']), ['target' => '_blank']);
			    	//return $model->rate->user['name'];
			    },
			    'format' => 'html',
            ],
            [
             	'attribute' => 'rate.lot.short_name',
			    'value'=> function ($model){
			    	//return Html::a($model->rate->lot['short_name'],  Url::to(\Yii::$app->urlManagerFrontEnd->baseUrl.$model->rate->lot['slug']), ['target' => '_blank']);
			    	//return $model->rate->user['name'];
			    	return $model->rate->lot['short_name'];
			    },
			    'format' => 'html',
            ],
            'winner_time',
            //'id',
            'pay',
            [
             	'attribute' => 'comment',
			    'value'=> function ($model){
			    	if($model->comment)
			    	{
						return 'нет';
					}
					else
					{
						return 'есть';
					}
			    	
			    },
            ],
            //'rate_id',
            //'comment',
            
            //'rate.lot.short_name',
            
           

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} '],
        ],
    ]); ?>

</div>
