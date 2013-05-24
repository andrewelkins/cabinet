<?php namespace Andrew13\Cabinet;

use Illuminate\Support\Facades\Facade;

class CabinetFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'cabinet'; }

}
