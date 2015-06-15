<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lot;

/**
 * LotSearch represents the model behind the search form about `backend\models\Lot`.
 */
class LotSearch extends Lot
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'public', 'city_id'], 'integer'],
            [['name', 'short_name', 'speaker', 'date', 'remaining_time', 'coordinates', 'address', 'address_text', 'phone', 'site', 'short_description', 'complete_description', 'condition', 'creation_time', 'update_time', 'meta_description', 'meta_keywords', 'image', 'subjectLots', 'branchLots'], 'safe'],
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
	
	
	public function attributes()
	{
	    // add related fields to searchable attributes
	    return array_merge(parent::attributes(), ['subjectLots', 'branchLots']);
	}

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
    	//var_dump($params);
    	//die();
    	/*if(!isset($params["LotSearch"]['subjects'])){
			$params["LotSearch"]['subjects'] = Null;
		}*/
		
    	
        $query = Lot::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //$query->joinWith(['subjectLots']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'remaining_time' => $this->remaining_time,
            'price' => $this->price,
            'creation_time' => $this->creation_time,
            'update_time' => $this->update_time,
            'public' => $this->public,
            'city_id' => $this->city_id,
        ]);
        
        if((isset($this->subjectLots)) AND ($this->subjectLots !=null)){
			 $query->innerJoin('subject_lot', 'lot.id = subject_lot.lot_id AND subject_lot.subject_id = :cname', [':cname' => $this->subjectLots]);
		}
		if((isset($this->branchLots)) AND ($this->branchLots !=null)){
			 $query->innerJoin('branch_lot', 'lot.id = branch_lot.lot_id AND branch_lot.branch_id = :cname', [':cname' => $this->branchLots]);
		}

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'speaker', $this->speaker])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'address_text', $this->address_text])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'complete_description', $this->complete_description])
            ->andFilterWhere(['like', 'condition', $this->condition])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'image', $this->image]);
		
	 //$query->innerJoin('subject_lot', 'lot.id = subject_lot.lot_id AND subject_lot.subject_id = :cname', [':cname' => $this->subjectLots]);
	    
		/*if((isset($params["LotSearch"]['subjectLots'])) AND ($params["LotSearch"]['subjectLots'] !=null)){
			 $query->innerJoin('subject_lot', 'lot.id = subject_lot.lot_id AND subject_lot.subject_id = :cname', [':cname' => $params["LotSearch"]['subjectLots']]);
		}*/
	    

	//var_dump($query);
    //	die();
        return $dataProvider;
    }
    
    
}
