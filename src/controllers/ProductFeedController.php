<?php

namespace app\controllers;

use app\services\ProductFeedService;
use yii\web\Controller;

class ProductFeedController extends Controller
{

    /**
     * @return File
     */
    public function actionExport()
    {
        $csv_data = ProductFeedService::createExportData();
        return \Yii::$app->response->sendContentAsFile($csv_data, 'product_datas.csv', [
            'mimeType' => 'application/csv', 
            'inline'   => false
        ]);
    }

}
