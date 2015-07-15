<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot".
 *
 * @property string $id
 * @property string $name
 * @property string $speaker
 * @property string $date
 * @property string $remaining_time
 * @property string $price
 * @property string $coordinates
 * @property string $address
 * @property string $address_text
 * @property string $phone
 * @property string $site
 * @property string $short_description
 * @property string $complete_description
 * @property string $condition
 * @property string $creation_time
 * @property string $update_time
 * @property string $public
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $region_id
 * @property string $image
 *
 * @property BranchLot[] $branchLots
 * @property Comment[] $comments
 * @property Region $region
 * @property LotImage[] $lotImages
 * @property Rate[] $rates
 * @property SubjectLot[] $subjectLots
 */
class Lot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lot';
    }
	
	public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['creation_time', 'update_time'],
                   \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
           /* 'slug' => [
	            'class' => 'Zelenin\yii\behaviors\Slug',
	            'slugAttribute' => 'slug',
	            'attribute' => 'short_name',
	            // optional params
	            'ensureUnique' => true,
	            'translit' => true,
	            'replacement' => '-',
	            'lowercase' => true,
	            'immutable' => false,
	            // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general. 
	            'transliterateOptions' => 'Russian-Latin/BGN;'
	        ]*/
        ];
     }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	['slug', 'unique'],
        	[['name', 'slug'], 'required'],
        	['coordinates', 'match', 'pattern' => '/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/',  'message' => 'привер: 55.75399400, 37.62209300'],
        	//['phone', 'match', 'pattern' => '/^\+7 \(\d\d\d\) \d\d\d\-\d\d\d\d$/',  'message' => 'привер +7 (999) 999-9999'], 
            [['remaining_time', 'creation_time', 'update_time', 'subjectLots', 'branchLots'], 'safe'],
            [['price', 'public', 'city_id'], 'integer'],
            [['short_description', 'complete_description', 'condition'], 'string'],
            [['name', 'short_name', 'address',  'city_id'], 'required'],
            [['name', 'short_name', 'speaker', 'date', 'address', 'phone', 'site', 'meta_description', 'meta_keywords', 'image'], 'string', 'max' => 255],
            [['coordinates'], 'string', 'max' => 45],
            
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
            'short_name' => 'Краткое имя',
            'speaker' => 'Спикер',
            'date' => 'Числа проведения',
            'remaining_time' => 'Длительность слота',
            'price' => 'Цена',
            'coordinates' => 'Координаты (не обязательно)',
            'address' => 'Адрес',
            //'address_text' => 'Положения от адреса',
            'phone' => 'Телефон',
            'site' => 'Сайт',
            'short_description' => 'Краткое описание',
            'complete_description' => 'Полное описание',
            'condition' => 'Условия',
            'creation_time' => 'Время создания',
            'update_time' => 'Обновлено',
            'public' => 'Публиковать',
            'meta_description' => 'Описание (поисковики)',
            'meta_keywords' => 'Ключевые слова',
            'region_id' => 'Регион',
            'city_id' => 'Город',
            'image' => 'Логотип',
            'images' => 'Изображения',
            'subjects' => 'Тематика',
            'branchs' => 'Отрасли',
            'lotImages' => 'Изображения',
            'subjectsToString' => 'Тематика',
            'branchsToString' => 'Отрасли',
            'subjectLots' => 'Тематика',
            'branchLots' => 'Отрасли',
            'categoryLots' => 'Категории',
            'categories' => 'Категории',
            'slug' => 'ЧПУ',
        ];
    }
    
    public function extraFields()
	{
	    return [
	        'subjects'=>function() {
	            if (isset($this->subjects)) {
	                $subjects = [];
	                foreach($this->subjects as $subjectstag) {
	                    $subjects[] = $subjectstag->name;
	                }
	                return implode(', ', $subjects);
	            }
	        },
	        'images'=> function() {
	        	 if (isset($this->lotImages)) {
		            $lotImages = [];
		            foreach($this->lotImages as $branchstag) {
		                $lotImages[] = $branchstag->url;
		            }
		            return  $lotImages;
		        }
	        	//return  $this->lotImages;
	        },
	        'branchs'=>function() {
	            if (isset($this->branchs)) {
	                $branchs = [];
	                foreach($this->branchs as $branchstag) {
	                    $branchs[] = $branchstag->name;
	                }
	                return implode(', ', $branchs);
	            }
	        },
	    ];
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchLots()
    {
        return $this->hasMany(BranchLot::className(), ['lot_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['lot_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(GeobaseRegion::className(), ['id' => 'region_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeobaseCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotImages()
    {
        return $this->hasMany(LotImage::className(), ['lot_id' => 'id'])->orderBy('priority');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(Rate::className(), ['lot_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectLots()
    {
        return $this->hasMany(SubjectLot::className(), ['lot_id' => 'id']);
    }
    
    public function getSubjects()
	{
	    return $this->hasMany(Subject::className(), ['id' => 'subject_id'])->viaTable(SubjectLot::tableName(), ['lot_id' => 'id']);
	}
	
	public function getBranchs()
	{
	    return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable(BranchLot::tableName(), ['lot_id' => 'id']);
	}
	
	public function getSubjectsToString()
	{
	    if (isset($this->subjects)) {
            $subjects = [];
            foreach($this->subjects as $subjectstag) {
                $subjects[] = $subjectstag->name;
            }
            return implode(', ', $subjects); 
        }
	}
	
	public function getBranchsToString()
	{
	   if (isset($this->branchs)) {
            $branchs = [];
            foreach($this->branchs as $branchstag) {
                $branchs[] = $branchstag->name;
            }
            return implode(', ', $branchs);
        }
	}
	
	public function getLotImagesToString()
	{
	   if (isset($this->lotImages)) {
            $lotImages = [];
            foreach($this->lotImages as $branchstag) {
                $lotImages[] = $branchstag->url;
            }
            return implode(', ', $lotImages);
        }
	}
	
	    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryLots()
    {
        return $this->hasMany(CategoryLot::className(), ['lot_id' => 'id']);
    }
    
    public function getCategories()
	{
	    return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable(CategoryLot::tableName(), ['lot_id' => 'id']);
	}
	
	
	
}
