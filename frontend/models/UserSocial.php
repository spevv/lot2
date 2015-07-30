<?php

namespace frontend\models;

use Yii;
use common\models\UserSettings;

/**
 * This is the model class for table "user_social".
 *
 * @property integer $id
 * @property string $client
 * @property string $user_id
 * @property string $name
 * @property string $email
 * @property string $image
 */
class UserSocial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_social';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client', 'user_id', 'name'], 'required'],
            [['image'], 'string'],
            ['email', 'email'],
            [['client', 'user_id', 'name', 'email', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client' => 'Client',
            'user_id' => 'User ID',
            'name' => 'Имя',
            'email' => 'Email',
            'image' => 'Image',
            'link' => 'link',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'user_id']);
    }
    
    
}
