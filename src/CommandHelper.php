<?php

namespace JaneStreetDigital\ArtisanGitHub;

use Illuminate\Support\Collection;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use function Termwind\{render};

trait CommandHelper
{
    protected function errorOut(string $error)
    {
        render(<<<HTML
            <div class="flex mb-1">
                <span class="text-white bg-red-600 mr-2 px-1 py-.5">
                    Error
                </span>
                <span>$error</span>
            </div>
        HTML);
    }

    protected function infoOut(string $info)
    {
        render(<<<HTML
            <div class="flex mb-1">
                <span class="text-white bg-purple-600 mr-2 px-1 py-.5">
                    Info
                </span>
                <span>$info</span>
            </div>
        HTML);
    }

    protected function itemOut(string $item)
    {
        render(<<<HTML
            <div class="flex space-x-1 mb-.5">
                <span class="text-purple-700">$item</span>
                <span class="content-repeat-[.] flex-1"></span>
                <span class="text-green-800">DONE</span>
            </div>
        HTML);
    }

    protected function cli(string $command, callable $callback = null)
    {
        return tap(Process::fromShellCommandline(escapeshellcmd("$command")))->run($callback);
    }

    protected function getRepoUrl(): ?string
    {
        $response = $this->cli('git remote -v');

        [$fetch, $push] = explode("\n", $response->getOutput());

        return 'https://github.com/' . Str::between($fetch, '.com:', '.git');
    }

    protected function getBranch(): string
    {
        $response = $this->cli('git branch --show-current');

        return Str::between($response->getOutput(), '* ', "\n");
    }

    protected function openViaBuiltInStrategy($url)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $process = $this->cli("start {$url}");

            if (! $process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return;
        }

        $binary = Collection::make(match (PHP_OS_FAMILY) {
            'Darwin' => ['open'],
            'Linux' => ['xdg-open', 'wslview'],
        })->first(fn ($binary) => (new ExecutableFinder)->find($binary) !== null);

        if ($binary === null) {
            $this->components->warn('Unable to open the URL on your system. You will need to open it yourself or create a custom opener for your system.');

            return;
        }

        $process = $this->cli("{$binary} {$url}");

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return;
    }
}