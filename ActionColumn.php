<?php

namespace carono\yii2widgets;

use carono\yii2rbac\RoleManager;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $text;
    public $checkUrlAccess = false;
    public $visibleButton;

    public function init()
    {
        parent::init();
        $methods = get_class_methods($this);
        preg_match_all('/button(\w+)/', join(" ", $methods), $m);
        foreach ($m[1] as $button) {
            $this->buttons[lcfirst($button)] = function ($url, $model, $key) use ($button) {
                return call_user_func_array([$this, "button" . $button], [$url, $model, $key, lcfirst($button)]);
            };
        }
    }

    /**
     * @param $url
     * @param $model
     * @param $key
     * @param $action
     * @return string
     */
    public function getDefaultButton($url, $model, $key, $action)
    {
        $title = Yii::t('buttons', $action);
        $button = new ButtonColumn();
        $button->icon = "";
        $button->title = $title;
        $button->content = $this->inDropdown("{$action}") ? $title : '';
        $button->options = $this->inDropdown("{$action}") ? ['class' => 'dropdown-item'] : ['class' => 'btn btn-sm font-sm rounded btn-brand mr-5'];
        $button->url = $url;
        return $button->asLink();
    }


    /**
     * @param $url
     * @param $model
     * @param $key
     * @return ButtonColumn
     */
    public function buttonUpload($url, $model, $key, $action)
    {
        $button = new ButtonColumn();
        $button->icon = "glyphicon glyphicon-upload";
        $button->title = "Загрузить";
        $button->url = $url;
        $button->options = $this->buttonOptions;
        return $button;
    }

    /**
     * @param $model
     * @param $key
     * @param $index
     * @return array|string|string[]|null
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $action = $matches[1];
            $name = lcfirst(Inflector::camelize(strtr($matches[1], ['/' => '_', '\\' => '_'])));

            if ($this->isButtonVisible($name, $model, $key, $index) || $this->urlFromModel($model, $action)) {
                $isVisible = $this->getButtonVisibility($name, $model, $key, $index);
                $url = $this->createUrl($action, $model, $key, $index);

                if ($isVisible && (!$this->checkUrlAccess || RoleManager::checkAccessByUrl($url))) {
                    return $this->getButtonContent($name, $action, $url, $model, $key);
                }
            }

            return '';
        }, $this->template);
    }

    /**
     * @param $name
     * @param $model
     * @param $key
     * @param $index
     * @return bool
     */
    protected function isButtonVisible($name, $model, $key, $index)
    {
        return isset($this->buttons[$name]) && $this->buttonIsVisible($name, $model, $key, $index);
    }

    /**
     * @param $name
     * @param $model
     * @param $key
     * @param $index
     * @return bool|mixed
     */
    protected function getButtonVisibility($name, $model, $key, $index)
    {
        if (isset($this->visibleButtons[$name])) {
            $isVisible = $this->visibleButtons[$name] instanceof \Closure
                ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                : $this->visibleButtons[$name];
        } else {
            $isVisible = true;
        }

        return $isVisible;
    }

    /**
     * @param $name
     * @param $action
     * @param $url
     * @param $model
     * @param $key
     * @return mixed
     */
    protected function getButtonContent($name, $action, $url, $model, $key)
    {
        $button = $this->buttons[$name] ?? [$this, 'getDefaultButton'];
        return call_user_func($button, $url, $model, $key, $action);
    }

    /**
     * @param $name
     * @param $model
     * @param $key
     * @param $index
     * @return bool|mixed
     */
    public function buttonIsVisible($name, $model, $key, $index)
    {
        if ($this->visibleButton) {
            return call_user_func($this->visibleButton, $name, $model, $key, $index);
        } else {
            return true;
        }
    }

    /**
     * @param $model
     * @param $class
     * @return bool
     */
    public static function haveBehaviour($model, $class)
    {
        if (!method_exists($model, 'behaviors')) {
            return false;
        }
        foreach ($model->behaviors() as $name => $behavior) {
            if (is_array($behavior)) {
                $name = ArrayHelper::getValue($behavior, 'class');
            } else {
                $name = $behavior;
            }
            if (ltrim($name, '\\') == ltrim($class, '\\')) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $model
     * @param $action
     * @return mixed|null
     */
    protected function urlFromModel($model, $action)
    {
        if (method_exists($model, 'getUrl') || self::haveBehaviour($model, 'carono\yii2behaviors\UrlBehavior')) {
            return call_user_func([$model, 'getUrl'], $action);
        } else {
            return null;
        }
    }

    /**
     * @param string $action
     * @param \yii\db\ActiveRecordInterface $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (!$this->urlCreator && ($url = $this->urlFromModel($model, $action))) {
            return $url;
        } else {
            return parent::createUrl($action, $model, $key, $index);
        }
    }
}