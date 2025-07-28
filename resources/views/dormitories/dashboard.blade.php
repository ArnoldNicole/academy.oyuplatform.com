@extends('layouts.master')

@section('title')
    {{ __('dormitory_dashboard') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('dormitory_dashboard') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.index') }}">{{ __('dormitories') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('dashboard') }}</li>
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
                                <h4 class="text-primary">{{ $stats['total_dormitories'] }}</h4>
                                <p class="text-muted mb-0">{{ __('total_dormitories') }}</p>
                            </div>
                            <div class="icon-large text-primary">
                                <i class="fas fa-building"></i>
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
                                <h4 class="text-success">{{ $stats['active_allocations'] }}</h4>
                                <p class="text-muted mb-0">{{ __('active_allocations') }}</p>
                            </div>
                            <div class="icon-large text-success">
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
                                <h4 class="text-info">${{ number_format($stats['monthly_revenue'], 2) }}</h4>
                                <p class="text-muted mb-0">{{ __('monthly_revenue') }}</p>
                            </div>
                            <div class="icon-large text-info">
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
                                <h4 class="text-warning">${{ number_format($stats['outstanding_fees'], 2) }}</h4>
                                <p class="text-muted mb-0">{{ __('outstanding_fees') }}</p>
                            </div>
                            <div class="icon-large text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('quick_actions') }}</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            @can('dormitory-create')
                                <a href="{{ route('dormitories.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> {{ __('add_dormitory') }}
                                </a>
                            @endcan
                            @can('dormitory-list')
                                <a href="{{ route('dormitories.index') }}" class="btn btn-info">
                                    <i class="fa fa-list"></i> {{ __('manage_dormitories') }}
                                </a>
                                <a href="{{ route('dormitories.history') }}" class="btn btn-secondary">
                                    <i class="fa fa-history"></i> {{ __('allocation_history') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Occupancy Chart -->
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('dormitory_occupancy') }}</h4>
                        
                        @if($occupancyData->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('dormitory') }}</th>
                                            <th>{{ __('capacity') }}</th>
                                            <th>{{ __('occupied') }}</th>
                                            <th>{{ __('available') }}</th>
                                            <th>{{ __('occupancy_rate') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($occupancyData as $dormitory)
                                            @php
                                                $occupancyRate = $dormitory->capacity > 0 ? ($dormitory->occupied / $dormitory->capacity) * 100 : 0;
                                                $available = $dormitory->capacity - $dormitory->occupied;
                                                $badgeClass = $occupancyRate >= 90 ? 'danger' : ($occupancyRate >= 75 ? 'warning' : 'success');
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $dormitory->name }}</strong></td>
                                                <td>{{ $dormitory->capacity }}</td>
                                                <td>{{ $dormitory->occupied }}</td>
                                                <td>{{ $available }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress me-2" style="width: 100px; height: 6px;">
                                                            <div class="progress-bar bg-{{ $badgeClass }}" style="width: {{ $occupancyRate }}%"></div>
                                                        </div>
                                                        <span class="badge badge-{{ $badgeClass }}">{{ number_format($occupancyRate, 1) }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_dormitories_found') }}</p>
                                @can('dormitory-create')
                                    <a href="{{ route('dormitories.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> {{ __('add_first_dormitory') }}
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('recent_allocations') }}</h4>
                        
                        @if($recentAllocations->count() > 0)
                            <div class="activity-feed">
                                @foreach($recentAllocations as $allocation)
                                    <div class="feed-item d-flex mb-3">
                                        <div class="feed-icon">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="fas fa-bed text-white" style="font-size: 12px;"></i>
                                            </div>
                                        </div>
                                        <div class="feed-content ms-3">
                                            <div class="feed-title">
                                                <strong>{{ $allocation->student->full_name }}</strong>
                                            </div>
                                            <div class="feed-subtitle text-muted small">
                                                {{ __('allocated_to') }} {{ $allocation->dormitory->name }}
                                            </div>
                                            <div class="feed-time text-muted small">
                                                {{ $allocation->allocated_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('dormitories.history') }}" class="btn btn-outline-primary btn-sm">
                                    {{ __('view_all_history') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">{{ __('no_recent_activity') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Items -->
        @if($overdueItems->count() > 0)
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ __('overdue_items') }}
                            </h4>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('student') }}</th>
                                            <th>{{ __('dormitory') }}</th>
                                            <th>{{ __('issue') }}</th>
                                            <th>{{ __('amount') }}</th>
                                            <th>{{ __('days_overdue') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overdueItems as $item)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->student->full_name }}</h6>
                                                        <small class="text-muted">{{ $item->student->admission_no }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $item->dormitory->name }}</td>
                                                <td>
                                                    @if($item->payment_status === 'overdue')
                                                        <span class="badge badge-danger">{{ __('payment_overdue') }}</span>
                                                    @elseif($item->expected_checkout_date && $item->expected_checkout_date->isPast())
                                                        <span class="badge badge-warning">{{ __('checkout_overdue') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->outstanding_fees > 0)
                                                        <span class="text-danger">${{ number_format($item->outstanding_fees, 2) }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->payment_status === 'overdue')
                                                        {{ now()->diffInDays($item->created_at) }}
                                                    @elseif($item->expected_checkout_date)
                                                        {{ now()->diffInDays($item->expected_checkout_date) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('dormitories.show', $item->dormitory) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('view') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Additional Statistics -->
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('capacity_utilization') }}</h5>
                        @php
                            $utilizationRate = $stats['total_capacity'] > 0 ? ($stats['total_occupied'] / $stats['total_capacity']) * 100 : 0;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: {{ $utilizationRate }}%"></div>
                            </div>
                            <span class="fw-bold">{{ number_format($utilizationRate, 1) }}%</span>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-muted small">{{ __('total_capacity') }}</div>
                                <div class="fw-bold">{{ $stats['total_capacity'] }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">{{ __('occupied') }}</div>
                                <div class="fw-bold text-success">{{ $stats['total_occupied'] }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">{{ __('available') }}</div>
                                <div class="fw-bold text-info">{{ $stats['total_capacity'] - $stats['total_occupied'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('financial_summary') }}</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="text-muted small">{{ __('monthly_revenue') }}</div>
                                    <div class="h4 text-success mb-0">${{ number_format($stats['monthly_revenue'], 2) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="text-muted small">{{ __('outstanding') }}</div>
                                    <div class="h4 text-warning mb-0">${{ number_format($stats['outstanding_fees'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                        @if($stats['monthly_revenue'] > 0)
                            @php
                                $collectionRate = (($stats['monthly_revenue'] - $stats['outstanding_fees']) / $stats['monthly_revenue']) * 100;
                            @endphp
                            <div class="mt-3">
                                <div class="text-muted small">{{ __('collection_rate') }}</div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $collectionRate }}%"></div>
                                </div>
                                <div class="text-center small">{{ number_format($collectionRate, 1) }}%</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
@endsection