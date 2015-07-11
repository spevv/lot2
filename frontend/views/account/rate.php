<?php
use yii\helpers\Html;
use yii\widgets\ListView;
/* @var $this yii\web\View */
$this->title = 'Лоты в которых делал ставки';
?>


<div class="row account-row">
	<div class="col-xs-3">
		<?= $menu; ?>
	</div>
	<div class="col-xs-9">
		<h1>
			<?= $this->title; ?>	
		</h1>
		
		<?=  ListView::widget([
		        'dataProvider' => $dataProvider,
		        'itemOptions' => ['class' => 'lot item'],
		        'itemView' => '_lot',
		        
		        /*'id' => 'my-listview-id',
			    'layout' => "<div class=\"items\">{items}</div>\n{pager}",
			    'pager' => [
			        'class' => InfiniteScrollPager::className(),
			        'widgetId' => 'my-listview-id',
			        'itemsCssClass' => 'items',
			        'contentLoadedCallback' => 'afterAjaxListViewUpdate',
			        'nextPageLabel' => 'Показать еще',
			        'linkOptions' => [
			            'class' => 'icon-more',
			        ],
			        'pluginOptions' => [
			            'loading' => [
			                'msgText' => "<em>Загрузка...</em>",
			                'finishedMsg' => "<em>Все елементы загружены</em>",
			            ],
			            'behavior' => InfiniteScrollPager::BEHAVIOR_TWITTER,
			        ],
			    ],*/
		        
		        
		    ]) ?>
		
	</div>
	
</div>