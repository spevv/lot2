<?php

namespace frontend\controllers;

use Yii;
use common\models\Category;
use frontend\models\CategorySearch;
use frontend\models\CategoryLotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//use common\models\Category;
use common\models\CategoryLot;
use common\models\Lot;

use yii\data\ActiveDataProvider;


use yii\helpers\ArrayHelper;
use common\models\SubjectLot;
use common\models\BranchLot;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\Branch;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param string $id
     * @return mixed
     */
   /* public function actionView($id)
    {
    	
    	$categoryLot = CategoryLot::find()
		    ->where(['category_id' => $id])
		    ->orderBy('id')
		    ->all();
		//$lotId = [];
		foreach($categoryLot as $key => $value){
			$lotId[] = $value["lot_id"];
		}
		$lots = Lot::find()
		    ->where(['id' => $lotId])
		    ->orderBy('id')
		    ->all();
		    //var_dump($lots);
		return $this->render('view', [
            'lots' => $lots,
            'model' => $this->findModel($id)
        ]);
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
   /* }*/
    public function actionView($category)
    {
    	
    	$categoryInfo = Category::find()
		    ->where(['public' => 1])
		    ->orderBy('priority')
		    ->all();
		
		foreach($categoryInfo as $key => $value){
			$items[$key]['label'] = $value['name'];
			$items[$key]['linkOptions'] =  ['class'=> $value['slug']];
			$items[$key]['url'] = ['category/view', 'category' => $value['slug']];
		}
    	//var_dump($category);
    	//die();
    	$categoryInfo = Category::find()
		    ->where(['slug' => $category])
		    ->orderBy('id')
		    ->all();
		    
		if(!isset($categoryInfo[0]['id'])){
			return $this->render('/site/error', [
	            'message' => 'Страница не найдена',
	            'name' => 'Страница не найдена',
	        ]);
		}
		$id = $categoryInfo[0]['id'];
    	$categoryLot = CategoryLot::find()
		    ->where(['category_id' => $id])
		    ->orderBy('id')
		    ->all();
		    
		if(isset($categoryLot[0]["lot_id"])){
			foreach($categoryLot as $key => $value){
				$lotId[] = $value["lot_id"];
			} 
		}
		else{
			$lotId = 0;
		}
		  
		$lots = Lot::find()
		    ->where(['id' => $lotId])
		    ->orderBy('id')
		    ->all();
		    
		
		$searchModel = new CategoryLotSearch();
		
		$dataProvider2 = $searchModel->search(null,$lotId, false);
		
		if(Yii::$app->request->isPost){
			$dataProvider = $searchModel->search(Yii::$app->request->post(), $lotId, true);
		}
		else
		{
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $lotId, true);
		}
		
		$categoryLot = ArrayHelper::map(CategoryLot::find()->where(['category_id' => $id])->all(), 'id', 'lot_id');
		$categoryLot = array_unique($categoryLot);

		$lot = ArrayHelper::map(Lot::find()->where(['id' => $categoryLot, 'public'=> 1])
			->andWhere('remaining_time>"'.Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss').'"')
			->all(), 'id', 'city_id');
		$lot = array_unique($lot);
		$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');

		$subjectLot = ArrayHelper::map(SubjectLot::find()->where(['lot_id' => $categoryLot])->all(), 'id', 'subject_id');
		$subjectLot = array_unique($subjectLot);
		$subjects = ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');


		$branchLot = ArrayHelper::map(BranchLot::find()->where(['lot_id' => $categoryLot])->all(), 'id', 'branch_id');
		$branchLot = array_unique($branchLot);
		$branchs = ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');

		

		
		
		/*$lot = ArrayHelper::map(Lot::find()->all(), 'id', 'city_id');
		$lot = array_unique($lot);
		$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');

		$subjectLot = ArrayHelper::map(SubjectLot::find()->all(), 'id', 'subject_id');
		$subjectLot = array_unique($subjectLot);
		$subjects = ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');

		$branchLot = ArrayHelper::map(BranchLot::find()->all(), 'id', 'branch_id');
		$branchLot = array_unique($branchLot);
		$branchs = ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');*/
		
		return $this->render('view', [
            'lots' => $lots,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'model' => $this->findModel($id),
            'categoryInfo' => $items,
            'region' => $region,
            'subjects' => $subjects,
            'branchs' => $branchs,
        ]);
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
