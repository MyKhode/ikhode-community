<?php

namespace Wave\Plugins\Publisher;

use Livewire\Livewire;
use Wave\Plugins\Plugin;
use Illuminate\Support\Facades\File;
use Wave\Plugins\Publisher\Components\PaperSubmission;

class PublisherPlugin extends Plugin
{
    protected $name = 'Publisher';

    protected $description = 'Allows users to submit research papers for admin review and publishing.';

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'publisher');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        Livewire::component('paper-submission', PaperSubmission::class);
    }

    public function getPluginInfo(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->getPluginVersion(),
        ];
    }

    public function getPluginVersion(): array
    {
        return File::json(__DIR__ . '/version.json');
    }
}
