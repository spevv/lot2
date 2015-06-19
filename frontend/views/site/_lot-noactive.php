<?php
use yii\helpers\Html;
use common\models\GeobaseCity;
use yii\helpers\Url;

use common\models\Rate;
use frontend\models\UserSocial;
use common\models\LotRateStatistic;
?>

	<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">
		<div class="lot-img-noactive">
			<div class="lot-name-noactive">
				<?= $model->short_name;?>
			</div>
			<?php 
			if(isset($model->image) and is_file('.'.$model->image)){
				$path = \Yii::$app->thumbler->resize(substr($model->image, 1),380,230);
				echo Html::img(Url::to(Yii::getAlias('@thumbsPath/').$path, true));
			}
			?>
		</div>
	</a>
	<div class="lot-footer">

		<div class="winner">
			<?php 
			$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$model->id])->orderBy('id desc')->one();
		    	$temp = 0;
		    	if($lotRateStatistic){
					if($lotRateStatistic->status)
					{
						$rate = Rate::find()->where(['lot_id'=>$model->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->one();
		    			$temp = 1;
					}
				}
				if(!$temp){
					$rate = Rate::find()->where(['lot_id'=>$model->id])->andWhere(['refusal'=>0])->orderBy('price desc')->one();
				}
			
			
			//$rate = Rate::find()->where(['lot_id'=>$model->id])->orderBy('price desc')->one(); ?>
			<?php if($rate): ?>
				<?php  
				$user = UserSocial::findOne(['user_id'=>$rate->user2_id]);
				if($user): ?>
					<span class="glyphicon glyphicon-star"></span> 
					<?=  $user['name'] ?> за
					<?= Yii::$app->formatter->asDecimal($rate->price,0);?>
					<span class="glyphicon glyphicon-ruble"></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		
		<div class="real-price">
			<div class="real-price-text">Реальная цена курса: <?= Yii::$app->formatter->asDecimal($model->price).' <span class="glyphicon glyphicon-ruble"></span>';
				?></div>
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



