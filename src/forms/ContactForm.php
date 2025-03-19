<?php

namespace app\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public string $email = '';
    public string $phone = '';
    public string $name = '';
    public string $message = '';

    public function rules(): array
    {
        return [
            [['email', 'message'], 'required'],
            ['email', 'email'],
            ['phone', 'string'],
            ['name', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'name' => 'Имя',
            'message' => 'Сообщение',
        ];
    }

    public function contact($email): bool
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
