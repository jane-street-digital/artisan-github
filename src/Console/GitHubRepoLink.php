<?php

namespace JaneStreetDigital\ArtisanGitHub\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JaneStreetDigital\ArtisanGitHub\CommandHelper;

class GitHubRepoLink extends Command
{
    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gh:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open the current GitHub Repo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->openViaBuiltInStrategy($this->getRepoUrl());

        return Command::SUCCESS;
    }
}
