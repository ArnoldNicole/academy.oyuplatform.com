@extends('layouts.master')

@section('title')
    {{ $dormitory->name }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ $dormitory->name }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.index') }}">{{ __('dormitories') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $dormitory->name }}</li>
                </ol>
            </nav>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-info">{{ $stats['active_allocations'] }}</h4>
                                <p class="text-muted mb-0">{{ __('active_allocations') }}</p>
                            </div>
                            <div class="icon-large text-info">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-success">${{ number_format($stats['monthly_revenue'], 2) }}</h4>
                                <p class="text-muted mb-0">{{ __('monthly_revenue') }}</p>
                            </div>
                            <div class="icon-large text-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-warning">{{ number_format($stats['occupancy_rate'], 1) }}%</h4>
                                <p class="text-muted mb-0">{{ __('occupancy_rate') }}</p>
                            </div>
                            <div class="icon-large text-warning">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary">{{ number_format($stats['average_stay_days']) }}</h4>
                                <p class="text-muted mb-0">{{ __('avg_stay_days') }}</p>
                            </div>
                            <div class="icon-large text-primary">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Dormitory Information -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('dormitory_information') }}</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ __('name') }}:</strong></td>
                                    <td>{{ $dormitory->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('gender') }}:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $dormitory->gender == 'male' ? 'primary' : ($dormitory->gender == 'female' ? 'danger' : 'info') }}">
                                            {{ ucfirst($dormitory->gender) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('capacity') }}:</strong></td>
                                    <td>{{ $dormitory->capacity }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('occupied') }}:</strong></td>
                                    <td>{{ $dormitory->occupied }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('available_beds') }}:</strong></td>
                                    <td>{{ $dormitory->available_beds }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('monthly_fee') }}:</strong></td>
                                    <td>${{ number_format($dormitory->monthly_fee, 2) }}</td>
                                </tr>
                                @if($dormitory->building)
                                <tr>
                                    <td><strong>{{ __('building') }}:</strong></td>
                                    <td>{{ $dormitory->building }}</td>
                                </tr>
                                @endif
                                @if($dormitory->floor)
                                <tr>
                                    <td><strong>{{ __('floor') }}:</strong></td>
                                    <td>{{ $dormitory->floor }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>{{ __('status') }}:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $dormitory->status_badge }}">
                                            {{ $dormitory->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if($dormitory->facilities && count($dormitory->facilities) > 0)
                            <div class="mt-3">
                                <h6>{{ __('facilities') }}:</h6>
                                <div class="d-flex flex-wrap">
                                    @foreach($dormitory->facilities as $facility)
                                        <small class="badge badge-light me-1 mb-1">{{ ucfirst(str_replace('_', ' ', $facility)) }}</small>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($dormitory->supervisor_name)
                            <div class="mt-3">
                                <h6>{{ __('supervisor') }}:</h6>
                                <p class="mb-1"><strong>{{ $dormitory->supervisor_name }}</strong></p>
                                @if($dormitory->supervisor_phone)
                                    <p class="mb-1"><small>{{ $dormitory->supervisor_phone }}</small></p>
                                @endif
                                @if($dormitory->supervisor_email)
                                    <p class="mb-0"><small>{{ $dormitory->supervisor_email }}</small></p>
                                @endif
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('dormitories.edit', $dormitory) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> {{ __('edit') }}
                            </a>
                            @if($dormitory->canAllocate())
                                <a href="{{ route('dormitories.allocate', $dormitory) }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> {{ __('allocate_student') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currently Allocated Students -->
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('currently_allocated_students') }}</h4>
                        
                        @if($dormitory->activeAllocations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('student') }}</th>
                                            <th>{{ __('bed_number') }}</th>
                                            <th>{{ __('allocated_date') }}</th>
                                            <th>{{ __('fees_status') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dormitory->activeAllocations as $allocation)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $allocation->student->full_name }}</h6>
                                                        <small class="text-muted">{{ $allocation->student->admission_no }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $allocation->bed_number ?? __('not_assigned') }}</td>
                                                <td>{{ $allocation->allocated_date->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $allocation->payment_status_badge }}">
                                                        {{ ucfirst($allocation->payment_status) }}
                                                    </span>
                                                    @if($allocation->outstanding_fees > 0)
                                                        <br><small class="text-danger">${{ number_format($allocation->outstanding_fees, 2) }} {{ __('due') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                            {{ __('actions') }}
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" data-toggle="modal" data-target="#checkoutModal{{ $allocation->id }}">
                                                                <i class="fa fa-sign-out-alt"></i> {{ __('checkout') }}
                                                            </button>
                                                            <button class="dropdown-item" data-toggle="modal" data-target="#transferModal{{ $allocation->id }}">
                                                                <i class="fa fa-exchange-alt"></i> {{ __('transfer') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

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
                                                                <p>{{ __('are_you_sure_checkout') }} <strong>{{ $allocation->student->full_name }}</strong>?</p>
                                                                
                                                                <div class="form-group">
                                                                    <label for="checkout_notes{{ $allocation->id }}">{{ __('checkout_notes') }}</label>
                                                                    <textarea name="checkout_notes" id="checkout_notes{{ $allocation->id }}" class="form-control" rows="3"></textarea>
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
                                                                        @foreach(\App\Models\Dormitory::forSchool(Auth::user()->school_id)->available()->where('id', '!=', $dormitory->id)->get() as $availableDormitory)
                                                                            <option value="{{ $availableDormitory->id }}">
                                                                                {{ $availableDormitory->name }} ({{ $availableDormitory->available_beds }} {{ __('beds_available') }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label for="transfer_notes{{ $allocation->id }}">{{ __('transfer_notes') }}</label>
                                                                    <textarea name="transfer_notes" id="transfer_notes{{ $allocation->id }}" class="form-control" rows="3"></textarea>
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_students_allocated') }}</p>
                                @if($dormitory->canAllocate())
                                    <a href="{{ route('dormitories.allocate', $dormitory) }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> {{ __('allocate_student') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Allocation History -->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">{{ __('recent_allocation_history') }}</h4>
                            <a href="{{ route('dormitories.history') }}?dormitory_id={{ $dormitory->id }}" class="btn btn-outline-primary btn-sm">
                                {{ __('view_all') }}
                            </a>
                        </div>
                        
                        @if($recentAllocations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('student') }}</th>
                                            <th>{{ __('allocated_date') }}</th>
                                            <th>{{ __('checkout_date') }}</th>
                                            <th>{{ __('status') }}</th>
                                            <th>{{ __('allocated_by') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAllocations as $allocation)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $allocation->student->full_name }}</h6>
                                                        <small class="text-muted">{{ $allocation->student->admission_no }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $allocation->allocated_date->format('M d, Y') }}</td>
                                                <td>
                                                    @if($allocation->actual_checkout_date)
                                                        {{ $allocation->actual_checkout_date->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $allocation->status_badge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $allocation->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ $allocation->allocatedBy->full_name ?? __('system') }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $recentAllocations->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">{{ __('no_allocation_history') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection