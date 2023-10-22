<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService;

use App\Services\NomenclatureUploaderService\Data\NomenclatureData;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use SimpleXLSX;

class NomenclatureXlsxParser
{
    private const SEP = '%sep%';
    private array $errors = [];

    public function parse(string $data): Collection
    {
        $xlsx = SimpleXLSX::parseData($data);

        $data = collect($xlsx->rows())
            ->skip(1)
            ->map(function ($row, $key) {
                $rowData = explode(';', implode(self::SEP, $row));
                try {
                    if (count($rowData) < 14) {
                        throw ValidationException::withMessages([
                            'error' => "В строке должно быть 13 разделителей"
                        ]);
                    }

                    return NomenclatureData::validateAndCreate([
                        'code' => $this->trimString($rowData[0]),
                        'name' => $this->trimString($rowData[1]),
                        'level1' => $this->trimString($rowData[2]),
                        'level2' => $this->trimString($rowData[3]),
                        'level3' => $this->trimString($rowData[4]),
                        'price' => $this->stringToFloat($rowData[5]),
                        'priceSP' => $this->stringToFloat($rowData[6]),
                        'quantity' => $this->stringToFloat($rowData[7]),
                        'properties' => $this->trimString($rowData[8]),
                        'jointPurchases' => (bool)$rowData[9],
                        'measurement' => str_replace('"', '', $rowData[10]),
                        'image' => $this->trimString($rowData[11]),
                        'showMain' => $rowData[12],
                        'description' => $this->trimString($rowData[13]),
                    ]);
                } catch (ValidationException $e) {
                    $rowNum = $key + 1;
                    $this->errors["$rowNum file row"] = $e->errors();
                }

                return null;
            });

        if (!empty($this->errors)) {
            throw ValidationException::withMessages($this->errors);
        }

        return $data;
    }

    private function trimString(?string $str): ?string
    {
        return trim(str_replace(self::SEP, ' ', $str)) ?: null;
    }

    private function stringToFloat(?string $str): float
    {
        return (float) str_replace(self::SEP, '.', $str);
    }
}
