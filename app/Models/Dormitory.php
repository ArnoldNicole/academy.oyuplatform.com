<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dormitory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'gender',
        'capacity',
        'occupied',
        'building',
        'floor',
        'facilities',
        'monthly_fee',
        'supervisor_name',
        'supervisor_phone',
        'supervisor_email',
        'is_active',
        'school_id'
    ];

    protected $casts = [
        'facilities' => 'array',
        'monthly_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'occupied' => 'integer',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(DormitoryAllocation::class);
    }

    public function activeAllocations(): HasMany
    {
        return $this->hasMany(DormitoryAllocation::class)->where('status', 'active');
    }

    public function students(): HasMany
    {
        return $this->hasMany(DormitoryAllocation::class)->where('status', 'active');
    }

    // Accessors & Mutators
    public function getAvailableBedsAttribute(): int
    {
        return $this->capacity - $this->occupied;
    }

    public function getOccupancyRateAttribute(): float
    {
        return $this->capacity > 0 ? ($this->occupied / $this->capacity) * 100 : 0;
    }

    public function getStatusBadgeAttribute(): string
    {
        if (!$this->is_active) {
            return 'danger';
        }
        
        $occupancyRate = $this->occupancy_rate;
        
        if ($occupancyRate >= 90) {
            return 'danger';
        } elseif ($occupancyRate >= 75) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    public function getStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        $occupancyRate = $this->occupancy_rate;
        
        if ($occupancyRate >= 100) {
            return 'Full';
        } elseif ($occupancyRate >= 90) {
            return 'Nearly Full';
        } elseif ($occupancyRate >= 75) {
            return 'High Occupancy';
        } elseif ($occupancyRate >= 50) {
            return 'Moderate Occupancy';
        } else {
            return 'Available';
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('occupied < capacity')->where('is_active', true);
    }

    // Methods
    public function canAllocate(): bool
    {
        return $this->is_active && $this->available_beds > 0;
    }

    public function allocateStudent($studentId, $allocatedBy, $bedNumber = null, $notes = null): ?DormitoryAllocation
    {
        if (!$this->canAllocate()) {
            return null;
        }

        $allocation = DormitoryAllocation::create([
            'student_id' => $studentId,
            'dormitory_id' => $this->id,
            'school_id' => $this->school_id,
            'bed_number' => $bedNumber,
            'allocated_date' => now()->toDateString(),
            'allocation_notes' => $notes,
            'total_fees' => $this->monthly_fee,
            'allocated_by' => $allocatedBy,
            'status' => 'active'
        ]);

        $this->increment('occupied');

        return $allocation;
    }

    public function checkoutStudent($studentId, $checkedOutBy, $notes = null): bool
    {
        $allocation = $this->allocations()
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if (!$allocation) {
            return false;
        }

        $allocation->update([
            'status' => 'checked_out',
            'actual_checkout_date' => now()->toDateString(),
            'checkout_notes' => $notes,
            'checked_out_by' => $checkedOutBy
        ]);

        $this->decrement('occupied');

        return true;
    }
}
