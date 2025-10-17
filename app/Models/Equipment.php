<?php

namespace App\Models;

use App\Helpers\StringHelper;
use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'equipment';

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'classification_tkdn',
        'tkdn',
        'type',
        'period',
        'price',
        'description',
        'location',
        'spesifikasi',
        'dibuat',
        'dimiliki',
        'satuan',
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

    /**
     * Get form numbers for a classification based on project type
     */
    public static function getFormNumbersForClassification(int $classification, ?string $projectType = null): array
    {
        return match ($classification) {
            1 => $projectType === 'tkdn_jasa' ? ['3.1'] : ($projectType === 'tkdn_barang_jasa' ? ['4.3'] : ['3.1', '4.3']), // Overhead & Manajemen
            2 => $projectType === 'tkdn_jasa' ? ['3.2'] : ($projectType === 'tkdn_barang_jasa' ? ['4.4'] : ['3.2', '4.4']), // Alat Kerja / Fasilitas
            3 => $projectType === 'tkdn_jasa' ? ['3.3'] : ($projectType === 'tkdn_barang_jasa' ? ['4.5'] : ['3.3', '4.5']), // Konstruksi & Fabrikasi
            4 => $projectType === 'tkdn_jasa' ? ['3.4'] : ($projectType === 'tkdn_barang_jasa' ? ['4.6'] : ['3.4', '4.6']), // Peralatan (Jasa Umum)
            5 => $projectType === 'tkdn_barang_jasa' ? ['4.1'] : [], // Material (Bahan Baku)
            6 => $projectType === 'tkdn_barang_jasa' ? ['4.2'] : [], // Peralatan (Barang Jadi)
            7 => [], // Summary
            default => [],
        };
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Check if equipment is disposable (single use)
     */
    public function isDisposable()
    {
        return $this->period == 0;
    }

    /**
     * Get equipment type label
     */
    public function getTypeLabel()
    {
        return $this->isDisposable() ? 'Sekali Pakai' : 'Dapat Dipakai Ulang';
    }

    /**
     * Get equipment type badge class
     */
    public function getTypeBadgeClass()
    {
        return $this->isDisposable() ? 'badge-danger' : 'badge-success';
    }
}
