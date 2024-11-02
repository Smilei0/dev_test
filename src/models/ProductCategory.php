<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property string $name
 *
 * @property Product[] $products
 */
class ProductCategory extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('product_has_product_category', ['product_category_id' => 'id']);
    }

}
