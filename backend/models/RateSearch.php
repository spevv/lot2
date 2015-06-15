<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rate;

/**
 * RateSearch represents the model behind the search form about `backend\models\Rate`.
 */
class RateSearch extends Rate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user2_id', 'lot_id'], 'integer'],
            [['time', 'price'], 'safe'],
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
    public function search($params)
    {
        $query = Rate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user2_id' => $this->user2_id,
            'lot_id' => $this->lot_id,
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
