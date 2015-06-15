<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property string $id
 * @property string $text
 * @property string $public
 * @property string $creation_time
 * @property string $update_time
 * @property string $user2_id
 * @property string $lot_id
 *
 * @property Lot $lot
 * @property User2 $user2
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['public', 'user2_id', 'lot_id'], 'integer'],
            [['creation_time', 'update_time'], 'safe'],
            [['user2_id', 'lot_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Комментарий',
            'public' => 'Публиковаит',
            'creation_time' => 'Создание',
            'update_time' => 'Обновление',
            'user2_id' => 'User2 ID',
            'lot_id' => 'Lot ID',
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
    public function getUser2()
    {
        return $this->hasOne(User2::className(), ['id' => 'user2_id']);
    }
}
