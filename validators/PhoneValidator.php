<?php


namespace carono\yii2widgets\validators;


use carono\yii2helpers\PhoneHelper;
use yii\validators\Validator;

class PhoneValidator extends Validator
{
    public $allowEmpty = true;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
//            $this->message = \Yii::t('errors', 'Wrong phone format');
            $this->message = 'Wrong phone format';
        }
    }

    protected function validateValue($value)
    {
        $normalize = PhoneHelper::normalNumber($value);
        if ($this->allowEmpty && !$normalize) {
            return null;
        }
        if (!$normalize) {
            return [$this->message, []];
        }
        return null;
    }
}