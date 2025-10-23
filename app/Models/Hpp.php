<?php

namespace App\Models;

use App\Traits\UsesUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hpp extends Model
{
    use HasFactory, UsesUlid;

    protected $table = 'hpps';

    protected $fillable = [
        'code',
        'project_id',
        'name_hpp',
        'hpp_type',
        'sub_total_hpp',
        'overhead_percentage',
        'overhead_amount',
        'margin_percentage',
        'margin_amount',
        'sub_total',
        'ppn_percentage',
        'ppn_amount',
        'grand_total',
        'notes',
        'status',
        'created_by',
        'updated_by',
        'approved_by',
        'rejected_by',
        'submitted_by',
        'approved_at',
        'rejected_at',
        'submitted_at',
        'approval_notes',
        'rejection_notes',
    ];

    protected $casts = [
        'overhead_percentage' => 'decimal:2',
        'overhead_amount' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'sub_total_hpp' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'ppn_percentage' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function ahs()
    {
        return $this->hasMany(HppAhs::class, 'hpp_id');
    }

    public function items()
    {
        return $this->hasMany(HppItem::class, 'hpp_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // User Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Relationship with HPP Logs
     */
    public function logs()
    {
        return $this->hasMany(HppLog::class, 'hpp_id')->orderBy('created_at', 'desc');
    }

    // Accessor untuk title dari project
    public function getTitleAttribute()
    {
        return $this->project->name ?? '';
    }

    // Accessor untuk company_name dari project
    public function getCompanyNameAttribute()
    {
        return $this->project->company ?? '';
    }

    // Accessor untuk work_description dari project
    public function getWorkDescriptionAttribute()
    {
        return $this->project->description ?? '';
    }
}
