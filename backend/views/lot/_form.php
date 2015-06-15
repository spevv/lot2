<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;

//use dosamigos\datetimepicker\DateTimePicker;
use kartik\datetime\DateTimePicker;
use iutbay\yii2kcfinder\KCFinderInputWidget;
use dosamigos\switchinput\SwitchBox;
use backend\models\CKEditor;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\GeobaseRegion;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\Branch;
use common\models\Category;

use iutbay\yii2kcfinder\KCFinder;
/* @var $this yii\web\View */
/* @var $model backend\models\Lot */
/* @var $form yii\widgets\ActiveForm */


?>
<div class="row">
	<div class="lot-form">

	    <?php $form = ActiveForm::begin(); ?>
	    <div class="col-xs-6">
	    	<?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xs-12">
	    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		</div>
		
		
		
		<div class="col-xs-6">
	   		<?= $form->field($model, 'speaker')->textInput(['maxlength' => true]) ?>
	    </div>
		<div class="col-xs-6">
	    	<?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
	    </div>
	    
		
		<div class="col-xs-6">
	    	<?php // $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
	    	<?= $form->field($model, 'site')->widget(\yii\widgets\MaskedInput::classname(), [
			    'clientOptions' => [
			        'alias' =>  'url',
			    ],
			]) ?>
		</div>
		
		<div class="col-xs-6">
	    	<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
		</div>
	    
	    <div class="col-xs-2">
			<?= $form->field($model, 'public')->widget(SwitchBox::className(),[
			    'options' => [
			        'label' => false
			    ],
			    'clientOptions' => [
			        'size' => 'small',
			        'onColor' => 'success',
			        'offColor' => 'danger',
			        'onText' => 'Вкл',
			        'offText' => 'Выкл'
			    ]
			]);?>
		</div>

		<div class="col-xs-2">
	    	<?= $form->field($model, 'price', [ 'addon' => ['append' => ['content'=>'<i class="glyphicon glyphicon-ruble"></i>']]])->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => '9',
			    'clientOptions' => ['repeat' => 10, 'greedy' => false]
			]) ?>
		</div>
		<div class="col-xs-2">
	    	<?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => ['+7 (999) 999-9999'] // 'mask' => ['99-999-9999', '999-999-9999']
			]) ?>
		</div>
		<div class="col-xs-3">	
			<?=  $form->field($model, 'remaining_time')->widget(DateTimePicker::className(), [
			    //'language' => 'ru',
			    //'size' => 'ms',
			    'options' => ['placeholder' => 'Выберите время...'],
			    
			    //'pickButtonIcon' => 'glyphicon glyphicon-time',
			    //'inline' => false,
			    'pluginOptions' => [
			        //'format' => 'd-M-Y g:i A',
			        //'startDate' => '01-Mar-2014 12:00 AM',
			        'todayHighlight' => true,
			        'todayBtn' => true,
			        'autoclose' => true,
			    ]
			]); ?>
		</div>
		
		<div class="col-xs-3">
	    	<?php // $form->field($model, 'coordinates')->textInput(['maxlength' => true]) ?>
	    	<?= $form->field($model, 'coordinates')->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => ['99.99999999, 99.99999999'] // 'mask' => ['99-999-9999', '999-999-9999']
			]) ?>
		</div>
		<div class="clearfix"></div>

		
		<div class="col-xs-6">
		    <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
			    //'data' => array_merge(["" => ""], $data),
			    'data' => ArrayHelper::map(GeobaseCity::find()->orderBy('name')->all(), 'id', 'name'),
			    'language' => 'ru',
				'options' => ['placeholder' => 'Город ...'],
			    'pluginOptions' => [
			        'allowClear' => true
			    ],
			]); ?>
		</div>
		
		<div class="col-xs-6">
		    <?= $form->field($model, 'subjects')->widget(Select2::classname(), [
			    //'data' => array_merge(["" => ""], $data),
			    'data' => ArrayHelper::map(Subject::find()->all(), 'id', 'name'),
			    'language' => 'ru',
				'options' => ['placeholder' => 'Выберите тему ...',  'multiple' => true],
			    'pluginOptions' => [
			        'allowClear' => true
			    ],
			]); ?>
		</div>
		
		<div class="col-xs-6  ">
		    <?= $form->field($model, 'branchs')->widget(Select2::classname(), [
			    //'data' => array_merge(["" => ""], $data),
			    'data' => ArrayHelper::map(Branch::find()->all(), 'id', 'name'),
			    'language' => 'ru',
				'options' => ['placeholder' => 'Выберите отрасль ...',  'multiple' => true],
			    'pluginOptions' => [
			        'allowClear' => true
			    ],
			]); ?>
		</div>
		
		<div class="col-xs-6  ">
		    <?= $form->field($model, 'categories')->widget(Select2::classname(), [
			    //'data' => array_merge(["" => ""], $data),
			    'data' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
			    'language' => 'ru',
				'options' => ['placeholder' => 'Выберите категорию ...',  'multiple' => true],
			    'pluginOptions' => [
			        'allowClear' => true
			    ],
			]); ?>
		</div>
		
		<div class="clearfix"></div>
		<div class="col-xs-2">
	    	<?php  // $form->field($model, 'image')->textInput(['maxlength' => true]) ?>
	    	<?=  $form->field($model, 'image')->widget(KCFinderInputWidget::className(), [
			    //'multiple' => true,

			]); ?>
		</div>
		
		<div class="col-xs-10">
	    	<?php // $form->field($model, 'image')->textInput(['maxlength' => true]) ?>
	    	<?php echo $form->field($model, 'lotImages')->widget(KCFinderInputWidget::className(), [
			    'multiple' => true,
			]); ?>
		</div>
		
		<div class="col-xs-12">
		    <?= $form->field($model, 'short_description')->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'basic',
		    ]) ?>
	    </div>
		<div class="col-xs-12">
		    <?= $form->field($model, 'complete_description')->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'full',
		    ]) ?>
	    </div>
		<div class="col-xs-12">
		    <?= $form->field($model, 'condition')->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'full',
		    ]) ?>
		</div>
	    
		<div class="col-xs-12">
	    	<?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xs-12">
	    	<?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-xs-12">
		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>
		</div>

	    <?php ActiveForm::end(); ?>


	</div>
</div>