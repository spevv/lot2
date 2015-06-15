<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot_image".
 *
 * @property string $id
 * @property integer $priority
 * @property string $url
 * @property string $lot_id
 *
 * @property Lot $lot
 */
class LotImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lot_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priority', 'lot_id'], 'integer'],
            [['lot_id'], 'required'],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'priority' => 'Priority',
            'url' => 'Url',
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
}
