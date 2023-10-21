<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @mixin LengthAwarePaginator
 */
class NomenclaturePaginatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => NomenclatureResource::collection($this->resource),
            'pages' => $this->lastPage(),
            'current_page' => $this->currentPage(),
            'links' => $this->linkCollection(),
        ];
    }
}
