<?php

namespace Botble\Region\Providers;

use Botble\Region\Models\Region;
use Illuminate\Support\ServiceProvider;
use Botble\Region\Repositories\Caches\RegionCacheDecorator;
use Botble\Region\Repositories\Eloquent\RegionRepository;
use Botble\Region\Repositories\Interfaces\RegionInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class RegionServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(RegionInterface::class, function () {
            return new RegionCacheDecorator(new RegionRepository(new Region));
        });

        $this->setNamespace('plugins/region')->loadHelpers();
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
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Region::class, [
                'name',
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-region',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/region::region.name',
                'icon'        => 'fa fa-list',
                'url'         => route('region.index'),
                'permissions' => ['region.index'],
            ]);
        });
    }
}
