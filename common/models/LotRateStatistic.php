<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot_rate_statistic".
 *
 * @property integer $id
 * @property integer $lot_id
 * @property integer $last_rate
 */
class LotRateStatistic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lot_rate_statistic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lot_id', 'last_rate'], 'required'],
            [['lot_id', 'last_rate', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lot_id' => 'Lot ID',
            'last_rate' => 'Last Rate',
            'status' => 'Status',
        ];
    }
}
