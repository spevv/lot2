<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
//use kartik\spinner\Spinner;
use nirvana\infinitescroll\InfiniteScrollPager;
use yii\bootstrap\Nav;

?>

<?php Pjax::begin(['options' => ['id'=>'main-lot', 'timeout'=>false]]) ?> 
		<?= $search ?>

		<div class="category-list-new">
			<?= Nav::widget([
				'items' => $categoryInfo,
				'options' => ['class' =>'nav nav-pills ', 'id'=>'category-list'],
			]);
			?>
		</div>
<br>
	    <!--<div class="lot-search-header">Активные лоты</div>-->
	    <div class="lot-list">
		    <?= ListView::widget([
		        'dataProvider' => $dataProvider,
		        'itemOptions' => ['class' => 'lot item'],
		        'itemView' => '_lot',
		        'id' => 'my-listview-id',
			    'layout' => "<div class=\"items\">{items}</div>\n<div class=\"mypager\">{pager}</div>",
			    'pager' => [
			        'class' => InfiniteScrollPager::className(),
			        'widgetId' => 'my-listview-id',
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
<?php
$js = <<< JS
	$('#lot-search').on('beforeSubmit', function(){
		//console.log('beforeSubmit');
		$('#spinner').fadeIn();
		var opts = {
			//top: '10px',
			
			rigth: '10px',
			lines: 11,
			length: 5,
			width: 3,
			corners: 1,
			trail: 100,
			speed : 1.25,
			radius : 4,
			color: '#fff'
		}
		var target = document.getElementById('spinner');
		var spinner = new Spinner(opts).spin()
		target.appendChild(spinner.el)
	});
	
	obj = {
		refresh: function() {
		    $.pjax({container: '#main-lot', timeout: 0, scrollTo: false});
		}
	}
	//setTimeout(	function(){ obj.refresh(); }, 1000);



    setTimeout(	function(){ getRatesInfo(); }, 60000);

	function getRatesInfo()
	{

      //  console.log($('.lot[data-key="36"]').find('.current-price-price').text());

	    var keys =  [];
        $('.lot').each(function( key, value ) {
            keys.push($(this).data('key'));
        });
        //console.log(keys);

        var ajaxRequest = $.ajax({
	        type: "post",
	        dataType: 'json',
	        url: '/site/get-rates-info',
	        data: 'lots_id='+keys
	    })
		  .done(function(msg) {

		  	if(msg.status)
		  	{
		  		//console.log(msg.rates);
		  		$.each(msg.rates, function( key, value ) {
                    //console.log(key);
                    var price = parseInt($('.lot[data-key="'+key+'"]').find('.current-price-price').text());
                    if(price != value)
                    {
                       // console.log('new');
                        $('.lot[data-key="'+key+'"]').find('.current-price-price').text(value);
                    }
                     //console.log($('.lot[data-key="'+key+'"]').find('.current-price-price').text());
                });
		  	}

		  })
		  .fail(function() {
		    console.log( "error" );
		  });

        setTimeout(	function(){ getRatesInfo(); }, 60000);

	}


JS;
$this->registerJs($js,  $this::POS_READY);
?>		
<?php Pjax::end(); ?>

