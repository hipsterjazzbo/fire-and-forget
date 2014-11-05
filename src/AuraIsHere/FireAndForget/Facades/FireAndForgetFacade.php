<?php namespace AuraIsHere\FireAndForget\Facades;

use Illuminate\Support\Facades\Facade;

class FireAndForgetFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fire-and-forget';
    }
}
