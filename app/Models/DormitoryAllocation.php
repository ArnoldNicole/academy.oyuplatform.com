<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DormitoryAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'dormitory_id',
        'school_id',
        'bed_number',
        'allocated_date',
        'expected_checkout_date',
        'actual_checkout_date',
        'status',
        'allocation_notes',
        'checkout_notes',
        'total_fees',
        'paid_fees',
        'payment_status',
        'allocated_by',
        'checked_out_by'
    ];

    protected $casts = [
        'allocated_date' => 'date',
        'expected_checkout_date' => 'date',
        'actual_checkout_date' => 'date',
        'total_fees' => 'decimal:2',
        'paid_fees' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(Dormitory::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function allocatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'allocated_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'checked_out_by');
    }

    // Accessors & Mutators
    public function getOutstandingFeesAttribute(): float
    {
        return $this->total_fees - $this->paid_fees;
    }

    public function getDurationDaysAttribute(): int
    {
        $endDate = $this->actual_checkout_date ?? $this->expected_checkout_date ?? now();
        return $this->allocated_date->diffInDays($endDate);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'checked_out' => 'secondary',
            'transferred' => 'info',
            'suspended' => 'warning',
            default => 'primary'
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'partial' => 'warning',
            'pending' => 'info',
            'overdue' => 'danger',
            default => 'secondary'
        };
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->payment_status === 'overdue' || 
               ($this->expected_checkout_date && 
                $this->expected_checkout_date->isPast() && 
                !$this->actual_checkout_date);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForDormitory($query, $dormitoryId)
    {
        return $query->where('dormitory_id', $dormitoryId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue')
                    ->orWhere(function($q) {
                        $q->whereNotNull('expected_checkout_date')
                          ->where('expected_checkout_date', '<', now())
                          ->whereNull('actual_checkout_date');
                    });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('allocated_date', '>=', now()->subDays($days));
    }

    // Methods
    public function checkout($checkedOutBy, $notes = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->update([
            'status' => 'checked_out',
            'actual_checkout_date' => now(),
            'checkout_notes' => $notes,
            'checked_out_by' => $checkedOutBy
        ]);

        // Update dormitory occupancy
        $this->dormitory->decrement('occupied');

        return true;
    }

    public function transfer($newDormitoryId, $transferredBy, $notes = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $newDormitory = Dormitory::find($newDormitoryId);
        if (!$newDormitory || !$newDormitory->canAllocate()) {
            return false;
        }

        // Mark current allocation as transferred
        $this->update([
            'status' => 'transferred',
            'actual_checkout_date' => now(),
            'checkout_notes' => $notes,
            'checked_out_by' => $transferredBy
        ]);

        // Create new allocation
        $newAllocation = static::create([
            'student_id' => $this->student_id,
            'dormitory_id' => $newDormitoryId,
            'school_id' => $this->school_id,
            'allocated_date' => now(),
            'allocation_notes' => "Transferred from {$this->dormitory->name}. {$notes}",
            'total_fees' => $newDormitory->monthly_fee,
            'allocated_by' => $transferredBy,
            'status' => 'active'
        ]);

        // Update dormitory occupancies
        $this->dormitory->decrement('occupied');
        $newDormitory->increment('occupied');

        return true;
    }

    public function makePayment($amount, $notes = null): bool
    {
        if ($amount <= 0 || $amount > $this->outstanding_fees) {
            return false;
        }

        $this->increment('paid_fees', $amount);

        // Update payment status
        if ($this->paid_fees >= $this->total_fees) {
            $this->update(['payment_status' => 'paid']);
        } elseif ($this->paid_fees > 0) {
            $this->update(['payment_status' => 'partial']);
        }

        return true;
    }

    public function suspend($suspendedBy, $notes = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->update([
            'status' => 'suspended',
            'checkout_notes' => $notes,
            'checked_out_by' => $suspendedBy
        ]);

        return true;
    }

    public function reactivate($reactivatedBy, $notes = null): bool
    {
        if ($this->status !== 'suspended') {
            return false;
        }

        $this->update([
            'status' => 'active',
            'allocation_notes' => $this->allocation_notes . "\nReactivated: {$notes}",
            'allocated_by' => $reactivatedBy
        ]);

        return true;
    }
}
