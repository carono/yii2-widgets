<?php


namespace carono\yii2widgets\helpers;


use yii\helpers\Json;

class PhoneHelper
{
    public static function getCode($number)
    {
        $number = self::clear($number);
        $codes = Json::decode(file_get_contents(\Yii::getAlias('@vendor/carono/yii2-widgets/assets/phone-codes.json')));
        foreach ($codes as $code) {
            $mask = preg_replace('/[\(\)\-\+]/x', '', $code['mask']);
            $mask = preg_replace('/[#]/x', '[0-9]', $mask);
            if (preg_match("/^$mask$/", $number)) {
                return $code;
            }
        }
        return null;
    }

    public static function normalNumber($number)
    {
        if (self::getCode($number)) {
            return self::clear($number);
        } else {
            return null;
        }
    }

    protected static function clear($number)
    {
        return preg_replace('/[^0-9]/x', '', trim($number));
    }

    public static function asString($number)
    {
        if ($code = self::getCode($number)) {
            $number = self::normalNumber($number);
            $result = '';
            $i = 0;
            foreach (str_split($code['mask']) as $letter) {
                if (is_numeric($letter) || $letter == '#') {
                    $result .= $number[$i];
                    $i++;
                } else {
                    $result .= $letter;
                }
            }
            return $result;
        } else {
            return $number;
        }
    }
}