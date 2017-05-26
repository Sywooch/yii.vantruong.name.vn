<?php

namespace app\modules\admin;
use Yii;
/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->view->theme = new \yii\base\Theme([
        		'pathMap' => ['@app/views' => '@app/themes/admin/views'],
        		'baseUrl' => '@web/themes/admin',
        ]);
        // custom initialization code goes here
    }
}
