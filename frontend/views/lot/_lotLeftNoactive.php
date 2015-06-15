<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="lot-left">
	<div class="lot-date">
		<span class="glyphicon glyphicon-calendar"></span>
		<?= $model['date']; ?>
	</div>
	<div class="lot-view-hr"></div>
	<div class="lot-remaining_time">
		<div class="lot-remaining_time-header">
			Аукцион завершен
		</div>
		<div class="lot-remaining_time-text">
			<?php
			if(isset($model['remaining_time'])): ?>
			<?= Yii::$app->formatter->asDatetime($model['remaining_time'], 'dd.MM.yyyy в HH:mm');?>
			<?php endif; ?>
		</div>
	</div>
	<div class="lot-view-hr">
	</div>
	<div class="lot-prices">

		<div class="lot-winner">
			<?php
			if(isset($rates[0])): ?>
			<?= $rates[0]->user2_id; ?> выиграл за <?= Yii::$app->formatter->asDecimal($currentPrice,0);?>
			<span class="glyphicon glyphicon-ruble">
			</span>
			<?php endif; ?>
		</div>

		<div class="real-price">
			<div class="real-price-text">
				Реальная цена курса:
			</div>
			<div class="real-price-price">
				<?= Yii::$app->formatter->asDecimal($model['price'],0).' <span class="glyphicon glyphicon-ruble"></span>';
				?>
			</div>
		</div>
	</div>
	<div class="lot-view-hr">
	</div>

	<div class="lot-rate">
		<div class="lot-count-rate">
			<?=$count;?>
		</div>
		<?php
		if(isset($rates[0])): ?>
		<?php
		if($count < 5): ?>
		<div class="lot-rate-table">
			<?php
			foreach($rates as $value): ?>
			<div class="lot-rate-table-row">
				<div class="lot-rate-date">
					<?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?>
				</div>
				<div class="lot-rate-name">
					<?= $value->user2_id; ?>
				</div>
				<div class="lot-rate-price">
					<?= Yii::$app->formatter->asDecimal($value->price,0); ?>
					<span class="glyphicon glyphicon-ruble">
					</span>
				</div>
			</div>
			<?php endforeach;  ?>
		</div>
		<?php
		else: ?>

		<div class="lot-rate-table">
			<?php $i = 0;
			foreach($rates as $value): $i++; ?>
			<?php
			if($i <= 5): ?>
			<div class="lot-rate-table-row">
				<div class="lot-rate-date">
					<?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?>
				</div>
				<div class="lot-rate-name">
					<?= $value->user2_id; ?>
				</div>
				<div class="lot-rate-price">
					<?= Yii::$app->formatter->asDecimal($value->price,0); ?>
					<span class="glyphicon glyphicon-ruble">
					</span>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach;  ?>
		</div>

		<div class="lot-rate-table collapse" id="collapseRate">
			<?php  $i = 0;
			foreach($rates as $value): $i++; ?>
			<?php
			if($i > 5): ?>
			<div class="lot-rate-table-row">
				<div class="lot-rate-date">
					<?= Yii::$app->formatter->asDatetime($value->time, 'dd.MM hh:mm'); ?>
				</div>
				<div class="lot-rate-name">
					<?= $value->user2_id; ?>
				</div>
				<div class="lot-rate-price">
					<?= Yii::$app->formatter->asDecimal($value->price, 0); ?>
					<span class="glyphicon glyphicon-ruble">
					</span>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach;  ?>
		</div>
		<div class="lot-rate-more"  data-toggle="collapse" href="#collapseRate" aria-expanded="false" aria-controls="collapseRate">
			<span>
				Показать больше
			</span>
		</div>
		<?php endif; ?>
		<?php endif; ?>

	</div>
</div>

