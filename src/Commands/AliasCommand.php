<?php

namespace Caneco\ArtisanAliases\Commands;

use Caneco\ArtisanAliases\Alias;
use Caneco\ArtisanAliases\AliasDirectory;
use Caneco\ArtisanAliases\AliasManager;
use Illuminate\Console\Command;

class AliasCommand extends Command
{
    protected $signature = 'alias
                            {name? : The name of the alias}
                            {line? : The command line to be aliased}
                            {--list : list all alias defined}
                            {--g|global : Add alias globally [`~/.laravel_alias`]}
                            {--force : Force alias to be replaced}';

    protected $description = 'Create an alias of another command.';

    public function handle()
    {
        if (empty($this->argument('name'))
            or empty($this->argument('line'))
            or $this->option('list')
        ) {
            $this->handleList();
            exit;
        }

        Alias::store(
            trim($this->argument('name')),
            trim($this->argument('line')),
            $this->option('global')
        );
    }

    private function handleList()
    {
        $list = Alias::load();

        $this->line(sprintf('Laravel `Artisan Aliases` <info>%s</info>', Alias::VERSION));
        $this->line('');

        $this->warn('Usage:');
        $this->line(' '.$this->getSynopsis());
        $this->line('');

        $this->warn('Available alias:');
        $this->table(
            [], // discard table headers
            array_map(function ($name, $value) {
                return ["<info>{$name}</info>", $value];
            }, array_keys($list), $list),
            'compact'
        );

        if (! config('aliases.enabled')) {
            $this->getOutput()->block('All aliases are currently disabled, to enable set `enabled = true` in your config file to use them.', 'Notice', 'fg=black;bg=yellow', '! ', true);
        }
    }
}