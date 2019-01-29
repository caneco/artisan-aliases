<?php

namespace Caneco\ArtisanAliases\Commands;

use Caneco\ArtisanAliases\Alias;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AliasedCommand extends Command
{
    public $alias;

    public static function create(string $alias, string $command)
    {
        $self = new self;
        $self->setName($alias);
        $self->setDescription("* Alias for the `{$command} command`");
        $self->setAlias($command);

        return $self;
    }

    public function setAlias(string $command)
    {
        $this->alias = $command;
    }

    public function handle()
    {
        if (strpos(trim($this->alias), '!') === 0) {
            $this->process($this->alias);
            exit;
        }

        if (strpos($this->alias, '&&') !== false) {
            foreach (explode('&&', $this->alias) as $alias) {
                if ($this->call(trim($alias))) break;
            }
            exit;
        }

        foreach (explode('||', $this->alias) as $alias) {
            $this->call(trim($alias));
        }
    }

    private function process(string $command)
    {
        $process = Process::fromShellCommandline(
            substr($command, 1),
            base_path() // set as default the base path as CWD
        );

        try {
            $process->run(function ($type, $buffer) { echo $buffer; });
        } catch (ProcessFailedException $e) {
            $this->error($e->getMessage());
        }
    }
}
