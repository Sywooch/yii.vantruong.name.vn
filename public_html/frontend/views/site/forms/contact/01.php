 
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha; 
$this->title = defined('__CATEGORY_NAME__') ? __CATEGORY_NAME__ : getTextTranslate(2);
?>
<div class="site-contact container">
    <h1><?= Html::encode($this->title) ?></h1>

 

    <div class="row f12e">
        <div class="col-lg-6 col-sm-12 col-md-6">
            <?php $form = ActiveForm::begin(['id' => 'contact-form' ,
            		'action'=>'/contact',
            		'options'=>[
            		'onsubmit'=>'return ajaxSubmitForm(this);',
            		'data-action'=>'sajax'		
            ]]); ?>
                <?= $form->field($model, 'full_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'phone') ?>

                <?= $form->field($model, 'address') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-send"></i> '.getTextTranslate(142), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
<input type="hidden" name="action" value="send_contact_request">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6">
        <?php 
        $b = Yii::$app->zii->getBox('right_contact_text');
        if(!empty($b)){
        	echo uh($b['text'],2);
        }
        ?>
        
        </div>
    </div>

</div>
