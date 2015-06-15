<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Вход';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

<?php
    if (Yii::$app->getSession()->hasFlash('error')) {
        echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
    }
?>

	
    <div class="row">
        <div class="col-xs-12">
            
			<p class="lead">Войдите через соцсеть:</p>
			<?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>
            
        </div>
    </div>

</div>
