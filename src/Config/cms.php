<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CMS General Configuration
    |--------------------------------------------------------------------------
    */
    'name' => env('CMS_NAME', 'Webtree CMS'),
    'version' => '1.0.0',
    
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Configuration
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'prefix' => env('CMS_ADMIN_PREFIX', 'admin'),
        'middleware' => ['web', 'auth', 'cms.admin'],
        'theme' => 'default',
        'pagination' => [
            'per_page' => 20,
        ],
        'upload_path' => 'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'active' => env('CMS_THEME', 'default'),
        'path' => base_path('themes'),
        'cache' => [
            'enabled' => env('CMS_THEME_CACHE', true),
            'duration' => 3600, // 1 hour
        ],
        'assets_path' => 'themes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Types Configuration
    |--------------------------------------------------------------------------
    */
    'content_types' => [
        'post' => [
            'model' => \Webtree\WebtreeCms\Core\Models\Post::class,
            'table' => 'posts',
            'frontend_route' => 'blog.show',
            'status' => ['draft', 'published'],
            'features' => [
                'categories' => true,
                'tags' => true,
                'comments' => true,
                'featured_image' => true,
            ],
        ],
        'page' => [
            'model' => \Webtree\WebtreeCms\Core\Models\Page::class,
            'table' => 'pages',
            'frontend_route' => 'page.show',
            'status' => ['draft', 'published'],
            'features' => [
                'templates' => true,
                'featured_image' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Configuration
    |--------------------------------------------------------------------------
    */
    'media' => [
        'disk' => env('CMS_MEDIA_DISK', 'public'),
        'path' => 'media',
        'allowed_types' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'video' => ['mp4', 'avi', 'mov'],
        ],
        'max_file_size' => 5120, // in KB (5MB)
        'image_sizes' => [
            'thumbnail' => [150, 150],
            'medium' => [300, 300],
            'large' => [1024, 1024],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'meta_tags' => true,
        'sitemap' => [
            'enabled' => true,
            'cache_duration' => 3600,
        ],
        'robots_txt' => true,
        'open_graph' => true,
        'twitter_cards' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('CMS_CACHE_ENABLED', true),
        'duration' => env('CMS_CACHE_DURATION', 3600),
        'prefix' => 'cms_',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'enabled' => env('CMS_API_ENABLED', true),
        'prefix' => 'api/cms',
        'middleware' => ['api'],
        'throttle' => [
            'enabled' => true,
            'attempts' => 60,
            'duration' => 1, // minutes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */
    'menus' => [
        'locations' => [
            'primary' => 'Primary Navigation',
            'footer' => 'Footer Navigation',
        ],
        'cache' => [
            'enabled' => true,
            'duration' => 3600,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Configuration
    |--------------------------------------------------------------------------
    */
    'widgets' => [
        'locations' => [
            'sidebar' => 'Sidebar',
            'footer' => 'Footer Widgets',
        ],
        'cache' => [
            'enabled' => true,
            'duration' => 3600,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Comments Configuration
    |--------------------------------------------------------------------------
    */
    'comments' => [
        'enabled' => true,
        'moderation' => true,
        'notification' => [
            'email' => true,
            'admin_email' => env('CMS_ADMIN_EMAIL'),
        ],
        'spam_protection' => true,
        'allowed_html_tags' => '<p><a><strong><em><ul><ol><li><blockquote>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'login_throttling' => [
            'enabled' => true,
            'max_attempts' => 5,
            'decay_minutes' => 1,
        ],
        'password_requirements' => [
            'min_length' => 8,
            'require_numbers' => true,
            'require_symbols' => true,
            'require_mixed_case' => true,
        ],
    ],
];