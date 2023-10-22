<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UploadNomenclatureRequest;
use App\Http\Resources\NomenclaturePaginatorResource;
use App\Models\Nomenclature;
use App\Services\NomenclatureUploaderService\NomenclatureUploader;
use Exception;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    public function index(Request $request)
    {
        $data = Nomenclature::getPaginatedList($request->get('page', 1));

        return response()->json(NomenclaturePaginatorResource::make($data));
    }

    /**
     * @throws Exception
     */
    public function upload(UploadNomenclatureRequest $request, NomenclatureUploader $nomenclatureXlsxUploader)
    {
        $result = $nomenclatureXlsxUploader->upload($request->file);

        return $result
            ? response()->json(['message' => 'Номенклатура загружена'])
            : response()->json(['message' => 'Номенклатура не загружена'], 422);
    }
}
