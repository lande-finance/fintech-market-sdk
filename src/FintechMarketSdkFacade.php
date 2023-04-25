<?php

namespace Hashstudio\FintechMarketSdk;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hashstdio\FintechMarketSdk\Skeleton\SkeletonClass
 */
class FintechMarketSdkFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fintech-market-sdk';
    }
}
