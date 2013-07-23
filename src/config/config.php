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
    | Upload Folder
    |--------------------------------------------------------------------------
    |
    | Folder the Uploader will use.
    | This will need to writable by the web server.
    | Default : public/packages/andrew13/cabinet/uploads/
    | Recommendation: public/uploads/
    |
    */

    'upload_folder' => 'public/packages/andrew13/cabinet/uploads/',
    'upload_folder_public_path' => 'assets/uploads/',
    'upload_folder_permission_value' => 0777, // Default 0777 - Other likely values 0775, 0755


    /*
    |--------------------------------------------------------------------------
    | Upload Files
    |--------------------------------------------------------------------------
    |
    | Configuration items for uploaded files.
    |
    */

    'upload_file_types' => array('image/png','image/gif','image/jpg','image/jpeg'),
    'upload_file_extensions' => array('png','gif','jpg','jpeg'), // Case insensitive

    // Max upload size - In BYTES. 1GB = 1073741824 bytes, 10 MB = 10485760, 1 MB = 1048576
    'max_upload_file_size' => 10485760, // Converter - http://www.beesky.com/newsite/bit_byte.htm

    // [True] will change all uploaded file names to an obfuscated name. (Example_Image.jpg becomes Example_Image_p4n8wfnt8nwh5gc7ynwn8gtu4se8u.jpg)
    // [False] attempts to leaves the filename as is.
    'obfuscate_filenames' => false, // True/False


);
