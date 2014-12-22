Cabinet - Laravel 4 File Upload Package 
=====================

Cabinet is a file upload package for Laravel 4.

![Cabinet Poster](http://i.imgur.com/y7YXeVQ.png)

Cabinet is a package that allows easy upload of files and images.

## Features

* File Upload
* Image Processing for display
* Configurable Image options
* Route, Controller, Model cli generators
* [Configurable](#config)

## Quick start

### Required setup

In the `require` key of `composer.json` file add the following:

For use with Laravel 4.1.x

    "andrewelkins/cabinet": "1.0.x"

For use with Laravel 4.2.x

    "andrewelkins/cabinet": "1.1.x"

Run the Composer update command

    $ composer update

In your `config/app.php` add `'Andrew13\Cabinet\CabinetServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Andrew13\Cabinet\CabinetServiceProvider',

    ),

At the end of `config/app.php` add `'Cabinet'    => 'Andrew13\Cabinet\CabinetFacade'` to the `$aliases` array

    'aliases' => array(

        'App'        => 'Illuminate\Support\Facades\App',
        'Artisan'    => 'Illuminate\Support\Facades\Artisan',
        ...
        'Cabinet'    => 'Andrew13\Cabinet\CabinetFacade',

    ),


### Upload model

Now generate the Cabinet migration:

    $ php artisan cabinet:migration

It will generate the `<timestamp>_cabinet_setup_uploads_table.php` migration. You may now run it with the artisan migrate command:

    $ php artisan migrate

It will setup a table containing `filename`, `directory_path`, `extension`, `user_id` and `deleted_at` fields, which are the default fields needed for Cabinet use.

Create an upload model in `app/models/Upload.php`:

    <?php

    use Andrew13\Cabinet\CabinetUpload;

    class Upload extends CabinetUpload {

        protected $softDelete = true;

    }

`CabinetUpload` class will take care of all the default upload behavior. This can be extended in your Upload model.


### Dump the default assessors

Least, you can dump a default controller and the default routes for Cabinet.

    $ php artisan cabinet:controller
    $ php artisan cabinet:routes

Don't forget to dump composer autoload

    $ composer dump-autoload


### Publish public assets

In order to use the js/css/images you'll need to publish the assets.

```
php artisan asset:publish andrew13/cabinet
```

**And you are ready to go.**
Access `http://localhost/upload` to upload a file. It is highly suggested to put some auth protection on the uploads.


### Advanced

#### Using custom table / model name

To change the controller name when dumping the default controller template you can use the --name option.

    $ php artisan cabinet:controller --name Uploader

Will result in `UploaderController`

Then, when dumping the routes, you should use the --controller option to match the existing controller.

    $ php artisan confide:routes --controller Uploader


<a name="config"></a>
#### Using custom form for upload and other configuration options.

First, publish the config files:

    $ php artisan config:publish andrew13/cabinet

Then edit the view names in `app/config/packages/andrew13/confide/config.php`.

Further, you can change the location of the uploads, type of upload files, and many more options!

#### RESTful controller

If you want to generate a [RESTful controller](https://github.com/laravel/docs/blob/master/controllers.md#restful-controllers) you can use the additional `--restful` or `-r` option.

    $ php artisan cabinet:controller --restful

Will result in a [RESTful controller](https://github.com/laravel/docs/blob/master/controllers.md#restful-controllers)

Then, when dumping the routes, you should use the --restful option to match the existing controller.

    $ php artisan cabinet:routes --restful


-----
## License

This is free software distributed under the terms of the MIT license


## Special Thanks

Generator code uses code from the [Confide](https://github.com/Zizaco/confide) package.
It is then modified to fit this application. Thanks goes out to @Zizaco for that code.

Workbench [tutorial](http://jasonlewis.me/article/laravel-4-develop-packages-using-the-workbench)
by Jason Lewis was excellent in getting up and running with workbench.

Image upload tutorial from [Phil Sturgeon](http://philsturgeon.co.uk/blog/2012/09/package-watch-image-management) which inspired me to create this package.


## Additional information

Any questions, feel free to [contact me](http://twitter.com/andrewelkins).
