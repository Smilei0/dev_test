<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\CSVUploadForm;
use app\services\ProductFeedService;

class SiteController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $form = new CSVUploadForm();
        if ($form->load(Yii::$app->request->post())) {
            $errors = [];
            if($form->validate()) {
                if(ProductFeedService::processCSVProductUpdate($form)) {
                    Yii::$app->session->setFlash('success', 'Update successful!');
                    return $this->redirect(['index']);
                } else {
                    $errors[] = 'Update failed';
                }
            } else {
                foreach($form->errors as $error) {
                    $errors[] = $error;
                }
            }
            foreach($errors as $error) {
                Yii::$app->session->addFlash('error', $error);
            }
        }
        return $this->render('index', ['form_model' => $form]);
    }

}
