<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $full_name;
    public $phone;
    public $address;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['full_name', 'email', 'phone', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        	//	['email', 'label'=>'ss'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => getTextTranslate(226),
        		'full_name' => getTextTranslate(136),
        		'email' => getTextTranslate(137) .' email',
        		'phone' => getTextTranslate(138),
        		'address' => getTextTranslate(137),
        		'body'=>getTextTranslate(225),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->full_name])
            ->setSubject('New email')
            ->setTextBody($this->body)
            ->send();
    }
}
