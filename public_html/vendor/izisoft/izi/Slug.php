<?php
namespace \izisotf\izi; 

class Slug extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return '{{%slugs}}';
	}
	
}
