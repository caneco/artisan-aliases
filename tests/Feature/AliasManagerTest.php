<?php

namespace Caneco\ArtisanAliases\Tests\Feature;

use Caneco\ArtisanAliases\AliasManager;
use Caneco\ArtisanAliases\AliasModel;
use Caneco\ArtisanAliases\Exceptions\AliasException;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\TestCase;

class AliasManagerTest extends TestCase
{
    protected $path = __DIR__.'/../tmp/laravel_aliases';

    public function setUp()
    {
        copy(__DIR__.'/../../stubs/laravel_aliases', $this->path);

        Artisan::shouldReceive('all')
            ->andReturn(['laravel' => 'inspire']);
    }

    public function tearDown()
    {
        array_map('unlink', glob(__DIR__.'/tmp/*'));
    }

    /** @test */
    public function an_exception_is_not_thrown_if_the_file_does_not_exist()
    {
        $manager = new AliasManager('MISSING_' . $this->path);

        $this->assertEmpty($manager->safeLoadFile());
    }

    /** @test */
    public function stub_file_contains_the_initial_aliases()
    {
        $manager = new AliasManager($this->path);

        $this->assertEquals(
            'laravel=inspire,# cc=clear-compiled',
            implode(',', $manager->loadFile())
        );
    }

    /** @test */
    public function initial_aliases_from_stub_are_imported_on_load()
    {
        $manager = new AliasManager($this->path);

        $this->assertEquals(
            '{"laravel":"inspire"}',
            json_encode($manager->load())
        );
    }

    /** @test */
    public function an_alias_can_be_saved()
    {
        $manager = new AliasManager($this->path);
        $manager->save(AliasModel::parse('example=alias'));
        $alias = $manager->load();

        $this->assertArrayHasKey('example', $alias);
        $this->assertContains('alias', array_values($alias));
    }

    /** @test */
    public function an_exception_is_thrown_storing_an_alias_that_already_exists()
    {
        $this->expectException(AliasException::class);
        $this->expectExceptionMessage("Artisan command/alias already exists!");

        $manager = new AliasManager($this->path);
        $manager->save(AliasModel::parse('laravel=expires'));
    }

    /** @test */
    public function force_storing_an_alias_that_already_exists()
    {
        $manager = new AliasManager($this->path);
        $manager->save(AliasModel::parse('laravel=expires'), $force = true);
        $alias = $manager->load();

        $this->assertArrayHasKey('laravel', $alias);
        $this->assertContains('expires', array_values($alias));
    }
}
