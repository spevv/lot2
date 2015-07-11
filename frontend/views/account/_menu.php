<?php
use yii\bootstrap\Nav;
?>


<div class="category-list">
	<?php
    echo Nav::widget([
	    'items' => $items,
	    'options' => ['class' =>'nav nav-pills nav-stacked'],
	]);
?>
</div>