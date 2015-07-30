<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "follower".
 *
 * @property integer $id
 * @property string $mail
 * @property string $date
 */
class Follower extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follower';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail'], 'required'],
            ['mail', 'email'],
            ['mail', 'unique'],
            [['date'], 'safe'],
            [['mail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail' => 'email',
            'date' => 'Дата подписки',
        ];
    }
}
