<?php

namespace Botble\Location\Providers;

use Botble\Location\Models\Location;
use Illuminate\Support\ServiceProvider;
use Botble\Location\Repositories\Caches\LocationCacheDecorator;
use Botble\Location\Repositories\Eloquent\LocationRepository;
use Botble\Location\Repositories\Interfaces\LocationInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class LocationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(LocationInterface::class, function () {
            return new LocationCacheDecorator(new LocationRepository(new Location));
        });

        $this->setNamespace('plugins/location')->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Location::class, [
                'name',
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-location',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/location::location.name',
                'icon'        => 'fa fa-list',
                'url'         => route('location.index'),
                'permissions' => ['location.index'],
            ]);
        });
    }
}
