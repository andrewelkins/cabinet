<?php namespace Andrew13\Cabinet;

use LaravelBook\Ardent\Ardent;
use Andrew13\Cabinet\CabinetUploadHandler;

class CabinetUpload extends Ardent
{

    protected $handler;

    protected $options;

    protected $errorMessages;

    protected $app;

    public function __construct( array $attributes = array(), CabinetUploadHandler $handler=null ) {
        parent::__construct( $attributes );

        if ( ! static::$app )
            static::$app = app();

        if (is_null($handler)) {
            $this->options = static::$app['lang']->get('cabinet.options');
            $this->errorMessages = static::$app['config']->get('cabinet.error_messages');

            $this->handler = new CabinetUploadHandler($this->options, true, $this->errorMessages);
        } else {
            $this->handler = $handler;
        }
    }



}