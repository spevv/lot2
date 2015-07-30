<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Follower */

$this->title = 'Редактирование:' . ' ' . $model->mail;
$this->params['breadcrumbs'][] = ['label' => 'Подписчики', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->mail;
?>
<div class="follower-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
