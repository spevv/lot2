<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RateWinner */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rate Winners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-winner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pay',
            'status',
           /* [
            	'attribute' => 'status',
			    'value'=> function($model){
			    	echo($model->status);
			    	/*if($model->status == 1)
			    	{
						echo "Оплачен";
					}
			    	else{
						echo "Не оплачен";
					}*/
			   /* },
            ],*/
            
            //'rate_id',
            'comment',
        ],
    ]) ?>

</div>
