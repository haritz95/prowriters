<?php

namespace App\Contracts;

interface AiContentGenerateUseCaseContract
{
    public static function getUniqueIdentifier(): string;

    public static function validationRules(): array;

    public static function prompt(): array;

    public static function formFields(): array;

    public static function dropdown(): array;
}
