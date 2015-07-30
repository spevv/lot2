<?php

namespace common\models;

use Yii;
use common\models\GeobaseCity;

/**
 * This is the model class for table "geobase_ip".
 *
 * @property string $ip_begin
 * @property string $ip_end
 * @property string $country_code
 * @property string $city_id
 */
class GeobaseIp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geobase_ip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip_begin', 'ip_end', 'country_code', 'city_id'], 'required'],
            [['ip_begin', 'ip_end', 'city_id'], 'integer'],
            [['country_code'], 'string', 'max' => 2],
            [['ip_begin'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ip_begin' => 'Ip Begin',
            'ip_end' => 'Ip End',
            'country_code' => 'Код страны',
            'city_id' => 'City ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeobaseCity::className(), ['id' => 'city_id']);
    }
}
