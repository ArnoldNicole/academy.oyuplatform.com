<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of inventory items
     */
    public function index()
    {
        $request = request();

        $schoolId = Auth::user()->school_id;
        
        $items = InventoryItem::forSchool($schoolId)
            ->with(['createdBy', 'updatedBy'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('supplier', 'like', "%{$search}%");
            })
            ->when($request->category, function($query, $category) {
                $query->byCategory($category);
            })
            ->when($request->status, function($query, $status) {
                switch($status) {
                    case 'low_stock':
                        $query->lowStock();
                        break;
                    case 'out_of_stock':
                        $query->outOfStock();
                        break;
                    case 'over_stock':
                        $query->overStock();
                        break;
                    case 'expiring_soon':
                        $query->expiringSoon();
                        break;
                    case 'expired':
                        $query->expired();
                        break;
                    case 'active':
                        $query->active();
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                }
            })
            ->orderBy('name')
            ->paginate(15);

        // Statistics
        $stats = [
            'total_items' => InventoryItem::forSchool($schoolId)->count(),
            'active_items' => InventoryItem::forSchool($schoolId)->active()->count(),
            'low_stock_items' => InventoryItem::forSchool($schoolId)->lowStock()->count(),
            'out_of_stock_items' => InventoryItem::forSchool($schoolId)->outOfStock()->count(),
            'total_value' => InventoryItem::forSchool($schoolId)->sum(DB::raw('current_stock * unit_cost')),
            'expiring_items' => InventoryItem::forSchool($schoolId)->expiringSoon()->count()
        ];

        return view('inventory.index', compact('items', 'stats'));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:inventory_items,code',
            'description' => 'nullable|string',
            'category' => 'required|in:stationery,books,equipment,furniture,electronics,sports,medical,cleaning,food,uniform,other',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('inventory', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $item = InventoryItem::create($validated);

        // Record initial stock transaction if stock > 0
        if ($item->current_stock > 0) {
            $item->addStock($item->current_stock, 'purchase', 'Initial stock entry', $item->unit_cost);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item
     */
    public function show(InventoryItem $inventory)
    {
        $this->authorize('view', $inventory);

        $inventory->load(['createdBy', 'updatedBy']);

        // Recent transactions
        $recentTransactions = $inventory->transactions()
            ->with(['createdBy', 'recipient'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        // Statistics
        $stats = [
            'total_transactions' => $inventory->transactions()->count(),
            'stock_in' => $inventory->transactions()->byType('in')->sum('quantity'),
            'stock_out' => $inventory->transactions()->byType('out')->sum('quantity'),
            'current_value' => $inventory->total_value,
            'last_transaction' => $inventory->transactions()->orderBy('transaction_date', 'desc')->first()
        ];

        return view('inventory.show', compact('inventory', 'recentTransactions', 'stats'));
    }

    /**
     * Show the form for editing the specified inventory item
     */
    public function edit(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:255', Rule::unique('inventory_items')->ignore($inventory->id)],
            'description' => 'nullable|string',
            'category' => 'required|in:stationery,books,equipment,furniture,electronics,sports,medical,cleaning,food,uniform,other',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('inventory', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $inventory->update($validated);

        return redirect()->route('inventory.show', $inventory)
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy(InventoryItem $inventory)
    {
        $this->authorize('delete', $inventory);

        if ($inventory->current_stock > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete item with stock. Please adjust stock to zero first.'
            ]);
        }

        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Stock adjustment form
     */
    public function adjustStock(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        
        return view('inventory.adjust-stock', compact('inventory'));
    }

    /**
     * Process stock adjustment
     */
    public function processStockAdjustment(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|in:adjustment,loss,damage,expired,found',
            'description' => 'required|string|max:500'
        ]);

        try {
            $inventory->adjustStock(
                $validated['new_quantity'],
                $validated['reason'],
                $validated['description']
            );

            return redirect()->route('inventory.show', $inventory)
                ->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Issue stock form
     */
    public function issueStock(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        
        $users = User::where('school_id', Auth::user()->school_id)
                    ->orderBy('first_name')
                    ->get();
        
        return view('inventory.issue-stock', compact('inventory', 'users'));
    }

    /**
     * Process stock issue
     */
    public function processStockIssue(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->current_stock,
            'recipient_id' => 'nullable|exists:users,id',
            'recipient_type' => 'required|in:student,teacher,staff,department',
            'department' => 'nullable|string|max:255',
            'description' => 'required|string|max:500'
        ]);

        try {
            $inventory->removeStock(
                $validated['quantity'],
                'issue',
                $validated['description'],
                $validated['recipient_id'],
                $validated['recipient_type'],
                $validated['department']
            );

            return redirect()->route('inventory.show', $inventory)
                ->with('success', 'Stock issued successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Add stock form
     */
    public function addStock(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        
        return view('inventory.add-stock', compact('inventory'));
    }

    /**
     * Process add stock
     */
    public function processAddStock(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'reason' => 'required|in:purchase,return,donation,transfer_in',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $inventory->addStock(
                $validated['quantity'],
                $validated['reason'],
                $validated['description'],
                $validated['unit_cost'],
                $validated['reference_number']
            );

            return redirect()->route('inventory.show', $inventory)
                ->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Inventory transactions history
     */
    public function transactions(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $transactions = InventoryTransaction::forSchool($schoolId)
            ->with(['inventoryItem', 'createdBy', 'recipient'])
            ->when($request->search, function($query, $search) {
                $query->whereHas('inventoryItem', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function($query, $type) {
                $query->byType($type);
            })
            ->when($request->reason, function($query, $reason) {
                $query->byReason($reason);
            })
            ->when($request->date_from, function($query, $dateFrom) {
                $query->where('transaction_date', '>=', $dateFrom);
            })
            ->when($request->date_to, function($query, $dateTo) {
                $query->where('transaction_date', '<=', $dateTo);
            })
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        return view('inventory.transactions', compact('transactions'));
    }

    /**
     * Dashboard/Overview
     */
    public function dashboard()
    {
        $schoolId = Auth::user()->school_id;

        $stats = [
            'total_items' => InventoryItem::forSchool($schoolId)->count(),
            'active_items' => InventoryItem::forSchool($schoolId)->active()->count(),
            'low_stock_items' => InventoryItem::forSchool($schoolId)->lowStock()->count(),
            'out_of_stock_items' => InventoryItem::forSchool($schoolId)->outOfStock()->count(),
            'total_value' => InventoryItem::forSchool($schoolId)->sum(DB::raw('current_stock * unit_cost')),
            'expiring_items' => InventoryItem::forSchool($schoolId)->expiringSoon()->count(),
            'expired_items' => InventoryItem::forSchool($schoolId)->expired()->count(),
            'recent_transactions' => InventoryTransaction::forSchool($schoolId)->recent(7)->count()
        ];

        // Recent transactions
        $recentTransactions = InventoryTransaction::forSchool($schoolId)
            ->with(['inventoryItem', 'createdBy', 'recipient'])
            ->recent(7)
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Low stock items
        $lowStockItems = InventoryItem::forSchool($schoolId)
            ->lowStock()
            ->active()
            ->orderBy('current_stock')
            ->limit(10)
            ->get();

        // Expiring items
        $expiringItems = InventoryItem::forSchool($schoolId)
            ->expiringSoon()
            ->active()
            ->orderBy('expiry_date')
            ->limit(10)
            ->get();

        return view('inventory.dashboard', compact('stats', 'recentTransactions', 'lowStockItems', 'expiringItems'));
    }
}