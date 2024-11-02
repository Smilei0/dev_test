<?php

/** @var yii\web\View $this */
/** @var app\models\forms\CSVUploadForm $form_model */

use app\models\forms\CSVUploadForm;
use app\widgets\Alert;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Product xml import/export';
?>
<div class="site-index">
    <?= Alert::widget() ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-wrap" >
        <?= $form->field($form_model, 'csv_file', ['options' => ['class' => 'form-group mb-1']])->widget(FileInput::class, [
            'pluginOptions' => [
                'allowedFileExtensions' => CSVUploadForm::ALLOWED_EXTENSIONS,
                'maxFileSize'=> CSVUploadForm::ALLOWED_MIME_TYPES,
                'maxFileCount' => 1,
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false
            ]
        ]) ?>

        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary submit-btn']) ?>
    </div>

    <div class="justify-content-center d-flex">
        <?= Html::a('Download products csv', ['product-feed/export'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
