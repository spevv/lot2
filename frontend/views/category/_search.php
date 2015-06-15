<?php

//use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use kartik\form\ActiveForm;
use kartik\spinner\Spinner;
/* @var $this yii\web\View */
/* @var $model frontend\models\LotSearch */
/* @var $form yii\widgets\ActiveForm */
use yii\helpers\ArrayHelper;

use kartik\helpers\Html;
use dosamigos\multiselect\MultiSelect;

?>

<?php 
if(!empty($region) or !empty($subjects) or !empty($branchs)):
?>

<div class="lot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view', 'category' => $modelUrl->slug],
        'method' => 'post',
        'options' => ['data-pjax' => true, 'id'=>'lot-search'],
        'id' => 'lot-search', 
        'formConfig' => [
        	'showLabels'=> false,
        ],
    ]); ?>
	<div class="block-multi-city">
	    <div class="title-multi-city-icon"></div> 
	    <div class="title-multi-city">Города</div> 
	    <?= $form->field($model, 'city_id')->widget(MultiSelect::classname(), [
				'id'=>"multi_city_id",
			    "options" => ['multiple'=>"multiple"], // for the actual multiselect
			    'data' => $region, // data as array
			    "clientOptions" => [
		            "includeSelectAllOption" => true,
		            'numberDisplayed' => 1,
		            'buttonClass' =>  'btn btn-link',
	    			'includeSelectAllOption' =>  true,
	    			'allSelectedText' =>  'Все',
				    'selectAllText' =>  'Выбрать все',
				    'nonSelectedText' =>  'Ничего не выбрано',
				    'nSelectedText' => 'города',
				    'updateButtonText' => true,
		        ], 
			])
		 ?>
	</div>
	
	<div class="block-multi-subjects">
		<div class="title-multi-subjects-icon"></div> 
		<div class="title-multi-subjects">Темы</div> 
	    <?= $form->field($model, 'subjects')->widget(MultiSelect::classname(), [
				'id'=>"multi_subjects",
			    "options" => ['multiple'=>"multiple"], // for the actual multiselect
			    'data' => $subjects, // data as array
			    "clientOptions" => [
		            "includeSelectAllOption" => true,
		            'numberDisplayed' => 1,
		            'buttonClass' =>  'btn btn-link',
	    			'includeSelectAllOption' =>  true,
	    			'allSelectedText' =>  'Все',
				    'selectAllText' =>  'Выбрать все',
				    'nonSelectedText' =>  'Ничего не выбрано',
				    'nSelectedText' => 'темы',
				    'updateButtonText' => true,
		        ],
				])
		 ?>
	</div>

	<div class="block-multi-branchs">
		<div class="title-multi-branchs-icon"></div> 
		<div class="title-multi-branchs">Отрасли</div> 
	    <?= $form->field($model, 'branchs')->widget(MultiSelect::classname(), [
				'id'=>"multi_branchs",
			    "options" => ['multiple'=>"multiple"], // for the actual multiselect
			    'data' => $branchs, // data as array
			    "clientOptions" => [
		            "includeSelectAllOption" => true,
		            'numberDisplayed' => 1,
		            'buttonClass' =>  'btn btn-link',
	    			'includeSelectAllOption' =>  true,
	    			'allSelectedText' =>  'Все',
				    'selectAllText' =>  'Выбрать все',
				    'nonSelectedText' =>  'Ничего не выбрано',
				    'nSelectedText' => 'отрасли',
				    'updateButtonText' => true,
		        ],
				])
		 ?>
	</div>
	
	<div class="block-multi-button">
	    <div class="form-group">
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
	 	$subnmae = 'Найти <span id="spinner" style="display: none; float:right; margin-left: 20px;">'.$snip.'</span>';
	        ?>
	        <?= Html::submitButton($subnmae, ['class' => 'btn btn-primary', 'id'=>'idb']) ?> 
	    </div>
	</div>

    <?php ActiveForm::end(); ?>
</div>

<?php else:  ?>
<div class="empty-block" ></div>
<?php endif;  ?>



