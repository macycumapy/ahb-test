<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService\Parsers;

use Exception;

class NomenclatureParserFactory
{
    /**
     * @throws Exception
     */
    public function getParser(string $filepath): AbstractNomenclatureParser
    {
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return match ($extension) {
            'xlsx' => app(NomenclatureXlsxParser::class),
            'csv' => app(NomenclatureCSVParser::class),
            default => throw new Exception('Не удалось определить парсер файла')
        };
    }
}
