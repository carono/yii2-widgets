<?php


namespace carono\yii2widgets\helpers; 


use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Query;

class QueryHelper
{
    /**
     * @param ActiveRecord $model
     * @param Query        $query
     */
    public static function regular($model, $query)
    {
        /**
         * @var ActiveRecord $class
         */
        $class = get_class($model);
        foreach ($model->safeAttributes() as $attribute) {
            if ($column = \Yii::$app->db->getTableSchema($class::tableName())->getColumn($attribute)) {
                $value = $model->getAttribute($attribute);
                if ($column->type == 'text' || $column->type == 'string') {
                    $query->andFilterWhere(['ilike', $attribute, $value]);
                } else {
                    $query->andFilterWhere([$attribute => $value]);
                }
            }
        }
    }
}