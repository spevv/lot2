<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subject_lot".
 *
 * @property string $id
 * @property string $subject_id
 * @property string $lot_id
 *
 * @property Lot $lot
 * @property Subject $subject
 */
class SubjectLot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject_lot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_id', 'lot_id'], 'required'],
            [['subject_id', 'lot_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Subject ID',
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
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }
}
