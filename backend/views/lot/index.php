<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\Branch;
use yii\i18n\Formatter;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Лоты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=> function($model){
        	if(!$model->public){
				return ['class' => 'danger'];
			}
        	if($model->remaining_time < Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss')){
				return ['class' => 'warning'];
			}
			
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'attribute' => 'short_name',
			    'value'=> 'short_name',
            	'contentOptions'=>['style'=>'width: 350px;'],
            ],
            //'short_name',
            //'speaker',
           // 'date',
            //'price:currency:Цена',
           // 'remaining_time:datetime',
            [
			    'attribute' => 'subjectLots',
			    'value'=> 'subjectsToString',
            	/*'value' => function($dataProvider) {
            		$subjects = [];
		            foreach($dataProvider['subjectLots'] as $subjectstag) {
		                $subjects[] = $subjectstag->subject_id;
		            }
		            return implode(', ', $subjects); 
			    },*/
            	'filter' => Select2::widget([
            		'attribute' => 'subjectLots',
            		'model' => $searchModel,
				    'data' => ArrayHelper::map(Subject::find()->all(), 'id', 'name'),
				    'language' => 'ru',
				    'options' => ['placeholder' => 'Выберите тематику ...'],
				    'pluginOptions' => [
				        'allowClear' => true
				    ],
				]),	
            ],
            [
			    'attribute' => 'branchLots',
            	'value'=> 'branchsToString',
            	'filter' => Select2::widget([
            		'attribute' => 'branchLots',
            		'model' => $searchModel,
				    'data' => ArrayHelper::map(Branch::find()->all(), 'id', 'name'),
				    'language' => 'ru',
				    'options' => ['placeholder' => 'Выберите отрасль ...'],
				    'pluginOptions' => [
				        'allowClear' => true
				    ],
				]),	
            ],
            //'subjects2',
            //'branchs2',
            /*[ 
		      'label' => 'remaining_time',
		      'value' => $searchModel['remaining_time'],
		     ],*/
            // 'coordinates',
            // 'address',
            // 'address_text',
            // 'phone',
            // 'site',
            // 'short_description:ntext',
            // 'complete_description:ntext',
            // 'condition:ntext',
            // 'creation_time',
            // 'update_time',
            // 'public',
            // 'meta_description',
            // 'meta_keywords',
            // 'region_id',
            [
			    'attribute' => 'city_id',
            	'value' => 'city.name',
            	'filter' => Select2::widget([
            		'attribute' => 'city_id',
            		'model' => $searchModel,
				    'data' => ArrayHelper::map(GeobaseCity::find()->orderBy('name')->all(), 'id', 'name'),
				    'language' => 'ru',
				    'options' => ['placeholder' => 'Выберите город ...'],
				    'pluginOptions' => [
				        'allowClear' => true
				    ],
				]),	
            ],
            // 'image',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
