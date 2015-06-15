<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['options' => ['id'=>'lot-left', 'timeout'=>false, 'enablePushState' => false], 'enablePushState' => false]) ?> 
<div class="lot-left">
				<div class="lot-date">
					<span class="glyphicon glyphicon-calendar"></span>
					<?= $model['date']; ?>
				</div>
				<div class="lot-view-hr"></div>
				<div class="lot-remaining_time">
					<div class="lot-remaining_time-header">До  окончания торгов</div> 
					<div class="lot-remaining_time-text">
					<?php
						if(isset($model['remaining_time']) and ($model['remaining_time'] > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss'))){
							echo \russ666\widgets\Countdown::widget([
							    'datetime' => $model['remaining_time'],
							    'format' => '%Dд %Hч:%Mм:%Sс',
							    'events' => [
							        //'finish' => 'function(){location.reload()}',
							        'update' => 'function(event){
							        	var format = "%-Sс";
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
				<div class="lot-view-hr"></div>
				<div class="lot-prices">
					<div class="current-price">
						<div class="current-price-text">Текущая цена лота: </div>
						<div class="current-price-price"><?= Yii::$app->formatter->asDecimal($currentPrice,0);?> <span class="glyphicon glyphicon-ruble"></span></div>
					</div>
					
					<div class="real-price">
						<div class="real-price-text">Реальная цена курса:</div>
						<div class="real-price-price">
							<?= Yii::$app->formatter->asDecimal($model['price'],0).' <span class="glyphicon glyphicon-ruble"></span>';
							?>
						</div>
					</div>
				</div>
				<div class="lot-view-hr"></div>
				
				<?php 
					$form = ActiveForm::begin([
					   'action' => ['/lot/rate'],
				        'method' => 'post',
				        'options' => ['data-pjax' => 'true', 'id'=>'form-lot-left'],
				        //'id' => 'form-lot-rate', 
				        'formConfig' => [
				        	'showLabels'=> false,
				        ],
					]);
				?>
					<?php echo $form->field($rate, 'price')->hiddenInput();  ?> 
					<?php echo $form->field($rate, 'lot_id')->hiddenInput();  ?> 
					<?php 
					$auth = 1;
					if(Yii::$app->user->isGuest){
						$auth = 0;
					} ?>
					<?= Html::submitButton('Сделать ставку <span class="lot-but-price">'.Yii::$app->formatter->asDecimal($rate->price,0).' <span class="glyphicon glyphicon-ruble"></span></span>', ['class' => 'lot-button', 'data-auth'=>$auth]) ?>
				<?php ActiveForm::end(); ?>

				<div class="lot-rate">
					<div class="lot-count-rate"><?=$count;?></div>
					<?php if(isset($rates[0])): ?>
						<?php if($count<5): ?>
							<div class="lot-rate-table">
								<?php foreach($rates as $value): ?>
									<div class="lot-rate-table-row">
										<div class="lot-rate-date"><?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?></div>
										<div class="lot-rate-name"><?= $value->user2_id; ?></div>
										<div class="lot-rate-price"><?= Yii::$app->formatter->asDecimal($value->price,0); ?> <span class="glyphicon glyphicon-ruble"></span></div>
									</div>
								<?php endforeach;  ?>
							</div>
						<?php else: ?>
							
							<div class="lot-rate-table">
								<?php $i=0; foreach($rates as $value): $i++; ?>
									<?php if($i<=5): ?>
										<div class="lot-rate-table-row">
											<div class="lot-rate-date"><?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?></div>
											<div class="lot-rate-name"><?= $value->user2_id; ?></div>
											<div class="lot-rate-price"><?= Yii::$app->formatter->asDecimal($value->price,0); ?> <span class="glyphicon glyphicon-ruble"></span></div>
										</div>
									<?php endif; ?>
								<?php endforeach;  ?>
							</div>
							
							<div class="lot-rate-table collapse" id="collapseRate">
								<?php  $i=0; foreach($rates as $value): $i++; ?>
									<?php if($i>5): ?>
										<div class="lot-rate-table-row">
											<div class="lot-rate-date"><?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?></div>
											<div class="lot-rate-name"><?= $value->user2_id; ?></div>
											<div class="lot-rate-price"><?= Yii::$app->formatter->asDecimal($value->price, 0); ?> <span class="glyphicon glyphicon-ruble"></span></div>
										</div>
									<?php endif; ?>
								<?php endforeach;  ?>
							</div>
							<div class="lot-rate-more"  data-toggle="collapse" href="#collapseRate" aria-expanded="false" aria-controls="collapseRate"> <span>Показать больше</span></div>
						<?php endif; ?>	
					<?php endif; ?>
					
				</div>
			
<?php
$modelId = $model['id'];
$identity = Yii::$app->getUser()->getIdentity();
if (isset($identity->profile)) {
	$service = $identity->profile['service'];	
}
else{
	$service = '';
}
$js = <<< JS

	obj = {
		rateVar:  $rate->id,
		modelId: $modelId,
		service: '$service',
		social: $social,
		refresh: function() {
		    getRateInfo(this.modelId, this.rateVar);
		}
	}
	
	ReturnAlert = {
		lotSuccess: function(){
			$("#form-lot-left").submit();
			swal({
				title: "Поздравляем!",
				text: "Вы успешно сделали ставку.",
				type: "success",
				timer: 3000,
				confirmButtonColor: "#2A8FBD",
			    confirmButtonText: "Сделать принята",
			});
		},
		
		lotError: function(){
			swal({
				title: "Ставка не принята!",
				text: "Чтобы все получилось, разместите пост в соцсети.",
				type: "error",
				timer: 3000,
				confirmButtonColor: "#2A8FBD",
			    confirmButtonText: "Закрыть",
			});
		}
	}
	

	setTimeout(	function(){ obj.refresh(); }, 5000);
	
	function getRateInfo(lotId, rate)
	{
	    var ajaxRequest = $.ajax({
	        type: "post",
	        dataType: 'json',
	        url: '/lot/get-rate-info',
	        data: 'lot_id='+lotId
	    })
		  .done(function(msg) {
		  	//console.log(msg.rate+' != '+rate);
		    if(msg.rate != rate)
		    {
				//console.log('pjax.reload');
				$.pjax({container: '#lot-left', timeout: 0, scrollTo: false});
			}
			else
			{
				//console.log('else');
				setTimeout(	function(){ obj.refresh(); }, 5000);
			}
		    
		  })
		  .fail(function() {
		    console.log( "error" );
		  });
	}
	
	
	
	
	
	$( ".lot-button" ).on( "click", function(event) {
		event.preventDefault();
		if($(this).data('auth')==0){
			$('#modal-auth').modal();
			event.preventDefault();
		}
		else
		{
			swal({
		    title: "Получите доступ к торгам",
		    text: "По правилам аукциона нужно рассказать о лоте в соцсети, что бы сделать ставку.",
		    type: "warning",
		    showCancelButton: true,
		    confirmButtonColor: "#2A8FBD",
		    confirmButtonText: "Сделать ставку",
		    cancelButtonText: "Отмена",
		    closeOnConfirm: false,
		    closeOnCancel: true
		    },
		    function (isConfirm) {
		        if (isConfirm) {
		        	if(obj.service){
		        		console.log(obj.service);
		        		switch(obj.service){
							case 'facebook':
								ShareSocial.fb(obj.social.fb);
								ShareSocial.wiretapping(ReturnAlert);
								break;
								
							case 'vkontakte':
								ShareSocial.vk(obj.social.vk);
								ShareSocial.wiretapping(ReturnAlert);
								break;
								
							case 'odnoklassniki':
								ShareSocial.ok(obj.social.ok.url);
								ShareSocial.wiretapping(ReturnAlert);
								break;
						}
					}
		        }
		    });	
		}
	});
	

	$('#collapseRate').on('hidden.bs.collapse', function () {
   		$('.lot-rate-more span').html('Показать больше');
	})

	$('#collapseRate').on('shown.bs.collapse', function () {
	  $('.lot-rate-more span').html('Свернуть');
	})	
JS;
$this->registerJs($js,  $this::POS_READY);
?>		
			</div>	
<?php Pjax::end(); ?>

<?php
$js = <<< JS

	ShareSocial.vkInit(obj.social.vk);
	ShareSocial.fbInit(obj.social.fb);

JS;
$this->registerJs($js,  $this::POS_READY);
?>	





