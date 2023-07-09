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
    public $contentInIcon = false;

    public function asLink()
    {
        $span = Html::tag('span', $this->contentInIcon ? $this->content : '', ["class" => $this->icon]);
        $this->options["title"] = $this->title;
        return Html::a($span . (!$this->contentInIcon ? ($this->content ? '&nbsp;' . $this->content : '') : ''), $this->url, $this->options);
    }

    public function __toString(): string
    {
        return $this->asLink();
    }
}