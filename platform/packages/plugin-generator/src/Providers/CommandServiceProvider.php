<?php

namespace Botble\PluginGenerator\Providers;

use Botble\PluginGenerator\Commands\PluginCreateCommand;
use Botble\PluginGenerator\Commands\PluginMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginCreateCommand::class,
                PluginMakeCrudCommand::class,
            ]);
        }
    }
}
