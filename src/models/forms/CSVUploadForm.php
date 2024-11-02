<?php

namespace app\models\forms;

class CSVUploadForm extends \yii\base\Model
{

    CONST ALLOWED_EXTENSIONS = ['csv'];
    CONST ALLOWED_MIME_TYPES = ['text/csv'];
    CONST MAX_FILESIZE = 5242880 ; // 5MB
    
    public $csv_file;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['csv_file', 'file', 'extensions' => static::ALLOWED_EXTENSIONS, 'mimeTypes' => static::ALLOWED_MIME_TYPES,'maxFiles' => 1, 'maxSize' => static::MAX_FILESIZE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'csv_file' => 'Product update csv file',
        ];
    }

}
