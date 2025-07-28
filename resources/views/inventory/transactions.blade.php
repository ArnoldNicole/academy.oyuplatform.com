@extends('layouts.master')

@section('title')
    {{ __('transaction_history') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('transaction_history') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">{{ __('inventory') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('transaction_history') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">{{ __('inventory_transactions') }}</h4>
                            <div>
                                <a href="{{ route('inventory.dashboard') }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-tachometer-alt"></i> {{ __('dashboard') }}
                                </a>
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-list"></i> {{ __('manage_inventory') }}
                                </a>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('inventory.transactions') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" 
                                                   name="search" 
                                                   class="form-control" 
                                                   placeholder="{{ __('search_transactions') }}" 
                                                   value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="type" class="form-control">
                                                <option value="">{{ __('all_types') }}</option>
                                                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>{{ __('stock_in') }}</option>
                                                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>{{ __('stock_out') }}</option>
                                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>{{ __('adjustment') }}</option>
                                                <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>{{ __('transfer') }}</option>
                                                <option value="loss" {{ request('type') == 'loss' ? 'selected' : '' }}>{{ __('loss') }}</option>
                                                <option value="damage" {{ request('type') == 'damage' ? 'selected' : '' }}>{{ __('damage') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="reason" class="form-control">
                                                <option value="">{{ __('all_reasons') }}</option>
                                                <option value="purchase" {{ request('reason') == 'purchase' ? 'selected' : '' }}>{{ __('purchase') }}</option>
                                                <option value="issue" {{ request('reason') == 'issue' ? 'selected' : '' }}>{{ __('issue') }}</option>
                                                <option value="return" {{ request('reason') == 'return' ? 'selected' : '' }}>{{ __('return') }}</option>
                                                <option value="adjustment" {{ request('reason') == 'adjustment' ? 'selected' : '' }}>{{ __('adjustment') }}</option>
                                                <option value="transfer_in" {{ request('reason') == 'transfer_in' ? 'selected' : '' }}>{{ __('transfer_in') }}</option>
                                                <option value="transfer_out" {{ request('reason') == 'transfer_out' ? 'selected' : '' }}>{{ __('transfer_out') }}</option>
                                                <option value="loss" {{ request('reason') == 'loss' ? 'selected' : '' }}>{{ __('loss') }}</option>
                                                <option value="damage" {{ request('reason') == 'damage' ? 'selected' : '' }}>{{ __('damage') }}</option>
                                                <option value="expired" {{ request('reason') == 'expired' ? 'selected' : '' }}>{{ __('expired') }}</option>
                                                <option value="donation" {{ request('reason') == 'donation' ? 'selected' : '' }}>{{ __('donation') }}</option>
                                                <option value="sale" {{ request('reason') == 'sale' ? 'selected' : '' }}>{{ __('sale') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" 
                                                   name="date_from" 
                                                   class="form-control" 
                                                   placeholder="{{ __('date_from') }}" 
                                                   value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" 
                                                   name="date_to" 
                                                   class="form-control" 
                                                   placeholder="{{ __('date_to') }}" 
                                                   value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-1">
                                            <div class="btn-group" role="group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                                <a href="{{ route('inventory.transactions') }}" class="btn btn-secondary">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('item_name') }}</th>
                                            <th>{{ __('transaction_type') }}</th>
                                            <th>{{ __('reason') }}</th>
                                            <th>{{ __('quantity') }}</th>
                                            <th>{{ __('balance_after') }}</th>
                                            <th>{{ __('unit_cost') }}</th>
                                            <th>{{ __('total_cost') }}</th>
                                            <th>{{ __('recipient') }}</th>
                                            <th>{{ __('transaction_date') }}</th>
                                            <th>{{ __('created_by') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
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
                                                <td>
                                                    <span class="text-muted">{{ $transaction->reason_text }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($transaction->type == 'in')
                                                            <i class="fas fa-arrow-up text-success me-1"></i>
                                                        @elseif($transaction->type == 'out')
                                                            <i class="fas fa-arrow-down text-danger me-1"></i>
                                                        @else
                                                            <i class="fas fa-exchange-alt text-warning me-1"></i>
                                                        @endif
                                                        <strong>{{ $transaction->quantity }}</strong>
                                                        <small class="text-muted ms-1">{{ $transaction->inventoryItem->unit }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong>{{ $transaction->balance_after }}</strong>
                                                    <small class="text-muted">{{ $transaction->inventoryItem->unit }}</small>
                                                </td>
                                                <td>
                                                    @if($transaction->unit_cost)
                                                        ${{ number_format($transaction->unit_cost, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($transaction->total_cost)
                                                        <strong>${{ number_format($transaction->total_cost, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($transaction->recipient)
                                                        <div>
                                                            <small class="text-muted">{{ $transaction->recipient->full_name }}</small>
                                                            @if($transaction->recipient_type)
                                                                <br><span class="badge badge-light">{{ ucfirst($transaction->recipient_type) }}</span>
                                                            @endif
                                                        </div>
                                                    @elseif($transaction->department)
                                                        <div>
                                                            <span class="badge badge-info">{{ $transaction->department }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $transaction->transaction_date->format('M d, Y') }}</strong>
                                                        <br><small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $transaction->createdBy->full_name ?? __('system') }}</small>
                                                </td>
                                            </tr>
                                            @if($transaction->description)
                                                <tr class="table-light">
                                                    <td colspan="10">
                                                        <div class="pl-3">
                                                            <i class="fas fa-comment-alt text-muted me-1"></i>
                                                            <small class="text-muted">{{ $transaction->description }}</small>
                                                            @if($transaction->reference_number)
                                                                <span class="badge badge-secondary ml-2">Ref: {{ $transaction->reference_number }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $transactions->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('no_transactions_found') }}</h5>
                                <p class="text-muted">{{ __('no_transactions_match_criteria') }}</p>
                                <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> {{ __('back_to_inventory') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        @if($transactions->count() > 0)
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-arrow-up fa-2x text-success mb-2"></i>
                            <h4 class="text-success">{{ $transactions->where('type', 'in')->count() }}</h4>
                            <small class="text-muted">{{ __('stock_in_transactions') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-arrow-down fa-2x text-danger mb-2"></i>
                            <h4 class="text-danger">{{ $transactions->where('type', 'out')->count() }}</h4>
                            <small class="text-muted">{{ __('stock_out_transactions') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-exchange-alt fa-2x text-warning mb-2"></i>
                            <h4 class="text-warning">{{ $transactions->where('type', 'adjustment')->count() }}</h4>
                            <small class="text-muted">{{ __('adjustment_transactions') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                            <h4 class="text-info">${{ number_format($transactions->where('total_cost', '>', 0)->sum('total_cost'), 2) }}</h4>
                            <small class="text-muted">{{ __('total_transaction_value') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        // Auto-submit form on filter change
        $('select[name="type"], select[name="reason"]').on('change', function() {
            $(this).closest('form').submit();
        });

        // Set max date for date_to based on date_from
        $('input[name="date_from"]').on('change', function() {
            $('input[name="date_to"]').attr('min', $(this).val());
        });

        // Set min date for date_from based on date_to
        $('input[name="date_to"]').on('change', function() {
            $('input[name="date_from"]').attr('max', $(this).val());
        });
    </script>
@endsection