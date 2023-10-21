<?php

declare(strict_types=1);

namespace App\Services\NomenclatureUploaderService\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class NomenclatureData extends Data
{
    public string $code;
    public string $name;
    public ?string $level1 = null;
    public ?string $level2 = null;
    public ?string $level3 = null;
    public ?float $price;
    public ?float $priceSP;
    public ?float $quantity;
    public ?string $properties;
    public bool $jointPurchases;
    public string $measurement;
    public ?string $image;
    public bool $showMain = false;
    public ?string $description;

    public static function rules(ValidationContext $context): array
    {
        return [
            'code' => ['required', 'string', 'max:8'],
            'name' => ['required', 'string', 'max:255'],
            'level1' => ['nullable', 'string', 'max:255'],
            'level2' => ['nullable', 'string', 'max:255'],
            'level3' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'price_sp' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'quantity' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'properties' => ['nullable', 'string', 'max:500'],
            'jointPurchases' => ['nullable', 'boolean'],
            'measurement' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'showMain' => ['required', 'boolean'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
