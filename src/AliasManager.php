<?php

namespace Caneco\ArtisanAliases;

use Caneco\ArtisanAliases\AliasDirectory;
use Caneco\ArtisanAliases\AliasModel;
use Caneco\ArtisanAliases\Exceptions\AliasException;
use Illuminate\Support\Facades\Artisan;
use sixlive\DotenvEditor\DotenvEditor;

class AliasManager
{
    public $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function load(): array
    {
        $list = array();

        foreach ($this->safeLoadFile() as $line) {
            if ($this->looksValid($line) && $this->isNotComment($line)) {
                $alias = AliasModel::parse($line);
                $list[$alias->getName()] = $alias->getCommand();
            }
        }

        return $list;
    }

    public function loadFile(): array
    {
        $detection = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $detection);

        return array_map('trim', $lines);
    }

    public function safeLoadFile(): array
    {
        try {
            return $this->loadFile();
        } catch (\Throwable $e) {
            return []; // suppressing exceptions
        }
    }

    public function save(AliasModel $command)
    {
        if (! file_exists($this->filePath)) {
            throw new AliasException("Expected file '{$this->filePath}' does not exists!");
        }

        if (array_key_exists($command->getName(), Artisan::all())) {
            throw new AliasException("Artisan command/alias already exists!");
        }

        $file = new DotenvEditor;
        $file->load($this->filePath);
        $file->set($command->getName(), '"'.$command->getCommand().'"');

        return $file->save();
    }

    public function looksValid(string $string): bool
    {
        return strpos($string, '=') !== false;
    }

    public function isNotComment(string $string): bool
    {
        return isset($string[0]) && $string[0] !== '#';
    }
}