<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category_lot".
 *
 * @property string $id
 * @property string $lot_id
 * @property string $category_id
 *
 * @property Category $category
 * @property Lot $lot
 */
class CategoryLot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_lot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lot_id', 'category_id'], 'required'],
            [['lot_id', 'category_id'], 'integer']
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
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }
}
