<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService\Parsers;

use App\Services\NomenclatureUploaderService\Data\NomenclatureData;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class NomenclatureCSVParser extends AbstractNomenclatureParser
{
    public function parse(string $filepath): Collection
    {
        $file = fopen($filepath, 'r');
        $header = fgetcsv($file);

        $data = collect();
        $rowNum = 2;
        while ($row = fgetcsv($file)) {
            $rowData = explode(';', implode(self::SEP, $row));

            try {
                if (count($rowData) < 14) {
                    throw ValidationException::withMessages([
                        'error' => "В строке должно быть 13 разделителей"
                    ]);
                }

                $data->push(NomenclatureData::validateAndCreate([
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
                    'description' => $this->trimString(strip_tags($rowData[13])),
                ]));

            } catch (ValidationException $e) {
                $this->errors["$rowNum file row"] = $e->errors();
            }

            $rowNum++;
        }
        fclose($file);

        if (!empty($this->errors)) {
            throw ValidationException::withMessages($this->errors);
        }

        return $data;
    }
}
