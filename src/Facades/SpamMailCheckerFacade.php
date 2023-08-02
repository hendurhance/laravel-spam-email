<?php

namespace Martian\SpamMailChecker\Facades;

use Illuminate\Support\Facades\Facade;

class SpamMailCheckerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This method returns the key used to resolve the service from the Laravel container.
     * In this case, it returns 'spammailchecker', which is the service container binding key
     * for the `SpamMailChecker` class.
     *
     * @see \Martian\SpamMailChecker\SpamMailChecker
     * 
     * @method static bool validate(string $email)
     * 
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'spammailchecker';
    }
}
