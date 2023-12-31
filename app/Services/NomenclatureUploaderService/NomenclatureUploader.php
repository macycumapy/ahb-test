<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService;

use App\Models\Measurement;
use App\Models\Nomenclature;
use App\Models\NomenclatureType;
use App\Services\NomenclatureUploaderService\Data\NomenclatureData;
use App\Services\NomenclatureUploaderService\Parsers\NomenclatureParserFactory;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

class NomenclatureUploader
{
    public function __construct(private readonly NomenclatureParserFactory $parserFactory)
    {
    }

    /**
     * @throws Exception
     */
    public function upload(UploadedFile $uploadedFile): bool
    {
        $filepath = $this->saveTmpFile($uploadedFile);

        $parser = $this->parserFactory->getParser($filepath);
        $parsedDataCollection = $parser->parse($filepath);

        DB::beginTransaction();
        try {
            foreach ($parsedDataCollection as $data) {
                /** @var NomenclatureData $data */
                $measurement = $this->firstOrCreateMeasurement($data->measurement);
                $nomenclatureType = $this->firstOrCreateNomenclatureTypes($data);
                $this->firstOrCreateNomenclature($data, $measurement, $nomenclatureType);
            }
            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
        }

        return false;
    }

    private function saveTmpFile(UploadedFile $uploadedFile): string|bool
    {
        return storage_path(
            'app/' . $uploadedFile->storeAs('tmp', $uploadedFile->getClientOriginalName(), [
                'disk' => 'local',
            ])
        );
    }

    private function firstOrCreateMeasurement(string $measurementName): Measurement
    {
        return Measurement::firstOrCreate([
            'name' => $measurementName,
        ], [
            'name' => $measurementName,
        ]);
    }

    private function firstOrCreateNomenclatureTypes(NomenclatureData $data): ?NomenclatureType
    {
        if ($data->level1) {
            $nomenclatureType = $this->firstOrCreateNomenclatureType($data->level1);
            if ($data->level2) {
                $nomenclatureType = $this->firstOrCreateNomenclatureType($data->level2, $nomenclatureType);
                if ($data->level3) {
                    $nomenclatureType = $this->firstOrCreateNomenclatureType($data->level3, $nomenclatureType);
                }
            }
        }

        return $nomenclatureType ?? null;
    }

    private function firstOrCreateNomenclatureType(string $typeName, ?NomenclatureType $parent = null): NomenclatureType
    {
        return NomenclatureType::firstOrCreate([
            'name' => $typeName,
            'parent_id' => $parent?->id,
        ], [
            'name' => $typeName,
            'parent_id' => $parent?->id,
        ]);
    }

    private function firstOrCreateNomenclature(NomenclatureData $data, ?Measurement $measurement, ?NomenclatureType $type): void
    {
        Nomenclature::firstOrCreate([
            'code' => $data->code,
        ], [
            'code' => $data->code,
            'name' => $data->name,
            'price' => $data->price,
            'price_sp' => $data->priceSP,
            'quantity' => $data->quantity,
            'image_path' => $data->image,
            'show_main' => $data->showMain,
            'description' => $data->description,
            'properties' => $data->properties,
            'joint_purchases' => $data->jointPurchases,
            'measurement_id' => $measurement->id,
            'nomenclature_type_id' => $type->id,
        ]);
    }
}
