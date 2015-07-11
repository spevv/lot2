<?php

?>


<div class="col-xs-3">
	<div class="user-img"> <img class="img-circle" src="<?= $model->userSocial['image']  ?>" alt="<?= $model->userSocial['name']  ?>" /> </div>
	<div class="user-name"><?= $model->userSocial['name'] ?></div>
</div>
<div class="col-xs-9">
	<div class="user-comment"><?= $model['text'] ?></div>
</div>