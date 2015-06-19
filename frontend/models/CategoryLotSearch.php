<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lot;
use yii\db\Query;

/**
 * CategoryLotSearch represents the model behind the search form about `common\models\Lot`.
 */
class CategoryLotSearch extends Lot
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'public'], 'integer'], //'region_id'
            [['name', 'speaker', 'date', 'remaining_time', 'coordinates', 'address', 'address_text', 'phone', 'site', 'short_description', 'complete_description', 'condition', 'creation_time', 'update_time', 'meta_description', 'meta_keywords', 'image', 'city_id', 'subjects', 'branchs'], 'safe'],
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
	    return array_merge(parent::attributes(), ['subjects', 'branchs']);
	}
	
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $lotId, $oldTime=null)
    {

        $query = Lot::find();
		$query->joinWith('subjects', true, 'LEFT JOIN');
		$query->joinWith('branchs', true, 'LEFT JOIN');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                    'pageSize' => 10,
                    //'pageParam' => 'page',
                    //'validatePage' => false,
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'lot.id' => $lotId,
            'remaining_time' => $this->remaining_time,
            'price' => $this->price,
            'creation_time' => $this->creation_time,
            'update_time' => $this->update_time,
            'public' => 1,
            'city_id' => $this->city_id,
            'subject_lot.subject_id' => $this->subjects,
            'branch_lot.branch_id' => $this->branchs,
        ]);
        
        if((isset($this->subjects)) AND ($this->subjects !=null)){

		}

        $query->andFilterWhere(['like', 'name', $this->name])
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
        
        if($oldTime){
			$query->andFilterWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')]);
		}
		else{
			$query->andFilterWhere(['<',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')]);
		}
		// > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss'
		$query->orderBy(['remaining_time' => SORT_ASC]);
		$query->groupBy(['lot.id', 'branch_lot.lot_id']);
		$query->groupBy(['lot.id', 'subject_lot.lot_id']);
        return $dataProvider;
    }
}
