<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property string $id
 * @property string $name
 *
 * @property SubjectLot[] $subjectLots
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'name' => 'Имя',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectLots()
    {
        return $this->hasMany(SubjectLot::className(), ['subject_id' => 'id']);
    }
}
