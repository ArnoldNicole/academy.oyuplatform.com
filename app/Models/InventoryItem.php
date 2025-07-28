<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'brand',
        'model',
        'unit',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit_cost',
        'selling_price',
        'supplier',
        'supplier_contact',
        'location',
        'specifications',
        'barcode',
        'expiry_date',
        'condition',
        'is_active',
        'notes',
        'image',
        'school_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'specifications' => 'array',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2'
    ];

    protected $appends = [
        'stock_status',
        'stock_status_badge',
        'category_name',
        'condition_badge',
        'total_value'
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function recentTransactions()
    {
        return $this->hasMany(InventoryTransaction::class)->orderBy('transaction_date', 'desc')->limit(10);
    }

    // Scopes
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', 0);
    }

    public function scopeOverStock($query)
    {
        return $query->whereColumn('current_stock', '>', 'maximum_stock')
                    ->whereNotNull('maximum_stock');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', now());
    }

    // Accessors
    public function getStockStatusAttribute()
    {
        if ($this->current_stock == 0) {
            return 'out_of_stock';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        } elseif ($this->maximum_stock && $this->current_stock > $this->maximum_stock) {
            return 'over_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusBadgeAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'over_stock' => 'info',
            'in_stock' => 'success',
            default => 'secondary'
        };
    }

    public function getCategoryNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getConditionBadgeAttribute()
    {
        return match($this->condition) {
            'new' => 'success',
            'good' => 'primary',
            'fair' => 'warning',
            'poor' => 'danger',
            'damaged' => 'dark',
            default => 'secondary'
        };
    }

    public function getTotalValueAttribute()
    {
        return $this->current_stock * $this->unit_cost;
    }

    // Methods
    public function addStock($quantity, $reason = 'purchase', $description = null, $unitCost = null, $referenceNumber = null)
    {
        $this->current_stock += $quantity;
        $this->save();

        return $this->recordTransaction('in', $quantity, $reason, $description, $unitCost, $referenceNumber);
    }

    public function removeStock($quantity, $reason = 'issue', $description = null, $recipientId = null, $recipientType = null, $department = null)
    {
        if ($this->current_stock < $quantity) {
            throw new \Exception('Insufficient stock. Available: ' . $this->current_stock);
        }

        $this->current_stock -= $quantity;
        $this->save();

        return $this->recordTransaction('out', $quantity, $reason, $description, null, null, $recipientId, $recipientType, $department);
    }

    public function adjustStock($newQuantity, $reason = 'adjustment', $description = null)
    {
        $difference = $newQuantity - $this->current_stock;
        $this->current_stock = $newQuantity;
        $this->save();

        return $this->recordTransaction('adjustment', $difference, $reason, $description);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function isOutOfStock()
    {
        return $this->current_stock == 0;
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && $this->expiry_date <= now()->addDays($days);
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    protected function recordTransaction($type, $quantity, $reason, $description = null, $unitCost = null, $referenceNumber = null, $recipientId = null, $recipientType = null, $department = null)
    {
        return InventoryTransaction::create([
            'inventory_item_id' => $this->id,
            'type' => $type,
            'quantity' => abs($quantity),
            'balance_after' => $this->current_stock,
            'unit_cost' => $unitCost ?? $this->unit_cost,
            'total_cost' => ($unitCost ?? $this->unit_cost) * abs($quantity),
            'reference_number' => $referenceNumber,
            'reason' => $reason,
            'description' => $description,
            'recipient_id' => $recipientId,
            'recipient_type' => $recipientType,
            'department' => $department,
            'transaction_date' => now()->toDateString(),
            'school_id' => $this->school_id,
            'created_by' => Auth::id()
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->school_id = $model->school_id ?? Auth::user()->school_id;
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
