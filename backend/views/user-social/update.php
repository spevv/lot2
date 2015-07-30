<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSocial */

$this->title = 'Редактирование: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Socials', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="user-social-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
