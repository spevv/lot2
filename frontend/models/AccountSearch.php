<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rate;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class AccountSearch extends Rate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user2_id', 'lot_id'], 'required'],
            [[ 'lot_id','price', 'refusal'], 'integer'],
            [['time'], 'string', 'max' => 45],
            [['user2_id'], 'string', 'max' => 255]
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
	    	
    	
        $query = Rate::find();
		
		$query->groupBy('lot_id');
        
        if($active)
        {
			$query->joinWith('lot', true, 'LEFT JOIN'); 
        	$query->andFilterWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')]); 
        	$query->groupBy(['rate.lot_id', 'lot.id']);      
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
        
        $query->andFilterWhere([
            'lot_id' => $this->lot_id,
        ]);
        
         $query->andFilterWhere([
            'user2_id' => $user2_id,
        ]);
		
       
       
		
		

		 
       /* $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword]);*/

        return $dataProvider;
    }
}
