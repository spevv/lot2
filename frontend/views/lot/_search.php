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
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\Branch;

use kartik\helpers\Html;

use common\models\Lot;
use common\models\SubjectLot;
use common\models\BranchLot;

$lot = ArrayHelper::map(Lot::find()->all(), 'id', 'city_id');
$lot = array_unique($lot);
$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');
$region2[0] = '<strong  >Выбрать все</strong>';
$region = $region2 + $region;
//array_unshift($region, '<strong id="region">Выбрать все</strong>');

$subjectLot = ArrayHelper::map(SubjectLot::find()->all(), 'id', 'subject_id');
$subjectLot = array_unique($subjectLot);
$subjects = ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');
$subjects2[0] = '<strong >Выбрать все</strong>';
$subjects = $subjects2 + $subjects;

$branchLot = ArrayHelper::map(BranchLot::find()->all(), 'id', 'branch_id');
$branchLot = array_unique($branchLot);
$branchs = ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');
$branchs2[0] = '<strong  >Выбрать все</strong>';
$branchs = $branchs2 + $branchs;


$js = "
$('#lotsearch-city_id :checkbox[value=0]').change(function() {
    var checkboxes = $(this).closest('#lotsearch-city_id').find(':checkbox');
    if($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});

$('#lotsearch-subjects :checkbox[value=0]').change(function() {
    var checkboxes = $(this).closest('#lotsearch-subjects').find(':checkbox');
    if($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});

$('#lotsearch-branchs :checkbox[value=0]').change(function() {
    var checkboxes = $(this).closest('#lotsearch-branchs').find(':checkbox');
    if($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
}); ";
		$this->registerJs($js);


?>



<div class="lot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'options' => ['data-pjax' => true, 'id'=>'lot-search'],
        'id' => 'lot-search', 
	    //'type' => ActiveForm::TYPE_HORIZONTAL,
	    //'formConfig' => ['labelSpan' => 3, 'spanSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?php // $form->field($model, 'id') ?>
    
    <?php  //echo $form->field($model, 'region_id') ?>
    <?php echo $form->field($model, 'city_id')->multiselect($region, [
	    'height' => '225px',
	    'container' => ['class' => 'bg-white']
	]); ?>
    
    <?php echo $form->field($model, 'subjects')->multiselect($subjects); ?>
    
    <?php echo $form->field($model, 'branchs')->multiselect($branchs); ?>
    
    <?php //echo  $form->field($model, 'name') ?>

    <?php //$form->field($model, 'speaker') ?>

    <?php //$form->field($model, 'date') ?>

    <?php //$form->field($model, 'remaining_time') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'coordinates') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'address_text') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'site') ?>

    <?php // echo $form->field($model, 'short_description') ?>

    <?php // echo $form->field($model, 'complete_description') ?>

    <?php // echo $form->field($model, 'condition') ?>

    <?php // echo $form->field($model, 'creation_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'public') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'image') ?>

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

    <?php ActiveForm::end(); ?>
</div>


