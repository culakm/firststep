<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CounterFacade extends Facade
{
    /**
     * A Facade to Contract
     * @method static int increment(string $key, array $tags = null)
     */
    public static function getFacadeAccessor()
    {
        // cez app/Providers/AppServiceProvider.php namapuje Facade na Contract
        return 'App\Contracts\CounterContract';
    }
}