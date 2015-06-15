<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RateWinner;

/**
 * RateWinnerSearch represents the model behind the search form about `backend\models\RateWinner`.
 */
class RateWinnerSearch extends RateWinner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pay', 'rate_id', 'comment'], 'integer'],
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
        $query = RateWinner::find();

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
            'pay' => $this->pay,
            'rate_id' => $this->rate_id,
            'comment' => $this->comment,
        ]);

        return $dataProvider;
    }
}
