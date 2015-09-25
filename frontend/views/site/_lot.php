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

            <div class="lot-city">
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php
                $city = GeobaseCity::findOne($model->city_id);
                echo($city["name"]);
                ?>
            </div>

			<?php 
			if(isset($model->image) and is_file(".".$model->image)){
				$path = \Yii::$app->thumbler->resize(substr($model->image, 1),290,214);
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
					1
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
				<div class="lot-time-time timeBl-<?=$model->id?>">			
				<?php
				if(isset($model->remaining_time) and ($model->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))){
					 $countdown =  \russ666\widgets\Countdown::widget([
					    'datetime' => $model->remaining_time,
					    //'format' => '%Dдни %Hчасы:%Mмин:%Sсек', %D  %H  %M  %S
					    'format' => '%D  %H  %M ',
					    'id' => 'countdown'.$model->id,
					    'events' => [
					        'finish' => 'function(){location.reload()}',
					        'update' => 'function(event){
					        	//var format = "%-S";
					        	var format = "";
				                if(event.offset.minutes > 0){
				                	if(event.offset.minutes >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
									}
				                	 
				                }
				                else
				                {
									format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
								}
				                if(event.offset.hours > 0)
				                {
				                	if(event.offset.hours  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
									}
				                	 
				                } 
				                 else
				                {
									format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
								}
				                /*if(event.offset.days > 0)
				                {
				                	if(event.offset.days  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
									}
				                	 
				                }  */
				                if(event.offset.weeks  > 0)
				                {
				                	var days = ((event.offset.weeks*7)+event.offset.days);
				                	if(days  >= 10)
				                	{
				                		
				                		var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                } 
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										 format =  "<div class=\"wrt\"><span class=\"day\">"+days +"</span></div> "+ format ;
										 
									}
									else
									{
										var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                }
						                 else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										format =  "<div class=\"wrt\"><span class=\"day\">0"+days  +"</span></div> "+ format ;
									}
									
				                	 
				                }
				                else
				                {
									if(event.offset.days > 0)
					                {
					                	if(event.offset.days  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"day\">00</span></div> " + format;
									} 
								}
								
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours < 25 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
					                if(event.offset.hours > 0)
					                {
					                	if(event.offset.hours  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
										}
					                	 
					                } 
					                 else
					                {
										format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
									}
				                } 
				                
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes  < 60 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
				                } 
				                if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
				                    format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div>";
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
						//echo Plural::downcounter($model->remaining_time);
						echo "<span class='load-time'>" . $model->remaining_time . "</span>";
						//echo "00 00 00 00";
					}
				}
				?>
					<div class="wrapptimeBl">
	                    <div class="lot-time-time-text timeBlock1">
	                        <div>дни</div>
	                        <div>часы</div>
	                        <div>мин</div>
	                    </div>
	                    <div class="lot-time-time-text timeBlock2">
	                        <div>часы</div>
	                        <div>мин</div>
	                        <div>сек</div>
	                    </div>
	                    <div class="lot-time-time-text timeBlock3">
	                        <div>мин</div>
	                        <div>сек</div>
	                    </div>
	                    
                    </div>
				</div>
			</div>
            <div class="lot-hr"></div>
			<div class="lot-button-wrapper">
				<a class="lot-button" href="<?= Url::toRoute(['lot/view', 'slug' => $model->slug]); ?>">Сделать ставку</a>
			</div>
		</div>

	</div>


