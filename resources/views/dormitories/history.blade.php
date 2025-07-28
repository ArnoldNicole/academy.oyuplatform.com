@extends('layouts.master')

@section('title')
    {{ __('allocation_history') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('dormitory_allocation_history') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.index') }}">{{ __('dormitories') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('allocation_history') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('allocation_history') }}</h4>
                        
                        <!-- Filters -->
                        <form method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">{{ __('search') }}</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="{{ __('search_student_or_dormitory') }}" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="dormitory_id" class="form-label">{{ __('dormitory') }}</label>
                                    <select name="dormitory_id" id="dormitory_id" class="form-control">
                                        <option value="">{{ __('all_dormitories') }}</option>
                                        @foreach($dormitories as $dormitory)
                                            <option value="{{ $dormitory->id }}" {{ request('dormitory_id') == $dormitory->id ? 'selected' : '' }}>
                                                {{ $dormitory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">{{ __('status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">{{ __('all_status') }}</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                                        <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>{{ __('checked_out') }}</option>
                                        <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>{{ __('transferred') }}</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('suspended') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">{{ __('from_date') }}</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">{{ __('to_date') }}</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">{{ __('filter') }}</button>
                                    <a href="{{ route('dormitories.history') }}" class="btn btn-secondary">{{ __('clear') }}</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('student') }}</th>
                                        <th>{{ __('dormitory') }}</th>
                                        <th>{{ __('bed_number') }}</th>
                                        <th>{{ __('allocated_date') }}</th>
                                        <th>{{ __('checkout_date') }}</th>
                                        <th>{{ __('duration') }}</th>
                                        <th>{{ __('fees') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('allocated_by') }}</th>
                                        <th>{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allocations as $allocation)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $allocation->student->full_name }}</h6>
                                                    <small class="text-muted">{{ $allocation->student->admission_no }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $allocation->dormitory->name }}</strong>
                                                    @if($allocation->dormitory->building)
                                                        <br><small class="text-muted">{{ $allocation->dormitory->building }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                {{ $allocation->bed_number ?? __('not_assigned') }}
                                            </td>
                                            <td>{{ $allocation->allocated_date->format('M d, Y') }}</td>
                                            <td>
                                                @if($allocation->actual_checkout_date)
                                                    {{ $allocation->actual_checkout_date->format('M d, Y') }}
                                                @elseif($allocation->expected_checkout_date)
                                                    <small class="text-muted">{{ __('expected') }}: {{ $allocation->expected_checkout_date->format('M d, Y') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $allocation->duration_days }} {{ __('days') }}
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>${{ number_format($allocation->total_fees, 2) }}</strong>
                                                    @if($allocation->paid_fees > 0)
                                                        <br><small class="text-success">{{ __('paid') }}: ${{ number_format($allocation->paid_fees, 2) }}</small>
                                                    @endif
                                                    @if($allocation->outstanding_fees > 0)
                                                        <br><small class="text-danger">{{ __('due') }}: ${{ number_format($allocation->outstanding_fees, 2) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $allocation->status_badge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $allocation->status)) }}
                                                </span>
                                                @if($allocation->payment_status)
                                                    <br><span class="badge badge-{{ $allocation->payment_status_badge }} mt-1">
                                                        {{ ucfirst($allocation->payment_status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $allocation->allocatedBy->full_name ?? __('system') }}
                                                    <br><small class="text-muted">{{ $allocation->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                        {{ __('actions') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <button class="dropdown-item" data-toggle="modal" data-target="#viewModal{{ $allocation->id }}">
                                                            <i class="fa fa-eye"></i> {{ __('view_details') }}
                                                        </button>
                                                        
                                                        @if($allocation->status == 'active')
                                                            <button class="dropdown-item" data-toggle="modal" data-target="#checkoutModal{{ $allocation->id }}">
                                                                <i class="fa fa-sign-out-alt"></i> {{ __('checkout') }}
                                                            </button>
                                                            <button class="dropdown-item" data-toggle="modal" data-target="#transferModal{{ $allocation->id }}">
                                                                <i class="fa fa-exchange-alt"></i> {{ __('transfer') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Details Modal -->
                                        <div class="modal fade" id="viewModal{{ $allocation->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('allocation_details') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>{{ __('student_information') }}</h6>
                                                                <p><strong>{{ __('name') }}:</strong> {{ $allocation->student->full_name }}</p>
                                                                <p><strong>{{ __('admission_no') }}:</strong> {{ $allocation->student->admission_no }}</p>
                                                                <p><strong>{{ __('class') }}:</strong> {{ $allocation->student->class_section->full_name ?? __('not_assigned') }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>{{ __('dormitory_information') }}</h6>
                                                                <p><strong>{{ __('dormitory') }}:</strong> {{ $allocation->dormitory->name }}</p>
                                                                <p><strong>{{ __('building') }}:</strong> {{ $allocation->dormitory->building ?? __('not_specified') }}</p>
                                                                <p><strong>{{ __('bed_number') }}:</strong> {{ $allocation->bed_number ?? __('not_assigned') }}</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <h6>{{ __('allocation_details') }}</h6>
                                                                <p><strong>{{ __('allocated_date') }}:</strong> {{ $allocation->allocated_date->format('M d, Y') }}</p>
                                                                <p><strong>{{ __('allocated_by') }}:</strong> {{ $allocation->allocatedBy->full_name ?? __('system') }}</p>
                                                                @if($allocation->allocation_notes)
                                                                    <p><strong>{{ __('notes') }}:</strong> {{ $allocation->allocation_notes }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>{{ __('checkout_details') }}</h6>
                                                                @if($allocation->actual_checkout_date)
                                                                    <p><strong>{{ __('checkout_date') }}:</strong> {{ $allocation->actual_checkout_date->format('M d, Y') }}</p>
                                                                    <p><strong>{{ __('checked_out_by') }}:</strong> {{ $allocation->checkedOutBy->full_name ?? __('system') }}</p>
                                                                    @if($allocation->checkout_notes)
                                                                        <p><strong>{{ __('checkout_notes') }}:</strong> {{ $allocation->checkout_notes }}</p>
                                                                    @endif
                                                                @else
                                                                    <p class="text-muted">{{ __('not_checked_out') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <h6>{{ __('financial_information') }}</h6>
                                                                <p><strong>{{ __('total_fees') }}:</strong> ${{ number_format($allocation->total_fees, 2) }}</p>
                                                                <p><strong>{{ __('paid_fees') }}:</strong> ${{ number_format($allocation->paid_fees, 2) }}</p>
                                                                <p><strong>{{ __('outstanding_fees') }}:</strong> ${{ number_format($allocation->outstanding_fees, 2) }}</p>
                                                                <p><strong>{{ __('payment_status') }}:</strong> 
                                                                    <span class="badge badge-{{ $allocation->payment_status_badge }}">
                                                                        {{ ucfirst($allocation->payment_status) }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($allocation->status == 'active')
                                            <!-- Checkout Modal -->
                                            <div class="modal fade" id="checkoutModal{{ $allocation->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('dormitories.checkout', $allocation) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('checkout_student') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{ __('are_you_sure_checkout') }} <strong>{{ $allocation->student->full_name }}</strong> {{ __('from') }} <strong>{{ $allocation->dormitory->name }}</strong>?</p>
                                                                
                                                                <div class="form-group">
                                                                    <label for="checkout_notes{{ $allocation->id }}">{{ __('checkout_notes') }}</label>
                                                                    <textarea name="checkout_notes" id="checkout_notes{{ $allocation->id }}" class="form-control" rows="3" placeholder="{{ __('enter_checkout_notes') }}"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                                                                <button type="submit" class="btn btn-warning">{{ __('checkout') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Transfer Modal -->
                                            <div class="modal fade" id="transferModal{{ $allocation->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('dormitories.transfer', $allocation) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('transfer_student') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="new_dormitory_id{{ $allocation->id }}">{{ __('transfer_to_dormitory') }} <span class="text-danger">*</span></label>
                                                                    <select name="new_dormitory_id" id="new_dormitory_id{{ $allocation->id }}" class="form-control" required>
                                                                        <option value="">{{ __('select_dormitory') }}</option>
                                                                        @foreach($dormitories->where('id', '!=', $allocation->dormitory_id) as $dormitory)
                                                                            @if($dormitory->canAllocate())
                                                                                <option value="{{ $dormitory->id }}">
                                                                                    {{ $dormitory->name }} ({{ $dormitory->available_beds }} {{ __('beds_available') }})
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="transfer_notes{{ $allocation->id }}">{{ __('transfer_notes') }}</label>
                                                                    <textarea name="transfer_notes" id="transfer_notes{{ $allocation->id }}" class="form-control" rows="3" placeholder="{{ __('enter_transfer_reason') }}"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                                                                <button type="submit" class="btn btn-info">{{ __('transfer') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">{{ __('no_allocation_history_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $allocations->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Auto-submit form on filter change
        $('select[name="dormitory_id"], select[name="status"]').change(function() {
            $(this).closest('form').submit();
        });
    </script>
@endsection