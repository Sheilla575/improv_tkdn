<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HppItem extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'hpp_items';

    protected $fillable = [
        'hpp_id',
        'hpp_ahs_id',
        'item_type',
        'item_id',
        'estimation_item_id',
        'name_ahs',
        'item_number',
        'description',
        'volume',
        'unit',
        'duration',
        'duration_unit',
        'koefisien',
        'jumlah',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function hpp()
    {
        return $this->belongsTo(Hpp::class, 'hpp_id');
    }

    public function hppAhs()
    {
        return $this->belongsTo(HppAhs::class, 'hpp_ahs_id');
    }

    public function estimationItem()
    {
        return $this->belongsTo(EstimationItem::class, 'estimation_item_id');
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get estimation through estimation item
     */
    public function estimation()
    {
        return $this->hasOneThrough(
            Estimation::class,
            EstimationItem::class,
            'id',           // Foreign key on estimation_items table
            'id', // Foreign key on estimations table
            'estimation_item_id', // Local key on hpp_items table
            'estimation_id'       // Local key on estimation_items table
        );
    }
}
