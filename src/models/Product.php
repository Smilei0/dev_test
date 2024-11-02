<?php

namespace app\models;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property int|null $price
 *
 * @property ProductCategory[] $productCategoryes
 */
class Product extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
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
    public function getProductCategoryes()
    {
        return $this->hasMany(ProductCategory::class, ['id' => 'product_category_id'])->viaTable('product_has_product_category', ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductHasProductCategoryes()
    {
        return $this->hasMany(ProductHasProductCategory::class, ['product_id' => 'id']);
    }

}
