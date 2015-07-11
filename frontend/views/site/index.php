<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Pjax;
use kartik\spinner\Spinner;

use nirvana\infinitescroll\InfiniteScrollPager;


//use yii\helpers\Url;
/* @var $this yii\web\View */
//$this->title = 'ИНТЕРНЕТ-АУКЦИОН БИЗНЕС-ОБРАЗОВАНИЯ';
$this->title = $model->name;
if(isset($model->meta_description)){
	$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($model->meta_description)], 'description');
}
if(isset($model->meta_keyword)){
	$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($model->meta_keyword)]); 
}
?>


<div class="row filter-row">
	<div class="col-xs-9">	
		<?= $activeBlockLot ?>
	</div>
	<div class="col-xs-3">
		<div class="category-list">
			<?php
            echo Nav::widget([
			    'items' => $categoryInfo,
			    'options' => ['class' =>'nav nav-pills nav-stacked', 'id'=>'category-list'],
			]);
        ?>
		</div>
	</div>
</div>



<div class="lot-search-header-noactive">Сыграные лоты</div>
		   <div class="lot-list-noactive">
	
		<?= ListView::widget([
		        'dataProvider' => $dataProvider2,
		        'itemOptions' => ['class' => 'lot-noactive item'],
		        'itemView' => '_lot-noactive',
		        'id' => 'my-listview-id-noactive',
			    'layout' => "<div class=\"items\">{items}</div>\n<div class=\"mypager\">{pager}</div>",
			    'pager' => [
			        'class' => InfiniteScrollPager::className(),
			        'widgetId' => 'my-listview-id-noactive',
			        'itemsCssClass' => 'items',
			        'contentLoadedCallback' => 'afterAjaxListViewUpdate',
			        'nextPageLabel' => 'Еще лоты',
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
			    ], 
		    ]) ?>
		</div>
		














