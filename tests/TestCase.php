<?php

namespace Caneco\ArtisanAliases\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Caneco\ArtisanAliases\ArtisanAliasesServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        // return [ArtisanAliasesServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }
}