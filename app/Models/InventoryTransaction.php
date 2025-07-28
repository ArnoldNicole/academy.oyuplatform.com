<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'balance_after',
        'unit_cost',
        'total_cost',
        'reference_number',
        'reason',
        'description',
        'recipient_id',
        'recipient_type',
        'department',
        'transaction_date',
        'school_id',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    protected $appends = [
        'type_badge',
        'reason_text'
    ];

    // Relationships
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Scopes
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeForItem($query, $itemId)
    {
        return $query->where('inventory_item_id', $itemId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('transaction_date', '>=', now()->subDays($days));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('transaction_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }

    // Accessors
    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'in' => 'success',
            'out' => 'danger',
            'adjustment' => 'warning',
            'transfer' => 'info',
            'loss' => 'dark',
            'damage' => 'secondary',
            default => 'light'
        };
    }

    public function getReasonTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->reason));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->school_id = $model->school_id ?? Auth::user()->school_id;
            $model->created_by = $model->created_by ?? Auth::id();
        });
    }
}
