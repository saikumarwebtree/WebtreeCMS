<?php

namespace Webtree\WebtreeCms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Webtree\WebtreeCms\Core\Theme\ThemeManager;
use Webtree\WebtreeCms\Core\Support\RouteRegistry;
use Webtree\WebtreeCms\Core\Support\MenuBuilder;

class WebtreeCmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__ . '/Config/cms.php', 'cms'
        );

        // Register Theme Manager
        $this->app->singleton('theme', function ($app) {
            return new ThemeManager();
        });

        // Register Route Registry
        $this->app->singleton('cms.routes', function ($app) {
            return new RouteRegistry();
        });

        // Register Menu Builder
        $this->app->singleton('cms.menu', function ($app) {
            return new MenuBuilder();
        });

        // Register Custom Facades
        $this->app->bind('cms', function ($app) {
            return new \Webtree\WebtreeCms\Core\Support\CmsFacade();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerBladeDirectives();
        $this->registerMiddleware();
        $this->registerCommands();
        $this->registerViewComposers();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__ . '/Config/cms.php' => config_path('cms.php'),
            ], 'cms-config');

            // Assets
            $this->publishes([
                __DIR__ . '/Resources/assets' => public_path('vendor/cms'),
            ], 'cms-assets');

            // Views
            $this->publishes([
                __DIR__ . '/Resources/views' => resource_path('views/vendor/cms'),
            ], 'cms-views');

            // Migrations
            $this->publishes([
                __DIR__ . '/Database/Migrations' => database_path('migrations'),
            ], 'cms-migrations');

            // Seeds
            $this->publishes([
                __DIR__ . '/Database/Seeders' => database_path('seeders'),
            ], 'cms-seeds');
        }
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        // Admin Routes
        Route::middleware(['web', 'auth', 'cms.admin'])
            ->prefix(config('cms.admin_prefix', 'admin'))
            ->name('cms.admin.')
            ->group(__DIR__ . '/Routes/admin.php');

        // API Routes
        Route::middleware(['api'])
            ->prefix('api/cms')
            ->name('cms.api.')
            ->group(__DIR__ . '/Routes/api.php');

        // Public Routes
        Route::middleware(['web'])
            ->group(__DIR__ . '/Routes/web.php');

        // Register Dynamic Routes
        if (!$this->app->routesAreCached()) {
            $this->app['cms.routes']->register();
        }
    }

    /**
     * Register the package views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'cms');

        // Register theme views
        View::addNamespace('theme', $this->app['theme']->getThemePath('views'));
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        // Theme Asset directive
        Blade::directive('themeAsset', function ($expression) {
            return "<?php echo theme_asset($expression); ?>";
        });

        // Menu directive
        Blade::directive('menu', function ($expression) {
            return "<?php echo menu_render($expression); ?>";
        });

        // Settings directive
        Blade::directive('setting', function ($expression) {
            return "<?php echo cms_setting($expression); ?>";
        });
    }

    /**
     * Register middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $router = $this->app['router'];
        
        // Admin middleware
        $router->aliasMiddleware('cms.admin', \Webtree\WebtreeCms\Core\Admin\Middleware\AdminMiddleware::class);
        
        // Theme middleware
        $router->aliasMiddleware('cms.theme', \Webtree\WebtreeCms\Core\Theme\Middleware\ThemeMiddleware::class);
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webtree\WebtreeCms\Console\Commands\InstallCommand::class,
                \Webtree\WebtreeCms\Console\Commands\MakeThemeCommand::class,
                \Webtree\WebtreeCms\Console\Commands\ClearCacheCommand::class,
            ]);
        }
    }

    /**
     * Register view composers.
     *
     * @return void
     */
    protected function registerViewComposers()
    {
        // Admin layout composer
        View::composer('cms::admin.*', \Webtree\WebtreeCms\Core\Admin\ViewComposers\AdminComposer::class);

        // Theme layout composer
        View::composer('theme::*', \Webtree\WebtreeCms\Core\Theme\ViewComposers\ThemeComposer::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'theme',
            'cms.routes',
            'cms.menu',
            'cms',
        ];
    }
}