<?php

namespace Caneco\ArtisanAliases\Tests\Functional;

use Caneco\ArtisanAliases\ArtisanAliasesServiceProvider;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class AliasCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ArtisanAliasesServiceProvider::class,
        ];
    }

    /** @test */
    public function artisan_alias_command_is_available_in_application()
    {
        $this->artisan('alias')
            ->assertExitCode(0);
    }

    /** @test */
    public function artisan_alias_disabled_throws_exception_on_missing_command()
    {
        config(['aliases.enabled' => false]);

        $this->expectException(CommandNotFoundException::class);
        $this->artisan('laravel');
    }

    /** @test */
    public function artisan_alias_disabled_throws_exception_when_use_only_global()
    {
        config(['aliases.use_only' => 'global']);

        $this->expectException(CommandNotFoundException::class);
        $this->artisan('laravel');
    }
}
