<?php


namespace carono\yii2widgets\validators;


use carono\yii2widgets\helpers\PhoneHelper;
use yii\validators\Validator;

class PhoneValidator extends Validator
{
    public $allowEmpty = true;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = \Yii::t('errors', 'Wrong phone format');
        }
    }

    protected function validateValue($value)
    {
        if ($this->allowEmpty && !$value) {
            return null;
        }
        if (!PhoneHelper::normalNumber($value)) {
            return [$this->message, []];
        }
        return null;
    }
}