<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService;

use App\Services\NomenclatureUploaderService\Data\NomenclatureData;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use SimpleXLSX;

class NomenclatureXlsxParser
{
    private array $errors = [];

    public function parse(string $data): Collection
    {
        $xlsx = SimpleXLSX::parseData($data);

        $data = collect($xlsx->rows())
            ->skip(1)
            ->map(function ($row, $key) {
                $rowData = explode(';', implode(' ', $row));
                try {
                    if (count($rowData) < 14) {
                        throw ValidationException::withMessages([
                            'error' => "В строке должно быть 13 разделителей"
                        ]);
                    }

                    return NomenclatureData::validateAndCreate([
                        'code' => trim($rowData[0]),
                        'name' => $rowData[1],
                        'level1' => trim($rowData[2]) ?: null,
                        'level2' => trim($rowData[3]) ?: null,
                        'level3' => trim($rowData[4]) ?: null,
                        'price' => (float)$rowData[5],
                        'priceSP' => (float)$rowData[6],
                        'quantity' => (float)$rowData[7],
                        'properties' => $rowData[8] ?: null,
                        'jointPurchases' => (bool)$rowData[9],
                        'measurement' => str_replace('"', '', $rowData[10]),
                        'image' => trim($rowData[11]) ?: null,
                        'showMain' => $rowData[12],
                        'description' => trim($rowData[13]) ?: null,
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
}
