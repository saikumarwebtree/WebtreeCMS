<?php

namespace Webtree\WebtreeCms\Core\Admin\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class BaseAdminController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * The view path for admin views
     *
     * @var string
     */
    protected $viewPath = 'cms::admin';

    /**
     * The route prefix for admin routes
     *
     * @var string
     */
    protected $routePrefix = 'cms.admin';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('cms.admin');
        $this->middleware('auth');
        
        // Share common data with all admin views
        View::share('adminMenu', $this->getAdminMenu());
        View::share('routePrefix', $this->routePrefix);
    }

    /**
     * Get the admin menu structure
     *
     * @return array
     */
    protected function getAdminMenu()
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'route' => 'cms.admin.dashboard',
            ],
            'posts' => [
                'label' => 'Posts',
                'icon' => 'article',
                'route' => 'cms.admin.posts.index',
                'submenu' => [
                    'all' => [
                        'label' => 'All Posts',
                        'route' => 'cms.admin.posts.index',
                    ],
                    'create' => [
                        'label' => 'Add New',
                        'route' => 'cms.admin.posts.create',
                    ],
                ],
            ],
            'media' => [
                'label' => 'Media',
                'icon' => 'image',
                'route' => 'cms.admin.media.index',
            ],
            'settings' => [
                'label' => 'Settings',
                'icon' => 'settings',
                'route' => 'cms.admin.settings.index',
            ],
        ];
    }

    /**
     * Get the view path for a specific view
     *
     * @param string $view
     * @return string
     */
    protected function view($view)
    {
        return $this->viewPath . '.' . $view;
    }

    /**
     * Get the route name with prefix
     *
     * @param string $route
     * @return string
     */
    protected function route($route)
    {
        return $this->routePrefix . '.' . $route;
    }

    /**
     * Return success response
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($message = 'Operation successful', $data = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Return error response
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($message = 'Operation failed', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * Handle unauthorized access
     *
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    protected function unauthorized($message = 'Unauthorized access')
    {
        if (request()->ajax()) {
            return $this->error($message, 403);
        }

        abort(403, $message);
    }
}