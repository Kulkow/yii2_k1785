<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nomenclature".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category
 * @property string $price
 * @property string $content
 * @property integer $image
 */
class Nomenclature extends \yii\db\ActiveRecord
{
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
            [['name', 'category', 'price', 'content', 'image'], 'required'],
            [['category', 'image'], 'integer'],
            [['price'], 'number'],
            [['content'], 'string'],
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
            'name' => 'Name',
            'category' => 'Category',
            'price' => 'Price',
            'content' => 'Content',
            'image' => 'Image',
        ];
    }
}
