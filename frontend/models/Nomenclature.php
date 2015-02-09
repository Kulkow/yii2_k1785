<?php

namespace frontend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "nomenclature".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $category_id
 * @property string $price
 * @property string $content
 * @property integer $image_id
 * @property integer $sort
 *
 * @property Category $category
 * @property Image $image
 */
class Nomenclature extends \yii\db\ActiveRecord
{
    public $image = NULL; 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nomenclature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'price', 'content'], 'required'],
            [['category_id', 'sort'], 'integer'],
            [['content'], 'string'],
            [['name', 'alias', 'price'], 'string', 'max' => 255],
            /*[['image'], 'file'],*/
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'content' => 'Content',
            'image_id' => 'Image ID',
            'sort' => 'Sort',
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
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }
    
    public function getAllCategory(){
        return Category::find()->all();
    }
}
