<?php
use yii\helpers\Html;
use common\models\GeobaseRegion;
use yii\helpers\Url;


echo Html::a(Html::encode($model->name), ['view', 'slug' => $model->slug]);
$region = GeobaseRegion::findOne($model->region_id);
echo($region["name"]);
echo(Yii::$app->formatter->asDecimal($model->price).' <span class="glyphicon glyphicon-ruble"></span>');
//echo($model->remaining_time);
echo($model->date);
//var_dump($model->categories);


if(isset($model->image) and is_file(".".$model->image)){
	$path = \Yii::$app->thumbler->resize(substr($model->image, 1),265,200);
	echo Html::img(Url::to(Yii::getAlias('@thumbsPath/').$path, true));
}

if(isset($model->remaining_time) and ($model->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss'))){
	echo \russ666\widgets\Countdown::widget([
	    'datetime' => $model->remaining_time,
	    'format' => '%Dд %Hч:%Mм:%Sс',
	    'events' => [
	        //'finish' => 'function(){location.reload()}',
	        'update' => 'function(event){
	        	var format = "%-Sс";
                if(event.offset.minutes > 0) format = "%-Mм " + format;
                if(event.offset.hours   > 0) format = "%-Hч " + format;
                if(event.offset.days    > 0) format = "%-Dд " + format;
                if(event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
                    format = "<em>%-S секунд осталось...</em>";
                }
                $(this).html(event.strftime(format));
	        }',
	    ],
	]);
}
?>


