<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_interests".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $interests
 */
class UserInterests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_interests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'string'],
            [['interests'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'interests' => 'Interests',
        ];
    }
}
