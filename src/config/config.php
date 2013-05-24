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


    /*
    |--------------------------------------------------------------------------
    | Authentication Model
    |--------------------------------------------------------------------------
    |
    | Specifies what Model you would like to use for uploading.
    |
    */

    'model' => 'Upload',

    'options' => array(
        'script_url' => '/',
        'upload_dir' => '/files/',
        'upload_url' => '/files/',
        'user_dirs' => false,
        'mkdir_mode' => 0755,
        'param_name' => 'files',
        // Set the following option to 'POST', if your server does not support
        // DELETE requests. This is a parameter sent to the client:
        'delete_type' => 'DELETE',
        'access_control_allow_origin' => '*',
        'access_control_allow_credentials' => false,
        'access_control_allow_methods' => array(
            'OPTIONS',
            'HEAD',
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE'
        ),
        'access_control_allow_headers' => array(
            'Content-Type',
            'Content-Range',
            'Content-Disposition'
        ),
        // Enable to provide file downloads via GET requests to the PHP script:
        'download_via_php' => false,
        // Defines which files can be displayed inline when downloaded:
        'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
        // Defines which files (based on their names) are accepted for upload:
        'accept_file_types' => '/.+$/i',
        // The php.ini settings upload_max_filesize and post_max_size
        // take precedence over the following max_file_size setting:
        'max_file_size' => null,
        'min_file_size' => 1,
        // The maximum number of files for the upload directory:
        'max_number_of_files' => null,
        // Image resolution restrictions:
        'max_width' => null,
        'max_height' => null,
        'min_width' => 1,
        'min_height' => 1,
        // Set the following option to false to enable resumable uploads:
        'discard_aborted_uploads' => true,
        // Set to true to rotate images based on EXIF meta data, if available:
        'orient_image' => false,
        'image_versions' => array(
            // Uncomment the following version to restrict the size of
            // uploaded images:
            /*
            '' => array(
                'max_width' => 1920,
                'max_height' => 1200,
                'jpeg_quality' => 95
            ),
            */
            // Uncomment the following to create medium sized images:
            /*
            'medium' => array(
                'max_width' => 800,
                'max_height' => 600,
                'jpeg_quality' => 80
            ),
            */
            'thumbnail' => array(
                // Uncomment the following to force the max
                // dimensions and e.g. create square thumbnails:
                //'crop' => true,
                'max_width' => 80,
                'max_height' => 80
            )
        )
    ),
    'error_messages' => array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height'
    )
);
