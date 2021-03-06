<?php

namespace backend\controllers;

use Yii;
use common\models\Lot;
use backend\models\LotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\SubjectLot;
use common\models\BranchLot;
use common\models\CategoryLot;
use common\models\LotImage;
use common\models\LotRateStatistic;
use common\models\GeobaseCity;
use yii\filters\AccessControl;

use yii\helpers\ArrayHelper;
use common\models\GeobaseIp;
use iutbay\yii2kcfinder\KCFinder;
/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{
    public function behaviors()
    {
        return [
        'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {

		//var_dump(Yii::$app->session['KCFINDER']);
    	
    	//var_dump(Yii::$app->getUser());
		$this->setKCFINDER();
        $searchModel = new LotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lot model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$this->setKCFINDER();
        $model = new Lot();

        if ($model->load(Yii::$app->request->post())) {
        	$post = Yii::$app->request->post();
        	//check subject
        	//var_dump($post);
        	$model->save();
        	//var_dump($model->id);
        	
        	if(!empty($post["Lot"]["subjects"]))
        	{
				foreach($post["Lot"]["subjects"] as $key)
				{	
				//var_dump($model->id);
					$subject = new SubjectLot();
					$subject->subject_id = $key;
					$subject->lot_id = $model->id;
					$subject->save();
				}
			}
			
			if(!empty($post["Lot"]["branchs"]))
			{
				foreach($post["Lot"]["branchs"] as $key)
				{	
					$branch = new BranchLot();
					$branch->branch_id = $key;
					$branch->lot_id = $model->id;
					$branch->save();
				}
			}
			if(!empty($post["Lot"]["categories"]))
			{
				foreach($post["Lot"]["categories"] as $key)
				{	
					$category = new CategoryLot();
					$category->category_id = $key;
					$category->lot_id = $model->id;
					$category->save();
				}
			}
        	if(!empty($post["Lot"]["lotImages"]) )
        	{
				foreach($post["Lot"]["lotImages"] as $key => $value)
				{
					$image = new LotImage();
					$image->url = $value;
					$image->lot_id = $model->id;
					$image->priority = $key;
					$image->save();
				}
			}
			
			
			
			
           // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'formRate' => '',
				'cities' => $this->getCities(),
            ]);
        }
    }
    
    public function actionCleanRate()
    { 
		if (Yii::$app->request->post()) {
        	$post = Yii::$app->request->post();
        	
        	$formRateModel = LotRateStatistic::find()->where(['lot_id' => $post["LotRateStatistic"]['lot_id']])->orderBy('id desc')->one();
        	
        	if($formRateModel)
        	{
				$formRateModel->status = 1;
				$formRateModel->save();
				
			}
        }
        echo '<span> </span>';
		
	}

    /**
     * Updates an existing Lot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$this->setKCFINDER();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$post = Yii::$app->request->post();
        	//var_dump($post);
        	//die;
        	//var_dump($post["Lot"]);
        	if(!isset($post["Lot"]['image'])){
				//$post["Lot"]['image'] = NULL;
				$model->image = NULL;
			}
			//var_dump($post["Lot"]);
        	
			$templotImagesArray =[];
        	if(!empty($post["Lot"]["lotImages"])){
				foreach($post["Lot"]["lotImages"] as $key => $value)
				{	
					//var_dump('key='.$key);
					//var_dump('value='.$value);
					$temp = LotImage::find()->where(['lot_id' =>$model->id, 'url' =>$value])->orderBy('id')->all();
					if(!empty($temp))
					{
						$templotImagesArray[$key] = $temp[0]['id'];
						//var_dump($temp[0]['priority'].' != '.$key);
						if($temp[0]['priority'] != $key){
							$image = LotImage::findOne($temp[0]['id']);
							//var_dump($image);
							$image->priority = $key;
							$image->save();
						}	
					}
					else
					{
						$templotImagesArray[$key] = ['url'=>$value];
					}
				}
				$tempImages = LotImage::find()->where(['lot_id' =>$model->id])->orderBy('id')->all();
				if(!empty($tempImages))
				{
					foreach($tempImages as $tempImage)
					{
						if(!in_array($tempImage['id'], $templotImagesArray)){
							$LotImageDelete = LotImage::findOne($tempImage['id']);
							$LotImageDelete->delete();
						}
					}	
				}
				foreach($templotImagesArray as $key => $value){
					if(is_array($value))
					{
						$image = new LotImage();
						$image->url = $value['url'];
						$image->lot_id = $model->id;
						$image->priority = $key;
						$image->save();
					}	
				}
			}
			else
			{
				LotImage::deleteAll('lot_id ='. $model->id);	
			}
			
			
			//check subject
        	$tempSubjectArray =[];
        	if(!empty($post["Lot"]["subjects"])){
				foreach($post["Lot"]["subjects"] as $key)
				{	
					$temp = SubjectLot::find()->where(['lot_id' =>$model->id, 'subject_id' =>$key])->orderBy('id')->all();
					if(!empty($temp))
					{
						$tempSubjectArray[$key] = $temp[0]['id'];	
					}
					else
					{
						$tempSubjectArray[$key] = NULL;
					}
				}
				$tempLots = SubjectLot::find()->where(['lot_id' =>$model->id])->orderBy('id')->all();
				if(!empty($tempLots))
				{
					foreach($tempLots as $tempLot)
					{
						if(!in_array($tempLot['id'], $tempSubjectArray)){
							$SubjectLotDelete = SubjectLot::findOne($tempLot['id']);
							$SubjectLotDelete->delete();
						}
					}	
				}
				foreach($tempSubjectArray as $key => $value){
					if($value == NULL)
					{
						$subject = new SubjectLot();
						$subject->subject_id = $key;
						$subject->lot_id = $model->id;
						$subject->save();
					}	
				}
			}
			else
			{
				SubjectLot::deleteAll('lot_id ='. $model->id);	
			}
        	
			unset($temp);
			//check branch
        	$tempBrunchArray =[];
        	if(!empty($post["Lot"]["branchs"])){
				foreach($post["Lot"]["branchs"] as $key)
				{	
					$temp = BranchLot::find()->where(['lot_id' =>$model->id, 'branch_id' =>$key])->orderBy('id')->all();
					if(!empty($temp))
					{
						$tempBrunchArray[$key] = $temp[0]['id'];	
					}
					else
					{
						$tempBrunchArray[$key] = NULL;
					}
				}
				$tempBranchs = BranchLot::find()->where(['lot_id' =>$model->id])->orderBy('id')->all();
				if(!empty($tempBranchs))
				{
					foreach($tempBranchs as $tempBranch)
					{
						if(!in_array($tempBranch['id'], $tempBrunchArray)){
							$SubjectLotDelete = BranchLot::findOne($tempBranch['id']);
							$SubjectLotDelete->delete();
						}
					}	
				}
				foreach($tempBrunchArray as $key => $value){
					if($value == NULL)
					{
						$branch = new BranchLot();
						$branch->branch_id = $key;
						$branch->lot_id = $model->id;
						$branch->save();
					}	
				}
			}
			else
			{
				BranchLot::deleteAll('lot_id ='. $model->id);	
			}
			
			unset($temp);
			//check category
        	$tempCategoryArray =[];
        	if(!empty($post["Lot"]["categories"])){
				foreach($post["Lot"]["categories"] as $key)
				{	
					$temp = CategoryLot::find()->where(['lot_id' =>$model->id, 'category_id' =>$key])->orderBy('id')->all();
					if(!empty($temp))
					{
						$tempCategoryArray[$key] = $temp[0]['id'];	
					}
					else
					{
						$tempCategoryArray[$key] = NULL;
					}
				}
				$tempCategories = CategoryLot::find()->where(['lot_id' =>$model->id])->orderBy('id')->all();
				if(!empty($tempCategories))
				{
					foreach($tempCategories as $tempCategory)
					{
						if(!in_array($tempCategory['id'], $tempCategoryArray)){
							$categoryLotDelete = CategoryLot::findOne($tempCategory['id']);
							$categoryLotDelete->delete();
						}
					}	
				}
				foreach($tempCategoryArray as $key => $value){
					if($value == NULL)
					{
						$category = new CategoryLot();
						$category->category_id = $key;
						$category->lot_id = $model->id;
						$category->save();
					}	
				}
			}
			else
			{
				CategoryLot::deleteAll('lot_id ='. $model->id);	
			}
        	$model->save();
           // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
        	
        	$formRateModel = LotRateStatistic::find()->where(['lot_id' =>$model->id])->orderBy('id desc')->one();
        	
        	//use common\models\RateWinner;
        	
        	//var_dump($formRateModel);
        	if($formRateModel){
        		//var_dump($formRateModel);
        		if($formRateModel['status'] == 0){
        			
        			$formRateModel->status = 1;
					$formRate = $this->renderPartial('_form-rate', [
		            	'model' => $formRateModel,
		            ]);
				}
        		else{
					$formRate = "";
				}	
			}
			else{
				$formRate = "";
			}
        	
        	
        	
            return $this->render('update', [
                'model' => $model,
                'formRate' => $formRate,
                'cities' => $this->getCities(),
            ]);
        }
    }

    /**
     * Deletes an existing Lot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		SubjectLot::deleteAll('lot_id ='. $id);	
		BranchLot::deleteAll('lot_id ='. $id);	
		CategoryLot::deleteAll('lot_id ='. $id);	
		LotImage::deleteAll('lot_id ='. $id);	
        return $this->redirect(['index']);
    }

	/*
	 * return all Cities
	 *
	 * */
	protected function getCities()
	{
		/*$data = Yii::$app->cache->get('getCities');
		if ($data === false) {

			// $data нет в кэше, считаем с нуля.
			$cities = GeobaseCity::find()
				->joinWith('geobase')
				->where(['geobase_ip.country_code'=> 'RU'])
				->orderBy('name')
				->all();

			if($cities)
			{
				Yii::$app->cache->set('getCities', ArrayHelper::map($cities, 'id', 'name'));
			}
			else
			{
				Yii::$app->cache->set('getCities', []);
			}

			// Сохраняем значение $data в кэше. Данные можно получить в следующий раз.
		}
		return $data;*/
		return ArrayHelper::map(GeobaseCity::find()->orderBy('name')->all(), 'id', 'name');

	}

	private function setKCFINDER()
	{
		if(!Yii::$app->session->get('KCFINDER')){
			$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
				'uploadURL' => Yii::getAlias('@uploadURL'), //'/uploads',
				'uploadDir' => Yii::getAlias('@uploadDir'), //dirname(dirname(__DIR__)).'/uploads',
				'access' => [
					'files' => [
						'upload' => true,
						'delete' => true,
						'copy' => true,
						'move' => true,
						'rename' => true,
					],
					'dirs' => [
						'create' => true,
						'delete' => true,
						'rename' => true,
					],
				],
				'disabled' => false,
				'denyZipDownload' => true,
				'denyUpdateCheck' => true,
				'denyExtensionRename' => true,
				'theme' => 'default',
				'types' => [  // @link http://kcfinder.sunhater.com/install#_types
					'files' => [
						'type' => '',
					],
				],
				'access' => [
					'files' => [
						'upload' => true,
						'delete' => true,
						'copy' => true,
						'move' => true,
						'rename' => true,
					],
					'dirs' => [
						'create' => true,
						'delete' => true,
						'rename' => true,
					],
				],
				'lang' => 'ru',
				'thumbsDir' => 'thumbs',
				'thumbWidth' => 100,
				'thumbHeight' => 100,
				'filenameChangeChars' => array(
					" " => "_",
					":" => ".",
					"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"-","є"=>"ye","ѓ"=>"g",
					"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
					"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
					"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
					"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
					"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
					"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
					"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
					"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
					"е"=>"e","ё"=>"yo","ж"=>"zh",
					"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
					"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
					"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
					"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
					"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
					" "=>"_","—"=>"_",","=>"_","!"=>"_","@"=>"_",
					"#"=>"-","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"",
					"("=>"",")"=>"","+"=>"","="=>"",";"=>"",":"=>"",
					"'"=>"", "\""=>"","~"=>"","`"=>"","?"=>"","/"=>"",
					"\\"=>"","["=>"","]"=>"","{"=>"","}"=>"","|"=>""
				),
			]);
			// Set kcfinder session options
			Yii::$app->session->set('KCFINDER', $kcfOptions);
		}
		return TRUE;
	}

    /**
     * Finds the Lot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Lot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
