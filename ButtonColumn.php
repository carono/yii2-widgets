<?php

namespace carono\yii2widgets;

use yii\helpers\Html;

class ButtonColumn
{
    public $icon;
    public $title;
    public $url;
	public $content;
    public $options = [];

    public function asLink()
    {
        $span = Html::tag('span', $this->content, ["class" => $this->icon]);
        $this->options["title"] = $this->title;
        return Html::a($span, $this->url, $this->options);
    }
}