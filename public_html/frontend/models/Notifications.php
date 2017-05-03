<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $sid
 * @property string $title
 * @property string $link
 * @property integer $state
 * @property string $time
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'state'], 'integer'],
            [['title', 'link'], 'required'],
            [['time'], 'safe'],
            [['title', 'link'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'title' => 'Title',
            'link' => 'Link',
            'state' => 'State',
            'time' => 'Time',
        ];
    }
    
    public static function insertNotification($f = [],$multiple = false){
    	if($multiple && !empty($f)){    		 
    		foreach ($f as $data){
    			$data['sid'] = isset($data['sid']) ? $data['sid'] : __SID__;
    			if($data['title'] != ""){
    				return Yii::$app->db->createCommand()->insert(self::tableName(),$data)->execute();
    			}
    		}
    		 
    	}elseif(!empty($f)){
	    	$f['sid'] = isset($f['sid']) ? $f['sid'] : __SID__;
	    	if($f['title'] != ""){
	    		return Yii::$app->db->createCommand()->insert(self::tableName(),$f)->execute();
	    	}
	    	
    	}
    }
}
