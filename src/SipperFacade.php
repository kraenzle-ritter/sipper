<?php

namespace KraenzleRitter\Sipper;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KraenzleRitter\Sipper\Skeleton\SkeletonClass
 */
class SipperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sipper';
    }
}
