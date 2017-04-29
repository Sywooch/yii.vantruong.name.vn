<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "templete_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $state
 * @property integer $is_active
 */
class TempleteCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'templete_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['state', 'is_active'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'state' => 'State',
            'is_active' => 'Active',
        ];
    }
    public static function getList($o=[]){
    	$type_id = isset($o['type_id']) ? $o['type_id'] : 0;
    	$module_id = isset($o['module_id']) ? $o['module_id'] : 0;
    	$l = static::find()
    	->select(['id', 'title','is_active',"(select count(1) from temp_to_modules where module_id=$module_id and temp_id=templete_category.id and type_id=$type_id) as module_id"])
    	->where(
    			['>','state',-2]
    			//
    			)
    			//->andWhere(['lang'=>ADMIN_LANG])
    			//->andWhere(['>=','rgt',CONTROLLER_RGT])
    	
    			//->andWhere(['not in','id',(new Query())->select('form_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])])
    	->orderBy(['title'=>SORT_ASC])
    	->asArray()->all();
    	return ['listItem'=>$l];
    }
}
