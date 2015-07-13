<?php

namespace backend\controllers;

use Yii;
use common\models\Subject;
use backend\models\SubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dosamigos\editable\EditableAction;
/**
 * SubjectController implements the CRUD actions for Subject model.
 */
class SubjectController extends Controller
{
	
	public function actions()
	{
	    return [
	        'editable' => [
	            'class' => EditableAction::className(),
	            'modelClass' => Subject::className(),
	            'forceCreate' => false
	        ]
	    ];
	}
	
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
     * Lists all Subject models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$searchModel = new SubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
        $model = new Subject();
	    if ($model->load(Yii::$app->request->post()) && $model->save())
	    {
	        $model = new Subject();
	        
	        //$searchModel = new BranchSearch();
	    	//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	        
	    }
	    $searchModel = new SubjectSearch();
	    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	 
	    return $this->render('index', [
	        'searchModel' => $searchModel,
	        'dataProvider' => $dataProvider,
	        'model' => $model,
	    ]);
    }

    /**
     * Displays a single Subject model.
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
     * Creates a new Subject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subject();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionAjaxDelete($id) {
	    if (Yii::$app->request->isAjax) {
	        $this->findModel($id)->delete();
	    	Yii::$app->response->format = 'json';
	        $res = array(
	            'body'    => $id,
	            'success' => true,
	        );
	        return $res;
	    }
	   
	}

    /**
     * Finds the Subject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Subject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
