<?php

namespace App\Models;

use App\Helpers\StringHelper;
use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Material extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'material';

    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'name',
        'specification',
        'category_id',
        'classification_tkdn',
        'brand',
        'type',
        'tkdn',
        'price',
        'unit',
        'link',
        'negara_asal',
        'price_inflasi',
        'description',
        'location',
    ];

    protected $casts = [
        // 'classification_tkdn' => 'integer',
        // 'tkdn' => 'decimal:2',
    ];

    /**
     * Get available TKDN classification options
     */
    public static function getClassificationOptions(): array
    {
        return [
            // 1 => 'Overhead & Manajemen',
            2 => 'Alat Kerja / Fasilitas',
            3 => 'Konstruksi & Fabrikasi',
            4 => 'Peralatan (Jasa Umum)',
            5 => 'Material (Bahan Baku)',
            6 => 'Peralatan (Barang Jadi)',
            // 7 => 'Summary',
        ];
    }

    public function hppItems(): MorphMany
    {
        return $this->morphMany(HppItem::class, 'item');
    }

    /**
     * Get classification TKDN as string
     */
    public function getClassificationTkdnStringAttribute(): ?string
    {
        return StringHelper::intToClassificationTkdn($this->classification_tkdn);
    }

    /**
     * Set classification TKDN from string
     */
    public function setClassificationTkdnStringAttribute(?string $value): void
    {
        $this->attributes['classification_tkdn'] = StringHelper::classificationTkdnToInt($value);
    }



    public function getRouteKeyName()
    {
        return 'id';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
