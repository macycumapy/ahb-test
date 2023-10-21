<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Nomenclature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @mixin Nomenclature
 */
class NomenclatureBuilder extends Builder
{
    public function getPaginatedList(int $page = 1): LengthAwarePaginator
    {
        return $this
            ->with(['measurement', 'type.parent.parent'])
            ->paginate(50, '*', 'page', $page);
    }
}
