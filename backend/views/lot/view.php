<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Lot */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Лоты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model->toArray([], ['subjects','branchs']),
        'attributes' => [
            'id',
            'name',
            'speaker',
            'date',
            'remaining_time',
            'price',
            'coordinates',
            'address',
            'address_text',
            'phone',
            'site',
            'short_description:ntext',
            'complete_description:ntext',
            'condition:ntext',
            'creation_time',
            'update_time',
            'public',
            'meta_description',
            'meta_keywords',
            'region_id',
            'image',
            'subjects',
            'branchs',
        ],
    ]) ?>

</div>
