<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rate_winner".
 *
 * @property string $id
 * @property string $pay
 * @property string $rate_id
 * @property string $comment
 *
 * @property Rate $rate
 */
class RateWinner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rate_winner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay', 'rate_id', 'status'], 'integer'],
            [['rate_id'], 'required'],
            [['winner_time', 'pay_time'], 'safe'],
            [['rate_info', 'comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay' => 'Pay',
            'rate_id' => 'Rate ID',
            'comment' => 'Comment',
            'rate_info' => 'Info',
            'winner_time' => 'winner_time',
            'pay_time' => 'pay_time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   /* public function getRate()
    {
        return $this->hasOne(Rate::className(), ['id' => 'rate_id']);
    }*/
}
