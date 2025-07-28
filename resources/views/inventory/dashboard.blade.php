@extends('layouts.master')

@section('title')
    {{ __('inventory') }} {{ __('dashboard') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('inventory') }} {{ __('dashboard') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('inventory') }} {{ __('dashboard') }}</li>
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
                                <h4 class="text-primary">{{ $stats['total_items'] }}</h4>
                                <p class="text-muted mb-0">{{ __('total_items') }}</p>
                            </div>
                            <div class="icon-large text-primary">
                                <i class="fas fa-boxes"></i>
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
                                <h4 class="text-success">{{ $stats['active_items'] }}</h4>
                                <p class="text-muted mb-0">{{ __('active_items') }}</p>
                            </div>
                            <div class="icon-large text-success">
                                <i class="fas fa-check-circle"></i>
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
                                <h4 class="text-warning">{{ $stats['low_stock_items'] }}</h4>
                                <p class="text-muted mb-0">{{ __('low_stock_items') }}</p>
                            </div>
                            <div class="icon-large text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
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
                                <h4 class="text-info">${{ number_format($stats['total_value'], 2) }}</h4>
                                <p class="text-muted mb-0">{{ __('inventory_value') }}</p>
                            </div>
                            <div class="icon-large text-info">
                                <i class="fas fa-dollar-sign"></i>
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
                            @can('inventory-create')
                                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> {{ __('add_item') }}
                                </a>
                            @endcan
                            @can('inventory-list')
                                <a href="{{ route('inventory.index') }}" class="btn btn-info">
                                    <i class="fa fa-list"></i> {{ __('manage_inventory') }}
                                </a>
                                <a href="{{ route('inventory.transactions') }}" class="btn btn-secondary">
                                    <i class="fa fa-history"></i> {{ __('transaction_history') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Low Stock Items -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('low_stock_items') }}</h4>
                        
                        @if($lowStockItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('item_name') }}</th>
                                            <th>{{ __('current_stock') }}</th>
                                            <th>{{ __('minimum_stock') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockItems as $item)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                                        <small class="text-muted">{{ $item->code }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $item->stock_status_badge }}">
                                                        {{ $item->current_stock }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->minimum_stock }}</td>
                                                <td>
                                                    <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('view') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">{{ __('no_low_stock_items') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Expiring Items -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('expiring_items') }}</h4>
                        
                        @if($expiringItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('item_name') }}</th>
                                            <th>{{ __('expiry_date') }}</th>
                                            <th>{{ __('days_left') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiringItems as $item)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                                        <small class="text-muted">{{ $item->code }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $item->expiry_date->format('M d, Y') }}</td>
                                                <td>
                                                    @php
                                                        $daysLeft = now()->diffInDays($item->expiry_date, false);
                                                    @endphp
                                                    <span class="badge badge-{{ $daysLeft <= 7 ? 'danger' : ($daysLeft <= 14 ? 'warning' : 'info') }}">
                                                        {{ $daysLeft >= 0 ? $daysLeft : 'Expired' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('view') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                                <p class="text-muted">{{ __('no_expiring_items') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">{{ __('recent_transactions') }}</h4>
                            <a href="{{ route('inventory.transactions') }}" class="btn btn-outline-primary btn-sm">
                                {{ __('view_all') }}
                            </a>
                        </div>
                        
                        @if($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('item_name') }}</th>
                                            <th>{{ __('transaction_type') }}</th>
                                            <th>{{ __('quantity') }}</th>
                                            <th>{{ __('transaction_date') }}</th>
                                            <th>{{ __('created_by') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTransactions as $transaction)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $transaction->inventoryItem->name }}</h6>
                                                        <small class="text-muted">{{ $transaction->inventoryItem->code }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $transaction->type_badge }}">
                                                        {{ ucfirst($transaction->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ $transaction->quantity }}</td>
                                                <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                                <td>
                                                    <small>{{ $transaction->createdBy->full_name ?? __('system') }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('no_recent_transactions') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('out_of_stock_items') }}</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                            <div>
                                <h3 class="text-danger mb-0">{{ $stats['out_of_stock_items'] }}</h3>
                                <p class="text-muted small mb-0">{{ __('items_need_restocking') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('expiring_items') }}</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-calendar-times fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h3 class="text-warning mb-0">{{ $stats['expiring_items'] }}</h3>
                                <p class="text-muted small mb-0">{{ __('expiring_next_30_days') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('recent_transactions') }}</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-exchange-alt fa-2x text-info"></i>
                            </div>
                            <div>
                                <h3 class="text-info mb-0">{{ $stats['recent_transactions'] }}</h3>
                                <p class="text-muted small mb-0">{{ __('last_7_days') }}</p>
                            </div>
                        </div>
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