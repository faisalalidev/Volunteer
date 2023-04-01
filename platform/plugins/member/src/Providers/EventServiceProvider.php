<?php

namespace Botble\Member\Providers;

use Botble\Member\Listeners\UpdatedContentListener;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
    ];
}
