<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EstimationItem extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'estimation_items';

    protected $fillable = [
        'estimation_id',
        'category',
        'reference_id',
        'code',
        'coefficient',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'coefficient' => 'decimal:3',
        'unit_price' => 'integer',
        'total_price' => 'integer',
    ];

    public function estimation()
    {
        return $this->belongsTo(Estimation::class, 'estimation_id');
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class, 'estimation_item_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'reference_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'reference_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'reference_id');
    }

    public function hppItems(): MorphMany
    {
        return $this->morphMany(HppItem::class, 'item');
    }

    /**
     * Get classification TKDN from master data
     */
    public function getClassificationTkdnAttribute(): ?string
    {
        switch ($this->category) {
            case 'worker':
                return $this->worker?->classification_tkdn;
            case 'material':
                return $this->material?->classification_tkdn;
            case 'equipment':
                return $this->equipment?->classification_tkdn;
            default:
                return null;
        }
    }

    /**
     * Scope untuk filter berdasarkan project type
     */
    public function scopeForProjectType($query, string $projectType)
    {
        // Menggunakan integer classification sesuai dengan StringHelper mapping
        $classifications = $projectType === 'tkdn_jasa'
            ? [1, 2, 3, 4] // Overhead & Manajemen, Alat Kerja / Fasilitas, Konstruksi & Fabrikasi, Peralatan (Jasa Umum)
            : [1, 2, 3, 4, 5, 6]; // Semua classification termasuk Material (Bahan Baku) dan Peralatan (Barang Jadi)

        return $query->where(function ($q) use ($classifications) {
            $q->whereHas('worker', function ($workerQuery) use ($classifications) {
                $workerQuery->whereIn('classification_tkdn', $classifications);
            })->orWhereHas('material', function ($materialQuery) use ($classifications) {
                $materialQuery->whereIn('classification_tkdn', $classifications);
            })->orWhereHas('equipment', function ($equipmentQuery) use ($classifications) {
                $equipmentQuery->whereIn('classification_tkdn', $classifications);
            });
        });
    }
}
