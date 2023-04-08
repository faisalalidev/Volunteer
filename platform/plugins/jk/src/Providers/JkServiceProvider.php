<?php

namespace Botble\Jk\Providers;

use Botble\Jk\Models\Jk;
use Illuminate\Support\ServiceProvider;
use Botble\Jk\Repositories\Caches\JkCacheDecorator;
use Botble\Jk\Repositories\Eloquent\JkRepository;
use Botble\Jk\Repositories\Interfaces\JkInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class JkServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(JkInterface::class, function () {
            return new JkCacheDecorator(new JkRepository(new Jk));
        });

        $this->setNamespace('plugins/jk')->loadHelpers();
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
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Jk::class, [
                'name',
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-jk',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/jk::jk.name',
                'icon'        => 'fa fa-list',
                'url'         => route('jk.index'),
                'permissions' => ['jk.index'],
            ]);
        });
    }
}
