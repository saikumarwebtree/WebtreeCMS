<?php

namespace Webtree\WebtreeCms\Core\Theme;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ThemeManager
{
    protected $activeTheme;
    protected $themesPath;
    protected $themeConfig;

    public function __construct()
    {
        $this->themesPath = config('cms.themes_path', base_path('themes'));
        $this->activeTheme = config('cms.active_theme', 'default');
        $this->loadThemeConfig();
    }

    protected function loadThemeConfig()
    {
        $configPath = $this->getThemePath('theme.json');
        $this->themeConfig = File::exists($configPath) 
            ? json_decode(File::get($configPath), true) 
            : [];
    }

    public function getThemePath($path = '')
    {
        return $this->themesPath . '/' . $this->activeTheme . ($path ? '/' . $path : '');
    }

    public function resolveTemplate($template)
    {
        $hierarchy = [
            "themes.{$this->activeTheme}.{$template}",
            "themes.{$this->activeTheme}.default",
            "cms::fallback.{$template}",
        ];

        foreach ($hierarchy as $view) {
            if (View::exists($view)) {
                return $view;
            }
        }

        return 'cms::fallback.default';
    }
}