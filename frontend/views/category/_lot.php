<?php
use yii\helpers\Html;
use common\models\GeobaseCity;
use yii\helpers\Url;

?>

	<a href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">
		<div class="lot-img">
			<div class="lot-name">
				<?= $model->name;?>
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
			<div class="current-price-price">75 <span class="glyphicon glyphicon-ruble"></span></div>
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
				if(isset($model->remaining_time) and ($model->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss'))){
					echo \russ666\widgets\Countdown::widget([
					    'datetime' => $model->remaining_time,
					    'format' => '%-Dд %-Hч:%-Mм:%-Sс',
					    'events' => [
					        //'finish' => 'function(){location.reload()}',
					        'update' => 'function(event){
					        	var format = "%S %!S:секунда,секунд;";
				                if(event.offset.minutes > 0) format = "%-Mм " + format;
				                if(event.offset.hours   > 0) format = "%-Hч " + format;
				                if(event.offset.days    > 0) format = "%-Dд " + format;
				                if(event.offset.weeks   > 0) format = "%-Dд %-Hч:%-Mм:%-Sс";
				                if(event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
				                    format = "<em>%-S секунд осталось...</em>";
				                }
				                $(this).html(event.strftime(format));
					        }',
					    ],
					]);
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
<?php // Html::a(Html::encode($model->name), ['view', 'slug' => $model->slug]);?>
<?php




/**
* 'update' => 'function(event){
	        	var format = "%-Sс";
                if(event.offset.minutes > 0) format = "%-Mм " + format;
                if(event.offset.hours   > 0) format = "%-Hч " + format;
                if(event.offset.days    > 0) format = "%-dд " + format;

                if(event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
                    format = "<em>%-S second left...</em>";
                }

                $(this).html(event.strftime(format));
	        }',
*/ 
?>


