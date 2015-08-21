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
	    	<?= $form->field($model, 'short_name', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Введите краткое имя лота. Будет вводиться на главной и на странице категории."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		 <div class="col-xs-6">
	    	<?= $form->field($model, 'slug', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"ЧПУ (урл лота), формируется автоматически от заданого краткого имени. Или можете отредактировать сами. Это поле должно быть уникальным."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-xs-12">
	    	<?= $form->field($model, 'name', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Введите краткое имя лота. Будет вводиться на главной и на странице категории."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		
		
		<div class="col-xs-6">
	   		<?= $form->field($model, 'speaker', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Спикер - имя человек который проводит трейнинг."]])->textInput(['maxlength' => true]) ?>
	    </div>
		<div class="col-xs-6">
	    	<?= $form->field($model, 'date', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Числа проведения трейнинга."]])->textInput(['maxlength' => true]) ?>
	    </div>
	    
		
		<div class="col-xs-6">
	    	<?php // $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
	    	<?= $form->field($model, 'site', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Url трейнинга. Источник подробной информации"]])->widget(\yii\widgets\MaskedInput::classname(), [
			    'clientOptions' => [
			        'alias' =>  'url',
			    ],
			]) ?>
		</div>
		
		<div class="col-xs-6">
	    	<?= $form->field($model, 'phone', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Номер телефона или номера, что бы задать номера используйте между ними тег <br>, что бы разделить их. Например: +7 (111) 111-1111<br>+7 (111) 111-1111."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-xs-12">
	    	<?= $form->field($model, 'address', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Адрес проведения трейнинга. Нужно указать для автоматического заполнения коорденат."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		
	    
	    <div class="col-xs-2">
			<?= $form->field($model, 'public', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Публиковать?  Если Выкл, то лот не будет выведен на сайте."]])->widget(SwitchBox::className(),[
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
	    	<?= $form->field($model, 'price', [
				'labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Реальная цена проведения трейнинга. Валюта - рубли."],
				'addon' => ['append' => ['content'=>'<i class="glyphicon glyphicon-ruble"></i>']]])->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => '9',
			    'clientOptions' => ['repeat' => 10, 'greedy' => false]
			]) ?>
		</div>
		<div class="col-xs-2">
			
	    	<?php /* $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => ['+7 (999) 999-9999'] // 'mask' => ['99-999-9999', '999-999-9999']
			])*/ ?>
		</div>
		<div class="col-xs-3">	
			<?=  $form->field($model, 'remaining_time', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выберите дату и время, до какого времени будут идти торги за лот. Если указаное время уже прошло, значить лот уже был отыгран."]])->widget(DateTimePicker::className(), [
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
	    	<?= $form->field($model, 'coordinates', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Координаты - точный адрсе проведения трейнинга. Формируются автоматически  Пример: 45.02485800, 39.11931300."]])->widget(\yii\widgets\MaskedInput::classname(), [
			    'mask' => ['99.99999999, 99.99999999'] // 'mask' => ['99-999-9999', '999-999-9999']
			]) ?>
		</div>
		<div class="clearfix"></div>

		
		<div class="col-xs-6">
		    <?= $form->field($model, 'city_id', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите город (города) в которых проводятся трейнинги."]])->widget(Select2::classname(), [
			    //'data' => array_merge(["" => ""], $data),
			    'data' => $cities,
			    'language' => 'ru',
				'options' => ['placeholder' => 'Город ...'],
			    'pluginOptions' => [
			        'allowClear' => true
			    ],
			]); ?>
		</div>
		
		<div class="col-xs-6">
		    <?= $form->field($model, 'subjects', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите тему (темы) до которых относится трейнинги."]])->widget(Select2::classname(), [
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
		    <?= $form->field($model, 'branchs', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите отрасль (отрасли) до которых относится трейнинг."]])->widget(Select2::classname(), [
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
		    <?= $form->field($model, 'categories', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите категорию (категории) к которым относится трейнинг."]])->widget(Select2::classname(), [
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
	    	<?=  $form->field($model, 'image', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите изображение, будет показана на главной или на страницах категорий. Так же может подгружать новые изображения."]])->widget(KCFinderInputWidget::className(), [
			    //'multiple' => true,

			]); ?>
		</div>
		
		<div class="col-xs-10">
	    	<?php // $form->field($model, 'image')->textInput(['maxlength' => true]) ?>
	    	<?php echo $form->field($model, 'lotImages', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Выьерите изображения. Будут показаны на странице лота в слайдере."]])->widget(KCFinderInputWidget::className(), [
			    'multiple' => true,
			]); ?>
		</div>
		
		<!--<div class="col-xs-12">
		    <?php /* $form->field($model, 'short_description')->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'basic',
		    ])*/ ?>
	    </div>-->
		<div class="col-xs-12">
		    <?= $form->field($model, 'complete_description', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Полное описание трейнинга. Выводится на странице лота."]])->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'full',
		    ]) ?>
	    </div>
		<div class="col-xs-12">
		    <?= $form->field($model, 'condition', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Условия проведения лота. Выводтся на странице лота."]])->widget(CKEditor::className(), [
		        'options' => ['rows' => 6],
		        'preset' => 'full',
		    ]) ?>
		</div>
	    
		<div class="col-xs-12">
	    	<?= $form->field($model, 'meta_description', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Meta description - информация для поисковиков."]])->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xs-12">
	    	<?= $form->field($model, 'meta_keywords', ['labelOptions'=>["data-toggle"=>"tooltip", "title"=>"Meta keywords - информация для поисковиков."]])->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-xs-12">
		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ["data-toggle"=>"tooltip", "title"=>"Сохранить данные.", 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>
		</div>

	    <?php ActiveForm::end(); ?>


	</div>
	
</div>


<?= $formRate; ?>

<?php
$js = <<< JS

$("#lot-short_name").syncTranslit({destination: "lot-slug"});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
JS;
$this->registerJs($js,  $this::POS_READY);
?>	


