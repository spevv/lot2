<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Pjax;
use kartik\spinner\Spinner;

use nirvana\infinitescroll\InfiniteScrollPager;
use yii\helpers\VarDumper;

use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'ИНТЕРНЕТ-АУКЦИОН БИЗНЕС-ОБРАЗОВАНИЯ';
?>




<div class="row filter-row">
	<div class="col-xs-9">	
	<?php Pjax::begin(['id' => 'main-lot']) ?>
	   <?php echo $this->render('_search', ['model' => $searchModel, 'region' => $region, 'subjects' => $subjects,'branchs' => $branchs,]); ?>
	   <div class="lot-search-header">Активные лоты</div>
	   <div class="lot-list">

		    <?= ListView::widget([
		        'dataProvider' => $dataProvider,
		        'itemOptions' => ['class' => 'lot item'],
		        //'layout' => "{items}",
		        //'layout' => "{items}\n{pager}",
		        'itemView' => '_lot',
		        //'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
		        
		        
		        'id' => 'my-listview-id',
			    'layout' => "<div class=\"items\">{items}</div>\n{pager}",
			    'pager' => [
			        'class' => InfiniteScrollPager::className(),
			        'widgetId' => 'my-listview-id',
			        'itemsCssClass' => 'items',
			        'contentLoadedCallback' => 'afterAjaxListViewUpdate',
			        'nextPageLabel' => 'Показать еще',
			        'linkOptions' => [
			            'class' => 'btn btn-lg btn-block',
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
		    <?php
			$js = "$('#lot-search').on('beforeSubmit', function(){
					$('#spinner').fadeIn();
				});	";
				$this->registerJs($js);

				//$this->registerJsFile('js/script.js',  ['depends' =>'dosamigos\multiselect\MultiSelectAsset']);
				//$this->registerJsFile('js/script.js',['position'=>'POS_END']);
			?>
		</div>
<?php
$js = <<< JS
	obj = {
		refresh: function() {
		    $.pjax({container: '#main-lot', timeout: 0, scrollTo: false});
		}
	}
	setTimeout(	function(){ obj.refresh(); }, 30000);
JS;
$this->registerJs($js,  $this::POS_READY);
?>		
	<?php Pjax::end(); ?>

	
	</div>
	<div class="col-xs-3">
		<div class="category-list">
			<?php
            echo Nav::widget([
			    'items' => $categoryInfo,
			    'options' => ['class' =>'nav nav-pills nav-stacked'],
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
		        //'layout' => "{items}",
		        //'layout' => "{items}\n{pager}",
		        'itemView' => '_lot-noactive',
		        //'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
		        
		        
		        'id' => 'my-listview-id-noactive',
			    'layout' => "<div class=\"items\">{items}</div>\n{pager}",
			    'pager' => [
			        'class' => InfiniteScrollPager::className(),
			        'widgetId' => 'my-listview-id-noactive',
			        'itemsCssClass' => 'items',
			        'contentLoadedCallback' => 'afterAjaxListViewUpdate',
			        'nextPageLabel' => 'Показать еще',
			        'linkOptions' => [
			            'class' => 'btn btn-lg btn-block',
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
		














