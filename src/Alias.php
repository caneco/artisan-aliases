<?php

namespace Caneco\ArtisanAliases;

use Caneco\ArtisanAliases\AliasFile;
use Caneco\ArtisanAliases\AliasManager;

class Alias
{
    public const VERSION = '1.1.0';

    public static function store(string $name, string $line, ?bool $global = false, ?bool $force = false)
    {

        return self::manager($global)->save(new AliasModel($name, $line), $force);
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

    public static function manager(bool $global): AliasManager
    {
        return new AliasManager(
            $global ? AliasFile::global() : AliasFile::local()
        );
    }
}
