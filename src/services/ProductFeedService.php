<?php

namespace app\services;

use app\models\Product;
use app\models\ProductCategory;
use app\models\ProductHasProductCategory;
use Exception;
use Yii;
use yii\web\UploadedFile;

class ProductFeedService
{   

    /**
     * 
     * @param CSVUploadForm
     * 
     * @return bool
     */
    public static function processCSVProductUpdate($form_model)
    {
        $csv_upload = UploadedFile::getInstance($form_model, "csv_file");
        $file = fopen($csv_upload->tempName, 'r');
        $upload_data = [];

        fgetcsv($file, null, ',');
        while($line = fgetcsv($file, null, ',')) {

            $categoryes = [];
            for($i = 2; $i < count($line); ++$i) {
                $categoryes[] = trim($line[$i], '"');
            }
            $upload_data[$line[0]] = [
                'name' => $line[0],
                'price' => $line[1],
                'categoryes' => $categoryes,
            ];
        }

        $existing_product_categoryes = ProductCategory::find()->indexBy('id')->all(); 
        $product_categoryes_by_name = array_flip(array_map(fn($item) => $item->name, $existing_product_categoryes));

        $existing_products_query = Product::find()->with('productHasProductCategoryes');

        foreach($existing_products_query->batch(1000) as $products) {
            foreach($products as $product) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if(isset($upload_data[$product->name])) {
                        $product->price = $upload_data[$product->name]['price'];
                        if(!$product->save()) {
                            throw new Exception('Failed to save Product: '.$product->name.' - '.$product->errors);
                        }
                        $categoryes_left = $upload_data[$product->name]['categoryes'];
                        foreach($product->productHasProductCategoryes as $product_has_category) {
                            if(in_array($existing_product_categoryes[$product_has_category->product_category_id], $upload_data[$product->name]['categoryes'])) {
                                unset($categoryes_left[array_search($existing_product_categoryes[$product_has_category->product_category_id]->name, $categoryes_left)]);
                            } else {
                                $product_has_category->delete();
                            }   
                        }
                        foreach($categoryes_left as $category_name) {
                            if(isset($product_categoryes_by_name[$category_name])) {
                                $category_id = $product_categoryes_by_name[$category_name];
                            } else {
                                $new_category = new ProductCategory(['name' => $category_name]);
                                if(!$new_category->save()) {
                                    throw new Exception('Failed to save ProductCategory: '.$category_name.' - '.$new_category->errors);
                                }
                                $category_id = $new_category->id;
                                $existing_product_categoryes[$category_id] = $new_category;
                            }
                            $product_has_category = new ProductHasProductCategory(['product_id' => $product->id, 'product_category_id' => $category_id]);
                            if(!$product_has_category->save()) {
                                throw new Exception('Failed to save ProductHasProductCategory: '.$category_name.' - '.$product_has_category->errors);
                            }

                        }
                        $transaction->commit();
                        unset($upload_data[$product->name]);
                    }
                } catch(Exception $e) {
                    $transaction->rollBack();
                    Yii::error(__METHOD__.' - '.$e->getMessage().PHP_EOL.' '.print_r($product->name, true));
                    return false;
                }
            }
        }

        $product_categoryes_by_name = array_flip(array_map(fn($item) => $item->name, $existing_product_categoryes));

        foreach($upload_data as $data_row) {
            try {
                $product = new Product(['name' => $data_row['name'], 'price' => $data_row['price']]);
                $product->save();
                foreach($data_row['categoryes'] as $category_name) {
                    if(isset($product_categoryes_by_name[$category_name])) {
                        $category_id = $product_categoryes_by_name[$category_name];
                    } else {
                        $new_category = new ProductCategory(['name' => $category_name]);
                        if(!$new_category->save()) {
                            throw new Exception('Failed to save ProductCategory: '.$category_name.' - '.$new_category->errors);
                        }
                        $category_id = $new_category->id;
                        $product_categoryes_by_name[$category_name] = $category_id;
                    }
                    $product_has_category = new ProductHasProductCategory(['product_id' => $product->id, 'product_category_id' => $category_id]);
                    if(!$product_has_category->save()) {
                        throw new Exception('Failed to save ProductHasProductCategory: '.$category_name.' - '.$product_has_category->errors);
                    }
                }
            } catch(Exception $e) {
                Yii::error(__METHOD__.' - '.$e->getMessage().PHP_EOL.' '.print_r($data_row, true));
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public static function createExportData()
    {
        $data = '';
        $products_query = Product::find()->with('productCategoryes');

        foreach($products_query->batch(1000) as $products) {
            foreach($products as $product) {
                $categoryes = '';
                foreach($product->productCategoryes as $category) {
                    $categoryes .= ',"'.$category->name.'"';
                }
                $data.= $product->name.','.$product->price.$categoryes."\n";
            }
        }
        return $data;
    }


}
