<?php

namespace Caneco\ArtisanAliases;

class AliasModel
{
    private $name;

    private $command;

    public function __construct(string $name, string $command)
    {
        $this->name = $name;
        $this->command = $command;
    }

    public static function parse(string $alias): AliasModel
    {
        [$name, $command] = explode('=', trim($alias), 2);

        return new self($name, $command);
    }

    public function getName(): string
    {
        return $this->sanitizeName($this->name);
    }

    public function getCommand(): string
    {
        return $this->sanitizeCommand($this->command);
    }

    public function sanitizeName(string $string): string
    {
        return trim(str_replace(['export ', '\'', '"'], '', $string));
    }

    public function sanitizeCommand(string $string): string
    {
        $string = trim(strstr($string, '#', true) ?: $string);

        if (isset($string[0]) && ($string[0] === '"' || $string[0] === '\'')) {
            $quote = $string[0];

            if (substr($string, -1) !== $quote) {
                throw new AliasException('Alias values must be surrounded with the same quote.');
            }

            $regex = sprintf( // thanks for @vlucas/phpdotenv for the the snippet
                '/^
                %1$s           # match a quote at the start of the value
                (              # capturing sub-pattern used
                 (?:           # we do not need to capture this
                  [^%1$s\\\\]* # any character other than a quote or backslash
                  |\\\\\\\\    # or two backslashes together
                  |\\\\%1$s    # or an escaped quote e.g \"
                 )*            # as many characters that match the previous rules
                )              # end of the capturing sub-pattern
                %1$s           # and the closing quote
                .*$            # and discard any string after the closing quote
                /mx',
                $quote
            );
            $string = preg_replace($regex, '$1', $string);
            $string = str_replace("\\$quote", $quote, $string);
            $string = str_replace('\\\\', '\\', $string);
        }
        else if (preg_match('/\s+/', $string) > 0) {
            throw new AliasException('Alias values containing spaces must be surrounded by quotes.');
        }

        return trim($string);
    }
}