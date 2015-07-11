<?php

namespace common\models;

use Yii;
use frontend\models\UserSocial;
use common\models\UserSettings;

/**
 * This is the model class for table "user_interests".
 *
 * @property string $user_id
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
            [['interests'], 'string'],
            [['user_id'], 'string', 'max' => 255],
            [['user_id'], 'unique']
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
    
    
    public function getUsers()
    {
        return $this->hasOne(UserSocial::className(), ['user_id' => 'user_id']);
    }
    
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'user_id']);
    }
}
