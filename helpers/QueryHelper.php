<?php


namespace carono\yii2widgets\helpers;


use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Query;

class QueryHelper
{
    /**
     * @param ActiveRecord $model
     * @param Query $query
     * @param null $alias
     */
    public static function regular($model, $query, $alias = null)
    {
        /**
         * @var ActiveRecord $class
         */
        $alias = $alias ? $alias : $model::tableName();
        $class = get_class($model);
        foreach ($model->safeAttributes() as $attribute) {
            if ($column = \Yii::$app->db->getTableSchema($class::tableName())->getColumn($attribute)) {
                $value = $model->getAttribute($attribute);
                if ($column->type == 'text' || $column->type == 'string') {
                    $query->andFilterWhere(['ilike', "[[$alias]].[[$attribute]]", $value]);
                } else {
                    $query->andFilterWhere(["[[$alias]].[[$attribute]]" => $value]);
                }
            }
        }
    }
}