<?php

namespace Webtree\WebtreeCms\Core\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Webtree\WebtreeCms\Core\Models\Route as RouteModel;

class RouteRegistry
{
    protected $cacheKey = 'cms.routes';
    protected $cacheDuration = 3600; // 1 hour

    public function register()
    {
        try {
            // Check if routes table exists
            if (!Schema::hasTable('routes')) {
                return;
            }

            $routes = Cache::store('file')->remember($this->cacheKey, $this->cacheDuration, function () {
                return RouteModel::active()->get();
            });

            foreach ($routes as $route) {
                Route::match(
                    explode('|', $route->methods),
                    $route->uri,
                    $route->controller_action
                )->name($route->name);
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Failed to register CMS routes: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        Cache::store('file')->forget($this->cacheKey);
    }
}