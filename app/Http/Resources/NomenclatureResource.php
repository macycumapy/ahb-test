<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Nomenclature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Nomenclature
 */
class NomenclatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $level3 = $this->type;
        $level2 = $this->type?->parent;
        $level1 = $this->type?->parent?->parent;

        if (!$level2) {
            $level1 = $level3;
            $level2 = null;
            $level3 = null;
        }

        if (!$level1) {
            $level1 = $level2;
            $level2 = $level3;
            $level3 = null;
        }

        return [
            'code' => $this->code,
            'name' => $this->name,
            'level1' => $level1?->name,
            'level2' => $level2?->name,
            'level3' => $level3?->name,
            'price' => $this->price,
            'price_sp' => $this->price_sp,
            'quantity' => $this->quantity,
            'measurement' => $this->measurement?->name,
            'image_path' => $this->image_path,
            'properties' => $this->properties,
            'show_main' => $this->show_main,
            'description' => $this->description,
            'joint_purchases' => $this->joint_purchases,
        ];
    }
}
