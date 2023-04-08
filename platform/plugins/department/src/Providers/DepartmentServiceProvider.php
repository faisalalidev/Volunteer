<?php

namespace Botble\Department\Providers;

use Botble\Department\Models\Department;
use Illuminate\Support\ServiceProvider;
use Botble\Department\Repositories\Caches\DepartmentCacheDecorator;
use Botble\Department\Repositories\Eloquent\DepartmentRepository;
use Botble\Department\Repositories\Interfaces\DepartmentInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class DepartmentServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(DepartmentInterface::class, function () {
            return new DepartmentCacheDecorator(new DepartmentRepository(new Department));
        });

        $this->setNamespace('plugins/department')->loadHelpers();
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
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Department::class, [
                'name',
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-department',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/department::department.name',
                'icon'        => 'fa fa-list',
                'url'         => route('department.index'),
                'permissions' => ['department.index'],
            ]);
        });
    }
}
