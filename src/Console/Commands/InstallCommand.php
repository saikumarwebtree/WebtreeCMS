<?php

namespace Webtree\WebtreeCms\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Install the WebtreeCMS package';

    public function handle()
    {
        $this->info('Installing WebtreeCMS...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'Webtree\WebtreeCms\WebtreeCmsServiceProvider',
            '--tag' => 'cms-config'
        ]);

        // Publish assets
        $this->call('vendor:publish', [
            '--provider' => 'Webtree\WebtreeCms\WebtreeCmsServiceProvider',
            '--tag' => 'cms-assets'
        ]);

        // Run migrations
        $this->call('migrate');

        // Create necessary directories
        $this->createDirectories();

        $this->info('WebtreeCMS has been installed successfully!');
    }

    protected function createDirectories()
    {
        // Create cache directory
        if (!file_exists(storage_path('framework/cache'))) {
            mkdir(storage_path('framework/cache'), 0755, true);
        }

        // Create themes directory
        if (!file_exists(base_path('themes'))) {
            mkdir(base_path('themes'), 0755, true);
        }

        // Create uploads directory
        if (!file_exists(public_path('uploads'))) {
            mkdir(public_path('uploads'), 0755, true);
        }
    }
}