<?php

namespace JaneStreetDigital\ArtisanGitHub\Console;

use Illuminate\Console\Command;
use JaneStreetDigital\ArtisanGitHub\CommandHelper;

class GitHubIssueLink extends Command
{
    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gh:issues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open the current Issues for your current repo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->openViaBuiltInStrategy($this->getRepoUrl() . '/issues');

        return Command::SUCCESS;
    }
}
