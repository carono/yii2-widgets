<?php


namespace carono\yii2widgets;


use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@vendor/carono/yii2-widgets/assets';
    public $js = ['phones.js'];

    public $depends = [
        'carono\yii2widgets\BowerInputmaskMultiAsset',
        'carono\yii2widgets\BowerJqueryMaskAsset'
    ];
}