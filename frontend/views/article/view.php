<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->name;
if(isset($model->meta_description)){
	$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($model->meta_description)], 'description');
}
if(isset($model->meta_keyword)){
	$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($model->meta_keyword)]); 
}


?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= $model->description; ?>

</div>
