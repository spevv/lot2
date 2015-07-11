<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $toWinner
 * @property string $toLoser
 * @property string $slewRate
 * @property string $endsInMinutes
 * @property string $toPay
 * @property string $successPay
 * @property string $interest
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['toWinner', 'toLoser', 'slewRate', 'endsInMinutes', 'toPay', 'successPay', 'interest', 'toPayClose'], 'integer'],
            [['user_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'toWinner' => 'To Winner',
            'toLoser' => 'To Loser',
            'slewRate' => 'Slew Rate',
            'endsInMinutes' => 'Ends In Minutes',
            'toPay' => 'To Pay',
            'successPay' => 'Success Pay',
            'interest' => 'Interest',
            'toPayClose' => 'toPayClose',
        ];
    }
}
