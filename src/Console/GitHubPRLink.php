<?php

namespace JaneStreetDigital\ArtisanWip\Console;

use Illuminate\Console\Command;
use JaneStreetDigital\ArtisanWip\CommandHelper;

class GitHubPRLink extends Command
{
    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gh:pr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link to PRs for your repo';

    protected function link($repo)
    {
        return "https://github.com/$repo/pulls";
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->openViaBuiltInStrategy($this->getRepoUrl() . '/pulls');

        return Command::SUCCESS;
    }
}
