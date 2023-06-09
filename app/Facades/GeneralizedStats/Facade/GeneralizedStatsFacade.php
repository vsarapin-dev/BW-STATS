<?php


namespace App\Facades\GeneralizedStats\Facade;


use Illuminate\Support\Facades\Facade;

class GeneralizedStatsFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'generalizedStats';
    }
}
