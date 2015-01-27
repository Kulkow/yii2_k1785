<?php

namespace frontend\models;

use Yii;
use yii\web\UploadedFile;


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
            [['type', 'path', 'alt'], 'required'],
            [['path', 'alt', 'hide'], 'string'],
            [['timestamp'], 'integer'],
            [['type'], 'string', 'max' => 255],
            [['file'], 'file','extensions' => 'jpg, png, jpeg, gif'],
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
    
    
    public function upload($file, $target = NULL, $type = NULL)
	{
        $this->file = $file; //UploadedFile::getInstance($model, 'file');
        if ($this->file && $this->file->validate()) {
            print_r($model->file);
            $this->path = 
            //$model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
        }else{
            $this->addError('password', 'Incorrect username or password.');
        }
        
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
        
        $config = Kohana::$config->load('image');
        $params = $config->get($type);
        if(empty($params)){
            $params = $config->get('default');    
        }
        if(! empty($params)){
    		foreach ($params['size'] as $index => $size)
    		{
                $this->_resize($size['name'], $size['width'], $size['height'], $size['quality']);
    		}
        }    
		return $this;
	}

	protected function _resize($size, $width, $height, $quality)
	{
		$image = Image::factory($this->_server_path('original'));
		if ( ! $height OR $image->width / $width < $image->height / $height)
		{
   		    $image->resize($width, $height, Image::WIDTH);
        }
   		else
   	    {
   			$image->resize($width, $height, Image::HEIGHT);
   	    }
   		if ($height)
   	    {
		    $image->crop($width, $height);
		}
        $image->save($this->_server_path($size), $quality);
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
        $config = Kohana::$config->load('image');
        $params = $config->get($type);
        if(empty($params)){
            $params = $config->get('default');    
        }
        foreach ($params['size'] as $index => $size)
		{
			@unlink($this->_server_path($size['name']));
		}
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

	public function url($size = 'image')
	{
		return '/upload/gallery/'.$this->path.'/'.$size.'.jpg?t='.$this->timestamp;
	}

	public function render($size = 'image', array $attributes = NULL)
	{
		if ($this->_loaded)
		{
			$attributes['alt'] = $this->alt;

			return HTML::image($this->url($size), $attributes);
		}

		return HTML::image('/upload/gallery/default/'.$size.'.jpg');
	}

	protected function _server_path($size = NULL)
	{
		return DOCROOT.'/upload/gallery/'.$this->path.($size ? '/'.$size.'.jpg' : '');
	}
    
}
