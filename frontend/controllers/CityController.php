<?php

namespace frontend\controllers;

use Yii;
use common\models\Category;
use yii\helpers\ArrayHelper;
use common\models\SubjectLot;
use common\models\BranchLot;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\Branch;
use common\models\CategoryLot;
use common\models\Lot;
use frontend\models\LotSearch;
class CityController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
    	Yii::$app->session->set('city', $id);
    	return $this->redirect('/',302);
    }

}
