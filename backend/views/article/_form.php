<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use iutbay\yii2kcfinder\KCFinderInputWidget;
use backend\models\CKEditor;
/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
    ]) ?>
    
    <?=  $form->field($model, 'image')->widget(KCFinderInputWidget::className(), []); ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keyword')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

	
	
<?php
$js = <<< JS

$("#article-name").syncTranslit({destination: "article-slug"});
	
JS;
$this->registerJs($js,  $this::POS_READY);
?>	


