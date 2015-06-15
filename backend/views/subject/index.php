<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;
use kartik\spinner\Spinner;
use yii2mod\alert\Alert;
use dosamigos\editable\Editable;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тематика';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form',[
        'model' => $model,
    ]) ?>

	<?php Pjax::begin(['id' => 'subject']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'name',
            [
			    'class' => \dosamigos\grid\EditableColumn::className(),
			    'attribute' => 'name',
			    'url' => ['editable'],
			    'type' => 'text',
			    
			    'editableOptions' => [
			        'mode' => 'inline',
			        //'clientOptions'=>['class'=>'wwwwwww'],
			        //'mode' => 'pop',
			    ]
			],
            ['class' => 'yii\grid\ActionColumn', 
	            'template' => '{update} {delete}',
            	'contentOptions'=>['style'=>'width: 50px;'],
            	'buttons' => [
		            'delete' => function ($url, $model, $key) {
		            	return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['subject/ajax-delete', 'id'=> $key], [
						        'class' => 'deleteSubject',
						    ]
						);
				    },
				],
	        ],
        ],
    ]); ?>
	<?php
	$js = "$('#subject-form').on('beforeSubmit', function(){
			$('.subject-form-submit').width('110px');
			$('#spinner').fadeIn();
		});
		
		$('.deleteSubject').click(function(event) {
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
				deleteAjax(delId, '#subject'); 
			});
			
			
			
			
		});";
		$this->registerJs($js, $this::POS_READY);
	?>
	<?php Pjax::end() ?>
</div>
<?php  Alert::widget(); // для js ?> 
