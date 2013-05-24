<?php namespace Andrew13\Cabinet;

use LaravelBook\Ardent\Ardent;
use Andrew13\Cabinet\CabinetUploadHandler;
use Illuminate\Support\Facades\Lang;

class CabinetUpload extends Ardent
{

    protected $handler;

    protected $options;

    protected $errorMessages;

    public static $app;

    public function __construct() {
        parent::__construct();

        if ( ! static::$app )
            static::$app = app();

        if (is_null($this->handler)) {
//            $this->options = static::$app['config']->get('cabinet.options');
//            $this->errorMessages = static::$app['config']->get('cabinet.error_messages');

//            $this->handler = new CabinetUploadHandler();
        }
    }

    public function getHandlerFunction($method)
    {
        return $this->handler->$method;
    }

}