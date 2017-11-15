<?php

namespace N1215\LaraTodo\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'N1215\LaraTodo\Events\Event' => [
            'N1215\LaraTodo\Listeners\EventListener',
        ],
    ];
}
