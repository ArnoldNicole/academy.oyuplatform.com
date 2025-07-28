<?php

namespace App\Http\Controllers;

use App\Models\Dormitory;
use App\Models\DormitoryAllocation;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DormitoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of dormitories
     */
    public function index()
    {
        $request = request();
        $schoolId = Auth::user()->school_id;
        
        $dormitories = Dormitory::forSchool($schoolId)
            ->with(['activeAllocations.student'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('building', 'like', "%{$search}%")
                      ->orWhere('supervisor_name', 'like', "%{$search}%");
            })
            ->when($request->gender, function($query, $gender) {
                $query->byGender($gender);
            })
            ->when($request->status, function($query, $status) {
                if ($status === 'active') {
                    $query->active();
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($status === 'available') {
                    $query->available();
                } elseif ($status === 'full') {
                    $query->whereRaw('occupied >= capacity');
                }
            })
            ->orderBy('name')
            ->paginate(15);

        // Statistics
        $stats = [
            'total' => Dormitory::forSchool($schoolId)->count(),
            'active' => Dormitory::forSchool($schoolId)->active()->count(),
            'total_capacity' => Dormitory::forSchool($schoolId)->sum('capacity'),
            'total_occupied' => Dormitory::forSchool($schoolId)->sum('occupied'),
            'available_beds' => Dormitory::forSchool($schoolId)->selectRaw('SUM(capacity - occupied) as available')->value('available') ?? 0
        ];

        return view('dormitories.index', compact('dormitories', 'stats'));
    }

    /**
     * Show the form for creating a new dormitory
     */
    public function create()
    {
        return view('dormitories.create');
    }

    /**
     * Store a newly created dormitory
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gender' => 'required|in:male,female,mixed',
            'capacity' => 'required|integer|min:1|max:1000',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'monthly_fee' => 'required|numeric|min:0',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:20',
            'supervisor_email' => 'nullable|email|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['school_id'] = Auth::user()->school_id;
        $validated['facilities'] = $request->facilities ?? [];
        $validated['is_active'] = $request->has('is_active');

        Dormitory::create($validated);

        return redirect()->route('dormitories.index')
            ->with('success', 'Dormitory created successfully.');
    }

    /**
     * Display the specified dormitory
     */
    public function show(Dormitory $dormitory)
    {
        $this->authorize('view', $dormitory);

        $dormitory->load([
            'activeAllocations.student',
            'activeAllocations.allocatedBy'
        ]);

        // Recent allocations history
        $recentAllocations = $dormitory->allocations()
            ->with(['student', 'allocatedBy', 'checkedOutBy'])
            ->orderBy('allocated_date', 'desc')
            ->paginate(10);

        // Statistics
        $stats = [
            'total_allocations' => $dormitory->allocations()->count(),
            'active_allocations' => $dormitory->activeAllocations()->count(),
            'monthly_revenue' => $dormitory->activeAllocations()->sum('total_fees'),
            'occupancy_rate' => $dormitory->occupancy_rate,
            'average_stay_days' => $dormitory->allocations()
                ->whereNotNull('actual_checkout_date')
                ->selectRaw('AVG(DATEDIFF(actual_checkout_date, allocated_date)) as avg_days')
                ->value('avg_days') ?? 0
        ];

        return view('dormitories.show', compact('dormitory', 'recentAllocations', 'stats'));
    }

    /**
     * Show the form for editing the specified dormitory
     */
    public function edit(Dormitory $dormitory)
    {
        $this->authorize('update', $dormitory);
        
        return view('dormitories.edit', compact('dormitory'));
    }

    /**
     * Update the specified dormitory
     */
    public function update(Request $request, Dormitory $dormitory)
    {
        $this->authorize('update', $dormitory);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gender' => 'required|in:male,female,mixed',
            'capacity' => 'required|integer|min:1|max:1000',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'monthly_fee' => 'required|numeric|min:0',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:20',
            'supervisor_email' => 'nullable|email|max:255',
            'is_active' => 'boolean'
        ]);

        // Validate capacity is not less than current occupancy
        if ($validated['capacity'] < $dormitory->occupied) {
            return back()->withErrors([
                'capacity' => 'Capacity cannot be less than current occupancy (' . $dormitory->occupied . ')'
            ]);
        }

        $validated['facilities'] = $request->facilities ?? [];
        $validated['is_active'] = $request->has('is_active');

        $dormitory->update($validated);

        return redirect()->route('dormitories.show', $dormitory)
            ->with('success', 'Dormitory updated successfully.');
    }

    /**
     * Remove the specified dormitory
     */
    public function destroy(Dormitory $dormitory)
    {
        $this->authorize('delete', $dormitory);

        if ($dormitory->occupied > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete dormitory with active allocations. Please transfer or checkout all students first.'
            ]);
        }

        $dormitory->delete();

        return redirect()->route('dormitories.index')
            ->with('success', 'Dormitory deleted successfully.');
    }

    /**
     * Show allocation form
     */
    public function allocate(Dormitory $dormitory)
    {
        $this->authorize('update', $dormitory);

        if (!$dormitory->canAllocate()) {
            return back()->withErrors([
                'error' => 'This dormitory is full or inactive.'
            ]);
        }

        $students = Students::where('school_id', $dormitory->school_id)
            ->with('user')
            ->whereDoesntHave('dormitoryAllocations', function($query) {
                $query->where('status', 'active');
            })
            ->when($dormitory->gender !== 'mixed', function($query) use ($dormitory) {
                $query->whereHas('user', function($q) use ($dormitory) {
                    $q->where('gender', $dormitory->gender);
                });
            })
            ->get()
            ->sortBy('user.first_name');

        return view('dormitories.allocate', compact('dormitory', 'students'));
    }

    /**
     * Store student allocation
     */
    public function storeAllocation(Request $request, Dormitory $dormitory)
    {
        $this->authorize('update', $dormitory);

        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('dormitory_allocations')->where(function ($query) {
                    return $query->where('status', 'active');
                }),
            ],
            'bed_number' => 'nullable|string|max:50',
            'expected_checkout_date' => 'nullable|date|after:today',
            'allocation_notes' => 'nullable|string'
        ]);

        if (!$dormitory->canAllocate()) {
            return back()->withErrors([
                'error' => 'This dormitory is full or inactive.'
            ]);
        }

        $student = Students::with('user')->find($validated['student_id']);
        
        // Check gender compatibility
        if ($dormitory->gender !== 'mixed' && $student->user->gender !== $dormitory->gender) {
            return back()->withErrors([
                'student_id' => 'Student gender does not match dormitory requirements.'
            ]);
        }

        $allocation = $dormitory->allocateStudent(
            $student->id,
            Auth::id(),
            $validated['bed_number'] ?? null,
            $validated['allocation_notes'] ?? null
        );

        if ($validated['expected_checkout_date']) {
            $allocation->update(['expected_checkout_date' => $validated['expected_checkout_date']]);
        }

        return redirect()->route('dormitories.show', $dormitory)
            ->with('success', "Student {$student->full_name} allocated successfully.");
    }

    /**
     * Checkout student
     */
    public function checkout(Request $request, DormitoryAllocation $allocation)
    {
        $this->authorize('update', $allocation->dormitory);

        $validated = $request->validate([
            'checkout_notes' => 'nullable|string'
        ]);

        if ($allocation->checkout(Auth::id(), $validated['checkout_notes'])) {
            return back()->with('success', 'Student checked out successfully.');
        }

        return back()->withErrors(['error' => 'Unable to checkout student.']);
    }

    /**
     * Transfer student to another dormitory
     */
    public function transfer(Request $request, DormitoryAllocation $allocation)
    {
        $this->authorize('update', $allocation->dormitory);

        $validated = $request->validate([
            'new_dormitory_id' => 'required|exists:dormitories,id',
            'transfer_notes' => 'nullable|string'
        ]);

        $newDormitory = Dormitory::find($validated['new_dormitory_id']);
        
        if (!$newDormitory->canAllocate()) {
            return back()->withErrors(['error' => 'Target dormitory is full or inactive.']);
        }

        if ($allocation->transfer($validated['new_dormitory_id'], Auth::id(), $validated['transfer_notes'])) {
            return back()->with('success', 'Student transferred successfully.');
        }

        return back()->withErrors(['error' => 'Unable to transfer student.']);
    }

    /**
     * Show allocation history
     */
    public function history(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $allocations = DormitoryAllocation::forSchool($schoolId)
            ->with(['student', 'dormitory', 'allocatedBy', 'checkedOutBy'])
            ->when($request->search, function($query, $search) {
                $query->whereHas('student', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('admission_no', 'like', "%{$search}%");
                })->orWhereHas('dormitory', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $query->byStatus($status);
            })
            ->when($request->dormitory_id, function($query, $dormitoryId) {
                $query->forDormitory($dormitoryId);
            })
            ->when($request->date_from, function($query, $dateFrom) {
                $query->where('allocated_date', '>=', $dateFrom);
            })
            ->when($request->date_to, function($query, $dateTo) {
                $query->where('allocated_date', '<=', $dateTo);
            })
            ->orderBy('allocated_date', 'desc')
            ->paginate(20);

        $dormitories = Dormitory::forSchool($schoolId)->orderBy('name')->get();

        return view('dormitories.history', compact('allocations', 'dormitories'));
    }

    /**
     * Dashboard/Overview
     */
    public function dashboard()
    {
        $schoolId = Auth::user()->school_id;

        $stats = [
            'total_dormitories' => Dormitory::forSchool($schoolId)->count(),
            'active_dormitories' => Dormitory::forSchool($schoolId)->active()->count(),
            'total_capacity' => Dormitory::forSchool($schoolId)->sum('capacity'),
            'total_occupied' => Dormitory::forSchool($schoolId)->sum('occupied'),
            'active_allocations' => DormitoryAllocation::forSchool($schoolId)->active()->count(),
            'overdue_checkouts' => DormitoryAllocation::forSchool($schoolId)->overdue()->count(),
            'monthly_revenue' => DormitoryAllocation::forSchool($schoolId)->active()->sum('total_fees'),
            'outstanding_fees' => DormitoryAllocation::forSchool($schoolId)
                ->active()
                ->selectRaw('SUM(total_fees - paid_fees) as outstanding')
                ->value('outstanding') ?? 0
        ];

        // Recent allocations
        $recentAllocations = DormitoryAllocation::forSchool($schoolId)
            ->with(['student', 'dormitory', 'allocatedBy'])
            ->recent(7)
            ->orderBy('allocated_date', 'desc')
            ->limit(10)
            ->get();

        // Occupancy by dormitory
        $occupancyData = Dormitory::forSchool($schoolId)
            ->active()
            ->select('name', 'capacity', 'occupied')
            ->orderBy('name')
            ->get();

        // Overdue items
        $overdueItems = DormitoryAllocation::forSchool($schoolId)
            ->overdue()
            ->with(['student', 'dormitory'])
            ->limit(10)
            ->get();

        return view('dormitories.dashboard', compact('stats', 'recentAllocations', 'occupancyData', 'overdueItems'));
    }

}
