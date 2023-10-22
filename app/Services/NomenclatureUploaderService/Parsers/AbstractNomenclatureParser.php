<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService\Parsers;

use Illuminate\Support\Collection;

abstract class AbstractNomenclatureParser
{
    protected const SEP = '%sep%';
    protected array $errors = [];

    abstract public function parse(string $filepath): Collection;

    protected function trimString(?string $str): ?string
    {
        return trim(str_replace(self::SEP, ' ', $str)) ?: null;
    }

    protected function stringToFloat(?string $str): float
    {
        return (float) str_replace(self::SEP, '.', $str);
    }
}
