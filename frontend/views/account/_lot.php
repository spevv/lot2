<?php
use yii\helpers\Html;
use yii\helpers\Url;
//var_dump($rates);
?>

<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->lot['slug']]); ?>" target="_blank">
	<?= $model->lot['short_name'];?>	
</a>