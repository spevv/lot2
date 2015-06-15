<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\spinner\Spinner;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model backend\models\subject */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subject-form">

<?php
    $this->registerJs(
        '$("document").ready(function(){
            $("#new_subject").on("pjax:end", function() {
            $.pjax.reload({container:"#subject"});
        });
    });'
    );
?>
<?php 
 	$snip = Spinner::widget([
		"pluginOptions" => [
		    "top" => "50%",
		    "rigth" => "10px",
		    "lines" => 11,
		    "length" => 5,
		    "width" => 3,
		    "corners" => 1,
		    "trail" => 100,
		    "speed" => 1.25,
		    "radius" => 4,
		],
		]);
 	$subnmae = 'Создать <span id="spinner" style="display: none; float:left; margin-right: 25px;">'.$snip.'</span>'; //display: none;
?>
	<?php Pjax::begin(['id' => 'new_subject']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'id'=>'subject-form']]); ?>
    <?= $form->field($model, 'name', [
    	 
    	//'autoPlaceholder' =>true,
    	'showLabels' =>false,
    	'inputOptions' => ['placeholder' => 'Введите название', 'class'=>"form-control"],
	    
	    'addon' => [
	        'append' => [
	            'content' => Html::submitButton($model->isNewRecord ? $subnmae : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success subject-form-submit' : 'btn btn-primary']), 
	            'asButton' => true
	        ]
	    ]
	]); ?>
    <?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>
</div>
