<?php

namespace Martian\SpamMailChecker\Facades;

use Illuminate\Support\Facades\Facade;

class SpamMailCheckerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'spammailchecker';
    }
}
