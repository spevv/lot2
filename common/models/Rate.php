<?php

namespace common\models;

use Yii;

use frontend\models\UserSocial;

/**
 * This is the model class for table "rate".
 *
 * @property string $id
 * @property string $time
 * @property string $price
 * @property string $user2_id
 * @property string $lot_id
 *
 * @property Lot $lot
 * @property User2 $user2
 * @property RateWinner[] $rateWinners
 */
class Rate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rate';
    }

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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'price' => 'Price',
            'user2_id' => 'User2 ID',
            'lot_id' => 'Lot ID',
            'refusal' => 'refusal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserSocial::className(), ['user_id' => 'user2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateWinners()
    {
        return $this->hasMany(RateWinner::className(), ['rate_id' => 'id']);
    }
}
