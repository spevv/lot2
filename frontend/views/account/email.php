<?php

/* @var $this yii\web\View */
$this->title = 'Настройка уведомлений';
?>


<div class="row account-row">
	<div class="col-xs-3">
		<?= $menu; ?>
	</div>
	<div class="col-xs-9">
		<h1>
			<?= $this->title; ?>	
		</h1>
		
		<p>
			Уведомлять меня если:
		</p>
		<?= $emailForm; ?>
	</div>
	
</div>