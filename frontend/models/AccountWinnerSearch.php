<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rate;
use common\models\RateWinner;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class AccountWinnerSearch extends RateWinner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         	[['pay', 'rate_id', 'status'], 'integer'],
            [['rate_id'], 'required'],
            [['winner_time', 'pay_time', 'send_email_time'], 'safe'],
            [['rate_info', 'comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $active = NULL)
    {

    	$identity = Yii::$app->getUser()->getIdentity();
	    $user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	
    	
        $query = RateWinner::find();
		
		//$query->groupBy('lot_id');
        
        if($active)
        {
			$query->joinWith('rate', true, 'LEFT JOIN'); 
			$query->joinWith('rate.lot', true, 'LEFT JOIN'); 
        	//$query->andFilterWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')]); 
        	$query->groupBy(['rate.id', 'rate_id']);   
        	$query->groupBy(['rate.lot_id', 'lot.id']);  
        
        	$query->andFilterWhere([
        	    'rate.user2_id' => $user2_id,
        	]);   
		}
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             //$query->where('0=1');
            return $dataProvider;
        }
		
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        
        
        
        
		
       
       
		
		

		 
       /* $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword]);*/

        return $dataProvider;
    }
}
