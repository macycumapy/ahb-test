<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Measurement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
