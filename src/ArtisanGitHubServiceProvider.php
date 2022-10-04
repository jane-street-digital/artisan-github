<?php

namespace JaneStreetDigital\ArtisanWip;

use Illuminate\Support\ServiceProvider;
use JaneStreetDigital\ArtisanWip\Console\GitHubRepoLink;
use JaneStreetDigital\ArtisanWip\Console\GitHubPRLink;
use JaneStreetDigital\ArtisanWip\Console\Wip;
use JaneStreetDigital\ArtisanWip\Console\GitHubIssueLink;

class ArtisanWipServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    public function register()
    {
        //
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GitHubPRLink::class,
                GitHubIssueLink::class,
                GitHubRepoLink::class,
                Wip::class,
            ]);
        }
    }
}