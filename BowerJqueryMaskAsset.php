<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace carono\yii2widgets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BowerJqueryMaskAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.inputmask/dist';
    public $js = [
        'min/jquery.inputmask.bundle.min.js',
    ];
}