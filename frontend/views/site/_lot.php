<?php
use yii\helpers\Html;
use common\models\GeobaseCity;
use yii\helpers\Url;
use yii\i18n\Formatter;
use common\models\Rate;
use common\models\LotRateStatistic;
use common\models\Plural;

?>

	<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">
		<div class="lot-img">
			<div class="lot-name">
				<?= $model->short_name;?>
			</div>
			<?php 
			if(isset($model->image) and is_file(".".$model->image)){
				$path = \Yii::$app->thumbler->resize(substr($model->image, 1),420,255);
				echo Html::img(Url::to(Yii::getAlias('@thumbsPath/').$path, true));
			}
			?>
		</div>
	</a>
	<div class="lot-footer">
		
		<div class="current-price">
			<div class="current-price-text">Текущая цена лота: </div>
			<div class="current-price-price">
				<?php 
				$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$model->id])->orderBy('id desc')->one();
		    	$temp = 0;
		    	if($lotRateStatistic){
					if($lotRateStatistic->status)
					{
						$rate = Rate::find()->where(['lot_id'=>$model->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('id desc')->one();
		    			$temp = 1;
					}
				}
				if(!$temp){
					$rate = Rate::find()->where(['lot_id'=>$model->id])->andWhere(['refusal'=>0])->orderBy('id desc')->one();
				} ?>
				<?php if($rate): ?>
					<?= Yii::$app->formatter->asDecimal($rate->price,0);?>
				<?php else: ?>
					0
				<?php endif; ?>
				
				 <span class="glyphicon glyphicon-ruble"></span>
			</div>
		</div>
		
		<div class="real-price">
			<div class="real-price-text">Реальная цена курса:</div>
			<div class="real-price-price">
				<?= Yii::$app->formatter->asDecimal($model->price).' <span class="glyphicon glyphicon-ruble"></span>';
				?>
			</div>
		</div>
		<div class="lot-hr"></div>
		<div class="lot-torg">
			<div class="lot-time">
				<div class="lot-time-header">До окончания торгов:</div>
				<div class="lot-time-time">			
				<?php
				if(isset($model->remaining_time) and ($model->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))){
					 $countdown =  \russ666\widgets\Countdown::widget([
					    'datetime' => $model->remaining_time,
					    'format' => '%Dд %Hч:%Mм:%Sс',
					    'id' => 'countdown'.$model->id,
					    'events' => [
					        'finish' => 'function(){location.reload()}',
					        'update' => 'function(event){
					        	var format = "%-Sс";
				                if(event.offset.minutes > 0) format = "%-M " + Text.plural(event.offset.minutes, ["минута ", "минуты ", "минут "]);
				                if(event.offset.hours   > 0) format = "%-H " + Text.plural(event.offset.hours, ["час ", "часа ", "часов "]);
				                if(event.offset.days    > 0) format = "%-D " + Text.plural(event.offset.days, ["день ", "дня ", "дней "])  + "%-H " + Text.plural(event.offset.hours, ["час ", "часа ", "часов "]);
				                if(event.offset.weeks   > 0) format = "%-D " + Text.plural((7*event.offset.weeks)+event.offset.days, ["день ", "дня ", "дней "])  + "%-H " + Text.plural(event.offset.hours, ["час ", "часа ", "часов "]);
				                if(event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
				                    format = "<em>%-D " + Text.plural(event.offset.seconds, ["секунда", "секунды", "секунд"]) + "...</em>";
				                }
				                $(this).html(event.strftime(format));
					        }',
					    ],
					]);
					
					if(strlen($countdown) < 100)
					{
						echo $countdown;
					}
					else
					{
						echo Plural::downcounter($model->remaining_time);
					}
				}
				?>
				</div>
			</div>
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


