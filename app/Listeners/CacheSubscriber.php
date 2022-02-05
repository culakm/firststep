<?php

namespace App\Listeners;

use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Facades\Log;

class CacheSubscriber
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleCacheHit(CacheHit $event)
    {
        Log::info("{$event->key} cache hit");
    }

    public function handleCacheMissed(CacheMissed $event)
    {
        Log::info("{$event->key} cache miss");
    }

    // toto je ako funkcia handle pri listeneri
    public function subscribe($events)
    {
        $events->listen(
            // podla triedy rozlisi funkciu
            CacheHit::class,
            CacheSubscriber::class.'@handleCacheHit'
        );

        $events->listen(
            CacheMissed::class,
            CacheSubscriber::class.'@handleCacheMissed'
        );
    }
}
