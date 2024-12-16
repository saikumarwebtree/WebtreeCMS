<?php

namespace Webtree\WebtreeCms\Core\Theme;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('theme', function ($app) {
            return new ThemeManager();
        });
    }

    public function boot()
    {
        $this->loadViewsFrom($this->app['theme']->getThemePath('views'), 'theme');
    }
}