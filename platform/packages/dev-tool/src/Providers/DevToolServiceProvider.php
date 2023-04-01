<?php

namespace Botble\DevTool\Providers;

use Illuminate\Support\ServiceProvider;

class DevToolServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
