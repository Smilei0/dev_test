<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_has_product_category".
 *
 * @property int $product_id
 * @property int $product_category_id
 *
 */
class ProductHasProductCategory extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_has_product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_category_id'], 'required'],
            [['product_id', 'product_category_id'], 'integer'],
        ];
    }

    /**
     * @return boolean
     */
    public function ifPropertyChanged($model, $attribute) {
        return $model->{$attribute} != $model->getOldAttribute($attribute);
    }

}
