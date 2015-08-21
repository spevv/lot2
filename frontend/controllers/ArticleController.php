<?php

namespace frontend\controllers;

use Yii;
use common\models\Article;
use frontend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\ContactForm2;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
	
	/*public function behaviors()
	{
	    return [
	        [
	            'class' => 'yii\filters\PageCache',
	            'only' => ['view'],
	            'duration' => 3600,
	        ],
	    ];
	}*/

    /**
     * Lists all Article models.
     * @return mixed
     */
   /* public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single Article model.
     * @param string $id
     * @return mixed
     */
   /* public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/
    
    public function actionContact()
    {  	
    //var_dump('sdf');
        $model = new ContactForm2();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Поздравляем! Ваше сообщение успешно отправлено.');
                //$return = '1';
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить сообщение.');
                // $return = '';
            }
            
            $modelNew = new ContactForm2();
            return $this->renderPartial('_form', [
                'model' => $modelNew,
                //'send' => $return,
            ]);
        } 
    }
    
    
    public function actionView($article)
    {
    	
    	//$modelNew = new ContactForm2();

       /* $model = new ContactForm2();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Поздравляем! Ваше сообщение успешно отправлено.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить сообщение.');
            }
            
            $modelNew = new ContactForm();
            return $this->renderPartial('_form', [
                'model' => $modelNew,
            ]);
        } */
    	
    	//var_dump($article);
    	//die();
    	
		/*
		$articles = [
			[
				'label' => 'О проекте', 
				'linkOptions'=> ['class'=> ''], 
				'url'=> '',
			],
			[
				'label' => 'Отзывы', 
				'linkOptions'=> ['class'=> ''], 
				'url'=> '',
			],
			[
				'label' => 'Правила участвия', 
				'linkOptions'=> ['class'=> ''], 
				'url'=> '',
			],
			[
				'label' => 'Как развиваться дальше?', 
				'linkOptions'=> ['class'=> ''], 
				'url'=> '',
			],
		];*/
    	
    	//$contactForm = new ContactForm();
    	
    	$model =  $this->findModel($article);
    	if($model)
    	{
			Yii::$app->opengraph->set([
			    'title' => $model->name,
			    'description' => $model->meta_description,
			]);
		}
		
		if($model->id == 10){
			$modelNew = new ContactForm2();
	        $contactForm =  $this->renderPartial('_form', [
	            'model' => $modelNew,
	            'send' => '',
	        ]);
		}
		else
		{
			$contactForm ="";
		}
		
    	  
        return $this->render('view', [
            'model' => $model,
            'articles' => $this->getMenuUrl(),
            'contactForm' => $contactForm,
            //'articles' => $articles,
        ]);
    }

    protected function getMenuUrl()
    {

        $articles = Article::find()->all();
        if($articles)
        {
            $urls['organizations'] = [];
            $urls['o-proyekte'] = [];
            $urls['pravila-uchastija'] = [];
            $urls['kak-razvivatsya-deshevle'] = [];
            $urls['publichnaya-oferta'] = [];
            foreach($articles as $article)
            {
                switch( $article->id )
                {
                    case 10:
                        $urls['organizations'] = ['article/view', 'article' => $article->slug];
                        break;
                    case 5:
                        $urls['o-proyekte'] = ['article/view', 'article' => $article->slug];
                        break;
                    case 7:
                        $urls['pravila-uchastija'] = ['article/view', 'article' => $article->slug];
                        break;
                    case 8:
                        $urls['kak-razvivatsya-deshevle'] = ['article/view', 'article' => $article->slug];
                        break;
                    case 9:
                        $urls['publichnaya-oferta'] = ['article/view', 'article' => $article->slug];
                        break;
                }
            }
        }

        $articles = [
            [
                'label' => 'О проекте',
                'linkOptions'=> ['class'=> ''],
                'url'=> $urls['o-proyekte'],
            ],
           /* [
                'label' => 'Отзывы',
                'linkOptions'=> ['class'=> ''],
                'url'=> ['comment/comments'],
            ],*/
            [
                'label' => 'Правила участвия',
                'linkOptions'=> ['class'=> ''],
                'url'=> $urls['pravila-uchastija'],
            ],
           /* [
                'label' => 'Как развиваться дальше?',
                'linkOptions'=> ['class'=> ''],
                'url'=> $urls['kak-razvivatsya-deshevle'],
            ],*/
        ];
        return $articles;
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   /* protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
    protected function findModel($slug)
    {
        if (($model = Article::findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
