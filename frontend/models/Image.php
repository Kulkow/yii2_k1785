<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $type
 * @property string $path
 * @property string $alt
 * @property string $hide
 * @property integer $timestamp
 *
 * @property Nomenclature[] $nomenclatures
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'path', 'alt'], 'required'],
            [['path', 'alt', 'hide'], 'string'],
            [['timestamp'], 'integer'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'path' => 'Path',
            'alt' => 'Alt',
            'hide' => 'Hide',
            'timestamp' => 'Timestamp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNomenclatures()
    {
        return $this->hasMany(Nomenclature::className(), ['image_id' => 'id']);
    }
}
