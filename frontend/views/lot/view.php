<?php

use yii\helpers\Html;


use katzz0\yandexmaps\Map;
use katzz0\yandexmaps\Point;
use katzz0\yandexmaps\objects\Placemark;
use katzz0\yandexmaps\Canvas as YandexMaps;
use yii2mod\alert\Alert;
use yii\bootstrap\Button;

use kartik\form\ActiveForm;

use yii\widgets\ListView;
use yii\widgets\Pjax;

use yii\captcha\Captcha;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\Lot */

$this->title = $model['name'];
?>
<div class="lot-view">
	<div class="row">
		<div class="col-xs-12">
			 <h1 class="lot-view-header"><?= Html::encode($this->title) ?></h1>
		</div>
		<div class="col-xs-8">
			<div class="lot-images">
				 <?php 
				    $fotorama = \metalguardian\fotorama\Fotorama::begin(
				        [
				            'options' => [
				                'loop' => true,
				                'hash' => false,
				                'autoplay' => true,
				                'minwidth'=> "800",
				                //'ratio' => 800/600,
				                'nav' => 'thumbs',
				                'allowfullscreen' => true,
				            ],
				            'spinner' => [
				                'lines' => 20,
				            ],
				            'tagName' => 'span',
				            'useHtmlData' => false,
				            'htmlOptions' => [
				                'class' => 'custom-class',
				                'id' => 'custom-id',
				            ],
				        ]
				    ); 
				    foreach($model['images'] as $key => $value){
							echo (Html::img($value));
						}
					$fotorama->end(); 
				?>
			</div>
			<div class="lot-menu-social">
				<div class="lot-menu">
					<a href="#condition">Условия</a> / 
					<a href="#description">Описание</a>
					<?php if($comments): ?> / 
						<a href="#reviews">Отзывы</a>
					<?php endif; ?>
				</div>
				<div class="lot-social"> <div>Поделиться:</div>  
					<div class="social-fb social_share" data-type="fb" data-image="<?=$share['image'];?>" data-text="<?=$share['text'];?>"></div>
					<div class="social-vk social_share" data-type="vk" data-image="<?=$share['image'];?>" data-text="<?=$share['text'];?>"></div>
					<div class="social-ok social_share" data-type="ok" data-image="<?=$share['image'];?>" data-text="<?=$share['text'];?>"></div>
					<div class="social-email " data-toggle="modal" data-target="#contactForm"></div>
				</div>

			</div>
			<div class="lot-content">
				<div class="lot-map-desc">
					<div class="lot-map">  
					<?php 
						$coordinates = explode(',', $model['coordinates']);
						?>
						
						<?= YandexMaps::widget([
						    'htmlOptions' => [
						        'style' => 'height: 260px;',
						    ],
						    'map' => new Map('yandex_map', [
						        'center' => $coordinates,
						        'zoom' => 17,
						        //'controls' => [Map::CONTROL_ZOOM],
						        'controls' => ['zoomControl', 'geolocationControl', 'typeSelector',  'fullscreenControl'],
						        'behaviors' => [Map::BEHAVIOR_DRAG],
						        'type' => "yandex#map",
						    ],
						    [
						        'objects' => [new Placemark(new Point($coordinates[0],$coordinates[1]), 
						        [
						            'balloonContentHeader' => $model['short_name'],
						            'balloonContentBody' => $model['address'],
						           // 'balloonContentFooter' => "Подвал",
						            'hintContent' => $model['short_name'],
						        ], 	
						        [
						            'draggable' => false,
						            'preset' => 'islands#dotIcon',
						            'iconColor' => '#2E9BB9',
						            'balloonContent' => $model['short_name'],
						            'events' => [
						                'dragend' => 'js:function (e) {
						                    console.log(e.get(\'target\').geometry.getCoordinates());
						                }'
						            ]
						        ])]
						    ])
						]) ?>
					</div>
					<div class="lot-lit-desc">
						<div><?=  $model['address'] ?></div>
						<div>Телефоны: <br><?=  $model['phone'] ?></div>
						<div>Сайт: <br><?= Html::a($model['site'], $model['site'], ['class'=>'']) ?></div>
					</div>
				</div>
				
				<div class="lot-condition" id="condition">
					<div class="lot-condition-header">Условия</div>
					<div class="lot-condition-text">
						<?= $model['condition']; ?>
					</div>
				</div>
				<div class="lot-view-hr"></div>
				<div class="lot-complete_description" id="description">
					<div class="lot-condition-header">Описание</div>
					<div class="lot-condition-text">
						<?= $model['complete_description']; ?>
					</div>
				</div>
				<div class="lot-view-hr"></div>
				<?php if($comments): ?>
				<div class="lot-reviews" id="reviews">
					<div class="lot-condition-header">Отзывы</div>
					<div class="lot-reviews-conternt">
						<div class="owl-carousel">
							<?php foreach($comments as $comment): ?>
						    <div class="item">
						    	<div class="row">
									<div class="col-xs-3">
										<div class="user-img"> <img class="img-circle" src="<?=  $comment->userSocial['image'] ?>" alt="<?= $comment->userSocial['name'] ?>" /> </div>
										<div class="user-name"><?= $comment->userSocial['name'] ?></div>
									</div>
									<div class="col-xs-9">
										<div class="user-comment"><?= $comment->text ?></div>
									</div>
								</div>
						    </div>
						    <?php endforeach; ?>
						</div>
						
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="col-xs-4">
			<?= $lotLeft; ?>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="contactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Расскажи друзьям о лоте</h4>
      </div>
      <div class="modal-body">
			<?= $contact ?>
      </div>
    </div>
  </div>
</div>



