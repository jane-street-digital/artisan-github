<?php

namespace JaneStreetDigital\ArtisanGitHub\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JaneStreetDigital\ArtisanGitHub\CommandHelper;
use function Termwind\{ask};

class Wip extends Command
{
    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build, Commit, and Push all current changes to the remote branch';

    private bool $hasSomethingToShip = true;

    private string $url;
    private string $branch;
    private string $remote = 'origin';

    public function __construct()
    {
        parent::__construct();

        $this->url = $this->getRepoUrl();
        $this->branch = $this->getBranch();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkWorkingTreeState();

        if (!$this->hasSomethingToShip) {
            return Command::FAILURE;
        }

        $this->infoOut('WIP In Progress');

        $this->npmRunBuild();
        $this->gitCommitAll();
        $this->gitPush();
        $this->askToOpenPr();

        return Command::SUCCESS;
    }

    protected function checkWorkingTreeState()
    {
        $this->cli('git status', function ($type, $data) {
            if (Str::contains($data, 'nothing to commit, working tree clean')) {
                $this->errorOut('Nothing to commit, working tree clean');
                $this->hasSomethingToShip = false;
            }
        });
    }

    protected function npmRunBuild()
    {
        $this->cli('npm run build');
        $this->itemOut('Production Assets Built');
    }

    protected function gitCommitAll(): void
    {
        $this->cli("git add .");
        $this->cli("git commit -m 'wip'");
        $this->itemOut('Changes Commited');
    }

    protected function gitPush(): void
    {
        $this->cli("git push {$this->remote} {$this->branch}");
        $this->itemOut('Changes pushed to remote branch');
    }

    protected function askToOpenPr()
    {
        $answer = ask('Create a PR? [y,n]');
        if ($answer === 'y') {
            $this->openViaBuiltInStrategy("{$this->url}/pull/new/{$this->branch}");
        }
    }
}
