<?php

namespace Caneco\ArtisanAliases;

use Caneco\ArtisanAliases\Alias;
use Caneco\ArtisanAliases\Commands\AliasCommand;
use Caneco\ArtisanAliases\Commands\AliasedCommand;
use Illuminate\Support\ServiceProvider;

class ArtisanAliasesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->registerCommands();
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/aliases.php', 'aliases'
        );
    }

    private function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/aliases.php' => config_path('aliases.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../stubs/laravel_aliases' => base_path('.laravel_aliases'),
        ], 'aliases');
    }

    private function registerCommands(): void
    {
        $this->commands(AliasCommand::class);

        if (config('aliases.enabled')) {
            foreach (Alias::load(config('aliases.use_only')) as $alias => $command) {
                $this->app->bind(
                    $list[] = "command.x-{$alias}",
                    function () use ($alias, $command) {
                        return AliasedCommand::create($alias, $command);
                    }
                );
            }

            $this->commands($list ?? []);
        }
    }
}
