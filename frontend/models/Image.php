<?php

namespace frontend\models;

use Yii;
use yii\web\UploadedFile;
use yii\imagine\BaseImage;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


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
    public $file;
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
            /*[['type', 'path', 'alt'], 'required'],*/
            [['path', 'alt', 'hide','ext','realname'], 'string'],
            [['timestamp'], 'integer'],
            [['type'], 'string', 'max' => 255],
            [['file'], 'file','extensions' => 'jpg, png, jpeg, gif','skipOnEmpty' => false],
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
            'ext' => 'Extension',
            'realname' => 'Realname',
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
    
    
    public function upload($file = 'file', $rules = array(), $type = NULL)
	{
        $this->file = UploadedFile::getInstance($this, $file);
        if(! empty($rules)){
            //createValidator
        }
        if($this->validate()){
           // $this->file->saveAs($dir . $model->file->baseName . '.' . $model->file->extension);
            $this->realname = $this->file->getBaseName();
            $this->alt = $this->realname;
            $this->ext = $this->file->getExtension();
            $this->type = ($type ? $type : '');
            do
        	{
    			$this->path = static::random('hexdec', 3).'/'.static::random('hexdec', 3);
    		}
    		while (file_exists($this->_server_path('original')));
            $this->alt = urldecode($_FILES['image']['name']);
    		
    		$this->timestamp = time();
            FileHelper::createDirectory($this->_server_path(), 0775, TRUE);
            if (! file_exists($this->_server_path())){
                exit();
            }
            $this->save();
    		$this->file->saveAs($this->_server_path('original'));
    		return $this->resize($this->type);
           
        }else{
            return $model->errors;
        }    
        /*
        $this->file = $file; //UploadedFile::getInstance($model, 'file');
        if ($this->file && $this->file->validate()) {
            print_r($model->file);
            $this->path = '';
            //$model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
        }else{
            $this->addError('password', 'Incorrect username or password.');
        }
        */
        
        /*$validation = Validation::factory($_FILES)->rules('image', array(
        	array('Upload::valid'),
        	array('Upload::not_empty'),
        	array('Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif'))),
        	array('Upload::size', array(':value', '4M')),
        ));*/
		/*
        if ( ! $validation->check())
		{
            throw new ORM_Validation_Exception($this->errors_filename(), $validation);
		}
*/
		//$this->gallery_id = $gallery->id;
        
        /*$this->target_id = $target->id;
        $this->type = ($type ? $type : $target->model());
		do
		{
			$this->path = Text::random('hexdec', 3).'/'.Text::random('hexdec', 3);
		}
		while (file_exists($this->_server_path('original')));
        $this->alt = urldecode($_FILES['image']['name']);
		//$sql = "SELECT MAX(`order`) as last FROM ".$this->_table_name." WHERE gallery_id = ".$gallery->id;
		//$last = DB::query(Database::SELECT, $sql)->execute()->as_array(NULL, 'last');
		//$this->order = (int)array_pop($last) + 1;
		$this->timestamp = time();
        mkdir($this->_server_path(), 0775, TRUE);
        if (! file_exists($this->_server_path())){
            exit();
        }
        $this->create();
		//$this->gallery->inc();
		$image = Image::factory($_FILES['image']['tmp_name']);
		$image->save($this->_server_path('original'));

		return $this->resize($this->type);
        */
	}

	public function resize($type = NULL)
	{
        $type = ! $type ? 'default' : $type;
        //$config = Kohana::$config->load('image');
        //$params = $config->get($type);
        $params = static::config($type);
        if(! empty($params)){
    		foreach ($params as $index => $size)
    		{
                $this->_resize($size['name'], $size['width'], $size['height'], $size['quality']);
    		}
        }    
		return $this;
	}

	protected function _resize($size, $width, $height, $quality)
	{
		/*$image = BaseImage::factory($this->_server_path('original'));
        $image = BaseImage::open($this->_server_path('original'));
		if ( ! $height OR $image->width / $width < $image->height / $height)
		{
   		    $image->thumbnail($width, $height, Image::WIDTH);
        }
   		else
   	    {
   			$image->resize($width, $height, Image::HEIGHT);
   	    }
   		if ($height)
   	    {
		    $image->crop($width, $height);
		}

        $image->save($this->_server_path($size), $quality);*/
        $original = static::_server_path('original', FALSE);
        $resize = static::_server_path($size);
        BaseImage::thumbnail($original, $width, $height)->save($resize, ['quality' => $quality]);
	}

	public function rotate($degrees)
	{
		var_dump(gd_info());
        echo $degrees;
        $image = Image::factory($this->_server_path('original'));
		$image->rotate($degrees);
		$image->save($this->_server_path('original'));

		$this->timestamp = time();

		return $this->resize()->update();
	}

	public function flip($direction)
	{
		$image = Image::factory($this->_server_path('original'));
		$image->flip($direction);
		$image->save($this->_server_path('original'));

		$this->timestamp = time();

		return $this->resize()->update();
	}

	public function delete()
	{
		//$this->gallery->dec();
		$this->unlink_images($this->type);
		return parent::delete();
	}

	public function unlink_images($type = NULL)
	{
        /*$type = ! $type ? 'default' : $type;
        $params = static::config($type);
        foreach ($params['size'] as $index => $size)
		{
			@unlink($this->_server_path($size['name']));
		}*/
        // Или удалить папку что правильней
        FileHelper::removeDirectory($this->_server_path());
	}

	public function order_update($gallery, $data)
	{
		$sql = "SELECT id, `order` FROM ".$this->_table_name." WHERE gallery_id = ".$gallery->id." ORDER BY `order`";
		$items = DB::query(Database::SELECT, $sql)->execute($this->_db)->as_array('id');

		$order = 1;
		$sql = "UPDATE ".$this->_table_name." SET `order` = :order WHERE id = :id";
		$query = DB::query(Database::UPDATE, $sql)->bind(':order', $order)->bind(':id', $id);
		foreach ($data as $id)
		{
			if ($items[$id]['order'] != $order)
			{
				$query->execute($this->_db);
			}
			$order ++;
		}
	}
    
    public function getUploadDir($is_server = FALSE){
        if(! $is_server){
            return '@webroot/uploads/';
        }else{
            return Yii::getAlias('@webroot/uploads/');       
        }
    }

	public function url($size = 'original')
	{
		return '@web/uploads/'.$this->path.'/'.$size.'.'.$this->ext.'?t='.$this->timestamp;
	}

	public function render($size = 'original', array $attributes = NULL)
	{
		if (! $attributes)
		{
			$attributes['alt'] = $this->alt;

		}
        return Html::img($this->url($size, FALSE),$attributes);
		/*return HTML::image('/upload/gallery/default/'.$size.'.'.$this->ext);*/
	}

	protected function _server_path($size = NULL, $is_server = TRUE)
	{
        return $this->getUploadDir($is_server).$this->path.($size ? '/'.$size.'.'.$this->ext : '');
	}
    
    protected static function config($type = 'default'){
        $config = [ 
                'default' => [
                         'small' => ['name' => 'small', 'width' => 400, 'height' => 200, 'quality' => 90],
                         'avatar' => ['name' => 'avatar', 'width' => 100, 'height' => 100, 'quality' => 90],
                         ]
        ];
        if(empty($type)){
            $type = 'default';
        }
        $_config = ArrayHelper::getValue($config, $type);
        if(empty($_config)){
            $_config = ArrayHelper::getValue($config, 'default');    
        }
        return $_config;
    }
    
    public static function random($type = NULL, $length = 8)
	{
		if ($type === NULL)
		{
			// Default is to generate an alphanumeric string
			$type = 'alnum';
		}

		$utf8 = FALSE;

		switch ($type)
		{
			case 'alnum':
				$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			case 'alpha':
				$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			case 'hexdec':
				$pool = '0123456789abcdef';
			break;
			case 'numeric':
				$pool = '0123456789';
			break;
			case 'nozero':
				$pool = '123456789';
			break;
			case 'distinct':
				$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
			break;
			default:
				$pool = (string) $type;
				$utf8 = ! UTF8::is_ascii($pool);
			break;
		}

		// Split the pool into an array of characters
		$pool = ($utf8 === TRUE) ? UTF8::str_split($pool, 1) : str_split($pool, 1);

		// Largest pool key
		$max = count($pool) - 1;

		$str = '';
		for ($i = 0; $i < $length; $i++)
		{
			// Select a random character from the pool and add it to the string
			$str .= $pool[mt_rand(0, $max)];
		}

		// Make sure alnum strings contain at least one letter and one digit
		if ($type === 'alnum' AND $length > 1)
		{
			if (ctype_alpha($str))
			{
				// Add a random digit
				$str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
			}
			elseif (ctype_digit($str))
			{
				// Add a random letter
				$str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
			}
		}

		return $str;
	}
}
