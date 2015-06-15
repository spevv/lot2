<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use kartik\spinner\Spinner;
use yii2mod\alert\Alert;
use dosamigos\editable\Editable;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отрасль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-index">

    <h1><?= Html::encode($this->title) ?></h1>
    

	
	<?= $this->render('_form',[
        'model' => $model,
    ]) ?>

	<?php Pjax::begin(['id' => 'branch']) ?>
	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel' => $searchModel,
	        'columns' => [
	           // ['class' => 'yii\grid\SerialColumn'],
	            //'name',
	             [
				    'class' => \dosamigos\grid\EditableColumn::className(),
				    'attribute' => 'name',
				    'url' => ['editable'],
				    'type' => 'text',
				    'editableOptions' => [
				        'mode' => 'inline',
				    ]
				],
	            ['class' => 'yii\grid\ActionColumn', 
	            	'template' => '{update} {delete}',
	            	'contentOptions'=>['style'=>'width: 50px;'],
	            	'buttons' => [
			            'delete' => function ($url, $model, $key) {
			            	return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['branch/ajax-delete', 'id'=> $key], [
							        'class' => 'deleteBranch',
							    ]
							);
					    },
					],
	            ],
	        ],
	    ]); ?>
	<?php
	$js = "$('#branch-form').on('beforeSubmit', function(){
			$('.branch-form-submit').width('110px');
			$('#spinner').fadeIn();
		});
		
		$('.deleteBranch').click(function(event) {
			delId = this;
			event.preventDefault();
			swal({   
				title: 'Вы уверены?',   
				text: 'Вы не сможете восстановить эту запись!',   
				type: 'warning',   
				showCancelButton: true,   
				confirmButtonColor: '#DD6B55',  
				confirmButtonText: 'Да, удалить!',   
				closeOnConfirm: false,
				cancelButtonText: 'Нет',
			}, 
			function(){   
				swal('Удалено!', 'Ваша запись успешно удалена.', 'success'); 
				//deleteBranch(delId);
				deleteAjax(delId, '#branch'); 
			});
			
			
			
			
		});";
		$this->registerJs($js, $this::POS_READY);
	?>
	<?php Pjax::end() ?>
</div>
<?php  Alert::widget(); // для js ?> 
