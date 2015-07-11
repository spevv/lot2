<?php
use yii\helpers\Html;
use yii\helpers\Url;
//var_dump($model->rate->lot->short_name);
?>

<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->rate->lot['slug']]); ?>" target="_blank">
	<?= $model->rate->lot['short_name'];?>	
</a>