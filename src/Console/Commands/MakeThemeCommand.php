<?php

namespace Webtree\WebtreeCms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeThemeCommand extends Command
{
    protected $signature = 'cms:make-theme {name : The name of the theme}';
    protected $description = 'Create a new theme';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = Str::kebab($this->argument('name'));
        $themePath = base_path("themes/{$name}");

        if ($this->files->exists($themePath)) {
            $this->error("Theme [{$name}] already exists!");
            return 1;
        }

        // Create theme directory structure
        $this->createThemeStructure($themePath);

        // Create basic theme files
        $this->createThemeFiles($name, $themePath);

        $this->info("Theme [{$name}] created successfully!");
    }

    protected function createThemeStructure($path)
    {
        $directories = [
            'assets/css',
            'assets/js',
            'assets/images',
            'views/layouts',
            'views/partials',
            'views/pages',
        ];

        foreach ($directories as $dir) {
            $this->files->makeDirectory("{$path}/{$dir}", 0755, true);
        }
    }

    protected function createThemeFiles($name, $path)
    {
        // Create theme.json
        $themeJson = json_encode([
            'name' => Str::title($name),
            'description' => 'A custom theme for WebtreeCMS',
            'version' => '1.0.0',
            'author' => 'Your Name',
            'supports' => [
                'custom-header' => true,
                'custom-background' => true
            ]
        ], JSON_PRETTY_PRINT);

        $this->files->put("{$path}/theme.json", $themeJson);

        // Create basic layout file
        $layoutContent = $this->getLayoutTemplate();
        $this->files->put("{$path}/views/layouts/app.blade.php", $layoutContent);

        // Create style.css
        $this->files->put("{$path}/assets/css/style.css", "/* Theme styles */\n");
    }

    protected function getLayoutTemplate()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @stack('styles')
</head>
<body>
    @include('theme::partials.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('theme::partials.footer')
    
    @stack('scripts')
</body>
</html>
HTML;
    }
}
