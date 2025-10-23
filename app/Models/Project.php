<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, UsesUlid;

    protected $fillable = [
        'name',
        'project_type',
        'status',
        'start_date',
        'end_date',
        'category',
        'description',
        'company',
        'location',
    ];

    // Project types
    const TYPE_TKDN_JASA = 'tkdn_jasa';

    const TYPE_TKDN_BARANG_JASA = 'tkdn_barang_jasa';

    public static function getProjectTypes()
    {
        return [
            self::TYPE_TKDN_JASA => 'TKDN Jasa (Form 3.1 - 3.5)',
            self::TYPE_TKDN_BARANG_JASA => 'TKDN Barang & Jasa (Form 4.1 - 4.7)',
        ];
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function hpps()
    {
        return $this->hasMany(Hpp::class);
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
}
