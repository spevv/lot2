<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GeobaseCity;

/**
 * GeobaseCitySearch represents the model behind the search form about `common\models\GeobaseCity`.
 */
class GeobaseCitySearch extends GeobaseCity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        $query = GeobaseCity::find();

        $query->joinWith('geobase', true, 'LEFT JOIN');

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
            'region_id' => $this->region_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $query->andFilterWhere([
            'geobase_ip.country_code'=> 'RU'
        ]);
        //$query->innerJoin('geobase', 'lot.id = subject_lot.lot_id AND subject_lot.subject_id = :cname', [':cname' => $this->subjectLots]);


        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->groupBy(['geobase_city.id', 'geobase_ip.city_id']);
        $query->orderBy('geobase_city.name');

        return $dataProvider;
    }
}
