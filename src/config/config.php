<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | The default views that the file uploader will use.
    |
    */

    'upload_form' => 'cabinet::upload',
    'upload_list' => 'cabinet::upload_list',


    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | Model the Uploader will use.
    | Default : Upload
    |
    */

    'upload_model' => 'Upload',


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    |
    | Table the Uploader will use.
    | Default : uploads
    |
    */

    'upload_table' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Upload folder
    |--------------------------------------------------------------------------
    |
    | Folder the Uploader will use.
    | This will need to writable by the web server.
    | Default : public/packages/andrew13/uploads
    | Recommendation: public/uploads
    |
    */

    'upload_folder' => 'public/packages/andrew13/uploads',

);
