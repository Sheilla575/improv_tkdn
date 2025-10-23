<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Estimation extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'estimations';

    protected $fillable = [
        'code',
        'title',
        'total',
        'total_unit_price',
    ];

    public function items()
    {
        return $this->hasMany(EstimationItem::class, 'estimation_id');
    }

    public function hppItems(): MorphMany
    {
        return $this->morphMany(HppItem::class, 'item');
    }
}
