<?php
use yii\helpers\Html;
use common\models\GeobaseCity;
use yii\helpers\Url;

?>

	<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">
		<div class="lot-img-noactive">
			<div class="lot-name-noactive">
				<?= $model->name;?>
			</div>
			<?php 
			if(isset($model->image) and is_file(".".$model->image)){
				$path = \Yii::$app->thumbler->resize(substr($model->image, 1),380,230);
				echo Html::img(Url::to(Yii::getAlias('@thumbsPath/').$path, true));
			}
			?>
		</div>
	</a>
	<div class="lot-footer">

		<div class="winner"><span class="glyphicon glyphicon-star"></span> Артем Киселев за 3 500 <span class="glyphicon glyphicon-ruble"></span></div>

		
		<div class="real-price">
			<div class="real-price-text">Реальная цена курса: <?= Yii::$app->formatter->asDecimal($model->price).' <span class="glyphicon glyphicon-ruble"></span>';
				?></div>
		</div>
		<div class="lot-torg">
			<div class="lot-button-wrapper">
				<a class="lot-button" href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">Сделать ставку&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-menu-right"></span> </a>
			</div>
		</div>
		<div class="lot-hr"></div>
		<div class="lot-city">
			<span class="glyphicon glyphicon-map-marker"></span>
			<?php
			$city = GeobaseCity::findOne($model->city_id);
			echo($city["name"]);
			?>
		</div>
	</div>



