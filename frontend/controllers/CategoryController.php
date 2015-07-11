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
	

  	public function actionView($category)
    {
    	$categoryInfo =  $this->findModelSlug($category);
		$lotIds = $this->getLotsOfCategory($categoryInfo->id);	
		$searchModel = new CategoryLotSearch();	
		$dataProvider2 = $searchModel->search(null,$lotIds, false);
		$categoryLot = ArrayHelper::map(CategoryLot::find()->where(['category_id' => $categoryInfo->id])->all(), 'id', 'lot_id');
		$categoryLot = array_unique($categoryLot);

		$search =  $this->renderPartial('/site/_search', [
			'action' => ['view', 'category' => $category],
        	'model' => $searchModel, 
        	'region' => $this->getActiveRegions($categoryLot), 
        	'subjects' => $this->getActiveSubjects($categoryLot), 
        	'branchs' => $this->getActiveBranchs($categoryLot)
        ]);
        
		if(Yii::$app->request->isPost)
		{
			//var_dump('is post');
			Yii::$app->session->set('searchData', Yii::$app->request->post());
			//$dataProvider = $searchModel->search(Yii::$app->request->post(), true);
			$dataProvider = $searchModel->search(Yii::$app->request->post(), $lotIds, true);
			
			$search =  $this->renderAjax('/site/_search', [
				'action' => ['view', 'category' => $category],
	        	'model' => $searchModel, 
	        	'region' => $this->getActiveRegions($categoryLot), 
        		'subjects' => $this->getActiveSubjects($categoryLot), 
        		'branchs' => $this->getActiveBranchs($categoryLot)
	        ]);
			
			return $this->renderPartial('/site/_active-block-lot', [
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
		}
		elseif(Yii::$app->request->isPjax)
        {
        	//$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        	$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $lotIds, true);
        	$search =  $this->renderAjax('/site/_search', [
        		'action' => ['view', 'category' => $category],
	        	'model' => $searchModel, 
	        	'region' => $this->getActiveRegions($categoryLot), 
        		'subjects' => $this->getActiveSubjects($categoryLot), 
        		'branchs' => $this->getActiveBranchs($categoryLot)
	        ]);
			
			return $this->renderPartial('/site/_active-block-lot', [
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
		}
		else
		{
			
			Yii::$app->opengraph->set([
			    'title' => $categoryInfo->name,
			    'description' =>$categoryInfo->meta_description,
			    'image' => Yii::$app->params['siteInfo']['image'],
			]);
			
			//var_dump($categoryInfo->name);	
			Yii::$app->session->remove('searchData');
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $lotIds, true);
			
			$activeBlockLot =  $this->renderPartial('/site/_active-block-lot', [
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
			
			return $this->render('/site/index', [
	            'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $this->getCategoty(),
	            'activeBlockLot' => $activeBlockLot,
	            'model'=> $categoryInfo,
	            
	        ]);
		}
    }
    
    protected function getLotsOfCategory($categoryId)
	{
		$categoryLot = CategoryLot::find()
		    ->where(['category_id' => $categoryId])
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
		 
		/*$lots = Lot::find()
		    ->where(['id' => $lotId])
		    ->orderBy('id')
		    ->all();*/
		
		return $lotId;
		//return ['lots'=>$lots, 'lotIds'=>$lotId];
	}
	
	// return all active subjects
	private function getActiveSubjects($categoryLot)
	{
		$subjectLots = SubjectLot::find()->where(['lot_id' => $categoryLot])->all();
		foreach($subjectLots as $key => $subjectLot)
		{
			if($subjectLot->lot->public != 1)
			{
				unset($subjectLots[$key]);
			}
			if($subjectLot->lot->remaining_time < Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
			{
				unset($subjectLots[$key]);
			}
		}
		
		$subjectLot = ArrayHelper::map($subjectLots, 'id', 'subject_id');
		$subjectLot = array_unique($subjectLot);
		return ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');
	}
	
	// return all active Branchs
	private function getActiveBranchs($categoryLot)
	{
		$branchLots = BranchLot::find()->where(['lot_id' => $categoryLot])->all();
		foreach($branchLots as $key => $branchLot)
		{
			if($branchLot->lot->public != 1)
			{
				unset($branchLots[$key]);
			}
			
			if($branchLot->lot->remaining_time < Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
			{
				unset($branchLots[$key]);
			}
		}
		
		$branchLot = ArrayHelper::map($branchLots, 'id', 'branch_id');
		$branchLot = array_unique($branchLot);
		return ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');
	}
	
	//return region
	private function getActiveRegions($categoryLot)
	{
		$lot = ArrayHelper::map(Lot::find()->where(['id' => $categoryLot, 'public'=> 1])->andWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')])->all(), 'id', 'city_id');
		$lot = array_unique($lot);
		return  ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');
	}
	
    
    //return public category
    private function getCategoty()
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
		return $items;
	}

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelSlug($slug)
    {
        if (($model = Category::find()->where(['slug'=> $slug])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не существует.');
        }
    }
    
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не существует.');
        }
    }
}
