<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\NomenclatureBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string code
 * @property string name
 * @property int nomenclature_type_id
 * @property int measurement_id
 * @property float price
 * @property float price_sp
 * @property float quantity
 * @property string|null image_path
 * @property boolean show_main
 * @property string|null properties
 * @property boolean joint_purchases
 * @property string|null description
 * @property-read NomenclatureType $type
 * @property-read Measurement measurement
 * @mixin NomenclatureBuilder
 */
class Nomenclature extends Model
{
    protected $fillable = [
        'code',
        'name',
        'price',
        'price_sp',
        'quantity',
        'image_path',
        'show_main',
        'description',
        'nomenclature_type_id',
        'measurement_id',
        'joint_purchases',
        'properties',
    ];

    protected $casts = [
        'price' => 'float',
        'price_sp' => 'float',
        'quantity' => 'float',
        'show_main' => 'boolean',
        'joint_purchases' => 'boolean',
    ];

    public function newEloquentBuilder($query): NomenclatureBuilder
    {
        return new NomenclatureBuilder($query);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(NomenclatureType::class, 'nomenclature_type_id');
    }

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }
}
