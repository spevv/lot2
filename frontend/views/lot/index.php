<?php

use yii\helpers\Html;
use yii\widgets\ListView;

use yii\widgets\Pjax;

use kartik\spinner\Spinner;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\LotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Лоты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-index">

    <h1><?= Html::encode($this->title) ?></h1>
   
    <?php Pjax::begin(['id' => 'countries']) ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php  //var_dump($dataProvider); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        /*'itemView' => function ($model, $key, $index, $widget) {
            //return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
            return $this->render('_lot', ['model' => $model]);
        },*/
        //'layout' => "{sorter}\n{summary}\n{items}\n{pager}",
        'itemView' => '_lot',
        //'layout' => "{items}\n{pager}",
        
    ]) ?>
    
    
    
    <?php
	$js = "$('#lot-search').on('beforeSubmit', function(){
			$('#spinner').fadeIn();
		});";
		$this->registerJs($js);
	?>
	<?php Pjax::end(); ?>
</div>


<?php
/*
    $this->registerJs(
       "$(document).on('ready pjax:success', function(){
		   //alert('in');
		});"
    );*/
?>