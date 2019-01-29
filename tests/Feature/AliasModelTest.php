<?php

namespace Caneco\ArtisanAliases\Tests\Feature;

use Caneco\ArtisanAliases\AliasManager;
use Caneco\ArtisanAliases\AliasModel;
use Caneco\ArtisanAliases\Exceptions\AliasException;
use PHPUnit\Framework\TestCase;

class AliasModelTest extends TestCase
{
    /** @test */
    public function an_alias_model_can_be_defined_from_a_string()
    {
        $model = AliasModel::parse('example=alias');

        $this->assertEquals('example', $model->getName());
        $this->assertEquals('alias', $model->getCommand());
    }

    /** @test */
    public function a_comment_is_ignored_from_the_parsing()
    {
        $model = AliasModel::parse('example=alias #comment');

        $this->assertEquals('example', $model->getName());
        $this->assertEquals('alias', $model->getCommand());
    }

    /** @test */
    public function a_command_with_spaces_must_be_surrounded_with_quotes()
    {
        $this->expectException(AliasException::class);
        $this->expectExceptionMessage("Alias values containing spaces must be surrounded by quotes.");

        $model = AliasModel::parse('example=spaced alias');
        $model->getCommand();
    }

    /** @test */
    public function same_quotes_must_be_surrounding_a_command()
    {
        $this->expectException(AliasException::class);
        $this->expectExceptionMessage("Alias values must be surrounded with the same quote.");

        $model = AliasModel::parse('example=\'spaced alias"');
        $model->getCommand();
    }
}
