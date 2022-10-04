<?php

namespace JaneStreetDigital\ArtisanGitHub;

use Illuminate\Support\ServiceProvider;
use JaneStreetDigital\ArtisanGitHub\Console\GitHubRepoLink;
use JaneStreetDigital\ArtisanGitHub\Console\GitHubPRLink;
use JaneStreetDigital\ArtisanGitHub\Console\Wip;
use JaneStreetDigital\ArtisanGitHub\Console\GitHubIssueLink;

class ArtisanGitHubServiceProvider extends ServiceProvider
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