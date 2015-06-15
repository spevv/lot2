<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property string $id
 * @property string $name
 *
 * @property BranchLot[] $branchLots
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	['name', 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchLots()
    {
        return $this->hasMany(BranchLot::className(), ['branch_id' => 'id']);
    }
}
