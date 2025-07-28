@extends('layouts.master')

@section('title')
    {{ __('manage_inventory') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_inventory') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('manage_inventory') }}</li>
                </ol>
            </nav>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $stats['total_items'] }}</h4>
                            <p class="text-muted mb-0 small">{{ __('total_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-success">{{ $stats['active_items'] }}</h4>
                            <p class="text-muted mb-0 small">{{ __('active_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $stats['low_stock_items'] }}</h4>
                            <p class="text-muted mb-0 small">{{ __('low_stock_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-danger">{{ $stats['out_of_stock_items'] }}</h4>
                            <p class="text-muted mb-0 small">{{ __('out_of_stock_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-info">${{ number_format($stats['total_value'], 2) }}</h4>
                            <p class="text-muted mb-0 small">{{ __('total_value') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $stats['expiring_items'] }}</h4>
                            <p class="text-muted mb-0 small">{{ __('expiring_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">{{ __('inventory_items') }}</h4>
                            @can('inventory-create')
                                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> {{ __('add_item') }}
                                </a>
                            @endcan
                        </div>

                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('inventory.index') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" 
                                                   name="search" 
                                                   class="form-control" 
                                                   placeholder="{{ __('search_items') }}" 
                                                   value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="category" class="form-control">
                                                <option value="">{{ __('all_categories') }}</option>
                                                <option value="stationery" {{ request('category') == 'stationery' ? 'selected' : '' }}>{{ __('stationery') }}</option>
                                                <option value="books" {{ request('category') == 'books' ? 'selected' : '' }}>{{ __('books') }}</option>
                                                <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>{{ __('equipment') }}</option>
                                                <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>{{ __('furniture') }}</option>
                                                <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>{{ __('electronics') }}</option>
                                                <option value="sports" {{ request('category') == 'sports' ? 'selected' : '' }}>{{ __('sports') }}</option>
                                                <option value="medical" {{ request('category') == 'medical' ? 'selected' : '' }}>{{ __('medical') }}</option>
                                                <option value="cleaning" {{ request('category') == 'cleaning' ? 'selected' : '' }}>{{ __('cleaning') }}</option>
                                                <option value="food" {{ request('category') == 'food' ? 'selected' : '' }}>{{ __('food') }}</option>
                                                <option value="uniform" {{ request('category') == 'uniform' ? 'selected' : '' }}>{{ __('uniform') }}</option>
                                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>{{ __('other') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="status" class="form-control">
                                                <option value="">{{ __('all_status') }}</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('inactive') }}</option>
                                                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>{{ __('low_stock') }}</option>
                                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('out_of_stock') }}</option>
                                                <option value="over_stock" {{ request('status') == 'over_stock' ? 'selected' : '' }}>{{ __('over_stock') }}</option>
                                                <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>{{ __('expiring_soon') }}</option>
                                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('expired') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="btn-group" role="group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i> {{ __('search') }}
                                                </button>
                                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                                    <i class="fa fa-refresh"></i> {{ __('reset') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-toggle="dropdown">
                                                    {{ __('actions') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('inventory.dashboard') }}">
                                                        <i class="fa fa-tachometer-alt"></i> {{ __('dashboard') }}
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('inventory.transactions') }}">
                                                        <i class="fa fa-history"></i> {{ __('transaction_history') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if($items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('item_name') }}</th>
                                            <th>{{ __('category') }}</th>
                                            <th>{{ __('current_stock') }}</th>
                                            <th>{{ __('stock_status') }}</th>
                                            <th>{{ __('unit_cost') }}</th>
                                            <th>{{ __('total_value') }}</th>
                                            <th>{{ __('condition') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->image)
                                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                                 alt="{{ $item->name }}" 
                                                                 class="rounded me-2" 
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-box text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->name }}</h6>
                                                            <small class="text-muted">{{ $item->code }}</small>
                                                            @if($item->brand)
                                                                <br><small class="text-muted">{{ $item->brand }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">{{ $item->category_name }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $item->current_stock }}</strong> {{ $item->unit }}
                                                        @if($item->minimum_stock > 0)
                                                            <br><small class="text-muted">Min: {{ $item->minimum_stock }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $item->stock_status_badge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
                                                    </span>
                                                    @if($item->isExpiringSoon())
                                                        <br><span class="badge badge-warning small">{{ __('expiring_soon') }}</span>
                                                    @endif
                                                    @if($item->isExpired())
                                                        <br><span class="badge badge-danger small">{{ __('expired') }}</span>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($item->unit_cost, 2) }}</td>
                                                <td>
                                                    <strong>${{ number_format($item->total_value, 2) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $item->condition_badge }}">
                                                        {{ ucfirst($item->condition) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                            {{ __('actions') }}
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @can('inventory-list')
                                                                <a class="dropdown-item" href="{{ route('inventory.show', $item) }}">
                                                                    <i class="fa fa-eye"></i> {{ __('view') }}
                                                                </a>
                                                            @endcan
                                                            @can('inventory-edit')
                                                                <a class="dropdown-item" href="{{ route('inventory.edit', $item) }}">
                                                                    <i class="fa fa-edit"></i> {{ __('edit') }}
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="{{ route('inventory.add-stock', $item) }}">
                                                                    <i class="fa fa-plus"></i> {{ __('add_stock') }}
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('inventory.issue-stock', $item) }}">
                                                                    <i class="fa fa-minus"></i> {{ __('issue_stock') }}
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('inventory.adjust-stock', $item) }}">
                                                                    <i class="fa fa-cog"></i> {{ __('adjust_stock') }}
                                                                </a>
                                                            @endcan
                                                            @can('inventory-delete')
                                                                @if($item->current_stock == 0)
                                                                    <div class="dropdown-divider"></div>
                                                                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                                onclick="return confirm('{{ __('are_you_sure') }}')">
                                                                            <i class="fa fa-trash"></i> {{ __('delete') }}
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $items->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('no_inventory_items_found') }}</h5>
                                <p class="text-muted">{{ __('no_items_match_criteria') }}</p>
                                @can('inventory-create')
                                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> {{ __('add_first_item') }}
                                    </a>
                                @endcan
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
        // Auto-submit form on category/status change
        $('select[name="category"], select[name="status"]').on('change', function() {
            $(this).closest('form').submit();
        });

        // Highlight low stock items
        $('.badge-warning, .badge-danger').closest('tr').addClass('table-warning');
    </script>
@endsection