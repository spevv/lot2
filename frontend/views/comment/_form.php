<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
//use backend\models\CKEditor;
use dosamigos\ckeditor\CKEditor;
?>

<?php $form = ActiveForm::begin([
	'method' => 'post',
	'options' => ['data-pjax' => 'true', 'id'=>'form-comment'],
	'formConfig' => [
		'showLabels'=> false,
	],
]); ?>
    <?php // $form->field($model, 'text') ?>
    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
