<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory, UsesUlid;

    protected $appends = ['estimation_category', 'classification_tkdn'];


    protected $fillable = [
        'service_id',
        'estimation_item_id',
        'item_number',
        'tkdn_classification',
        'description',
        'qualification',
        'nationality',
        'tkdn_percentage',
        'quantity',
        'duration',
        'duration_unit',
        'wage',
        'domestic_cost',
        'foreign_cost',
        'total_cost',
    ];

    protected $casts = [
        'estimation_item_id' => 'string', // ULID is stored as string
        'tkdn_percentage' => 'decimal:2',
        'quantity' => 'integer',
        'duration' => 'decimal:2',
        'wage' => 'decimal:2',
        'domestic_cost' => 'decimal:2',
        'foreign_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function estimationItem()
    {
        return $this->belongsTo(EstimationItem::class, 'estimation_item_id');
    }

    public function getEstimationCategoryAttribute()
    {
        return $this->estimationItem ? $this->estimationItem->category : null;
    }

    public function getClassificationTkdnAttribute()
    {
        if (!$this->estimationItem) {
            return null;
        }

        // Ambil classification_tkdn berdasarkan kategori dari tabel yang sesuai
        $category = $this->estimationItem->category;

        if (in_array($category, ['worker', 'pekerja'])) {
            $worker = $this->estimationItem->worker;
            return $worker ? $worker->classification_tkdn : null;
        }

        if ($category === 'material') {
            $material = $this->estimationItem->material;
            return $material ? $material->classification_tkdn : null;
        }

        if (in_array($category, ['equipment', 'peralatan', 'elektrika'])) {
            $equipment = $this->estimationItem->equipment;
            return $equipment ? $equipment->classification_tkdn : null;
        }

        if ($category === 'hse') {
            // Jika HSE merujuk ke worker table atau equipment table
            $worker = $this->estimationItem->worker;
            if ($worker && $worker->classification_tkdn) {
                return $worker->classification_tkdn;
            }

            $equipment = $this->estimationItem->equipment;
            return $equipment ? $equipment->classification_tkdn : null;
        }

        return null;
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function estimationItems()
    {
        return $this->hasMany(EstimationItem::class, 'id', 'estimation_item_id');
    }

    public function calculateCosts()
    {
        $totalWage = $this->wage * $this->quantity * $this->duration;

        if ($this->tkdn_percentage == 100) {
            $this->domestic_cost = $totalWage;
            $this->foreign_cost = 0;
        } elseif ($this->tkdn_percentage == 0) {
            $this->domestic_cost = 0;
            $this->foreign_cost = $totalWage;
        } else {
            $this->domestic_cost = ($totalWage * $this->tkdn_percentage) / 100;
            $this->foreign_cost = $totalWage - $this->domestic_cost;
        }

        $this->total_cost = $this->domestic_cost + $this->foreign_cost;
        $this->save();
    }

    public function getFormattedWage()
    {
        return number_format($this->wage, 0, ',', '.');
    }

    public function getFormattedDomesticCost()
    {
        return number_format($this->domestic_cost, 0, ',', '.');
    }

    public function getFormattedForeignCost()
    {
        return number_format($this->foreign_cost, 0, ',', '.');
    }

    public function getFormattedTotalCost()
    {
        return number_format($this->total_cost, 0, ',', '.');
    }
}
