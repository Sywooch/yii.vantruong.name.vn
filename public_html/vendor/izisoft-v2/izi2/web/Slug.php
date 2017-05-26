<?php
namespace izi\web;

class Slugs extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return '{{%slugs}}';
	}
	
}
