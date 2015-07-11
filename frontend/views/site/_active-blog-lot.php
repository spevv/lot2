<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use kartik\spinner\Spinner;
use nirvana\infinitescroll\InfiniteScrollPager;

?>


<?php Pjax::begin(['options' => ['id'=>'main-lot', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?> 
		<?= $search ?>
	  
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
			console.log('refresh');
		    $.pjax({container: '#main-lot', timeout: 0, scrollTo: false});
		}
	}
	setTimeout(	function(){ obj.refresh(); }, 300);
JS;
$this->registerJs($js,  $this::POS_READY);
?>		



<?php Pjax::end(); ?>