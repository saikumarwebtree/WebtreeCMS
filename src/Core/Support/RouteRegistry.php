<?php

namespace Sai\WebtreeCms\Core\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Sai\WebtreeCms\Core\Models\Route as RouteModel;

class RouteRegistry
{
    protected $cacheKey = 'cms.routes';
    protected $cacheDuration = 3600; // 1 hour

    public function register()
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            Route::match(
                explode('|', $route->methods),
                $route->uri,
                $route->controller_action
            )->name($route->name);
        }
    }

    protected function getRoutes()
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            return RouteModel::active()->get();
        });
    }

    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }
}