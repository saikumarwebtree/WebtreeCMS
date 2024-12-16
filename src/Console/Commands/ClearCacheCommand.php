<?php

namespace Webtree\WebtreeCms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCacheCommand extends Command
{
    protected $signature = 'cms:clear-cache';
    protected $description = 'Clear all CMS caches';

    public function handle()
    {
        $this->info('Clearing CMS cache...');

        // Clear route cache
        $this->call('route:clear');

        // Clear view cache
        $this->call('view:clear');

        // Clear config cache
        $this->call('config:clear');

        // Clear application cache
        Cache::tags(['cms'])->flush();

        // Clear theme assets cache
        if (file_exists(public_path('themes'))) {
            $this->files->deleteDirectory(public_path('themes'), true);
        }

        $this->info('CMS cache cleared successfully!');
    }
}