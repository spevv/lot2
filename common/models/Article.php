<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $meta_description
 * @property string $meta_keyword
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }
    
    public function behaviors()
	{
	    return [
	        'slug' => [
	            'class' => 'Zelenin\yii\behaviors\Slug',
	            'slugAttribute' => 'slug',
	            'attribute' => 'name',
	            // optional params
	            'ensureUnique' => true,
	            'translit' => true,
	            'replacement' => '-',
	            'lowercase' => true,
	            'immutable' => false,
	            // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general. 
	            'transliterateOptions' => 'Russian-Latin/BGN;'
	        ]
	    ];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name', 'meta_description', 'meta_keyword'], 'string', 'max' => 255]
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
            'description' => 'Описание',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'slug' => 'slug',
        ];
    }
}
