<?php

namespace Caneco\ArtisanAliases;

use Caneco\ArtisanAliases\AliasFile;
use Caneco\ArtisanAliases\AliasManager;

class Alias
{
    public const VERSION = '1.0.0';

    public static function store(string $name, string $line, bool $global)
    {
        $manager = new AliasManager(
            $global ? AliasFile::global() : AliasFile::local()
        );

        return $manager->save(new AliasModel($name, $line));
    }

    public static function load(?string $location = null): array
    {
        switch ($location) {
            case 'local':
                return self::local();
            case 'global':
                return self::global();
            default:
                return array_merge(self::global(), self::local());
        }
    }

    public static function local(): array
    {
        return (new AliasManager(
            AliasFile::local()
        ))->load();
    }

    public static function global(): array
    {
        return (new AliasManager(
            AliasFile::global()
        ))->load();
    }
}
