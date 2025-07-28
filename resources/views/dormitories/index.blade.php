@extends('layouts.master')

@section('title')
    {{ __('dormitories') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_dormitories') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('dormitories') }}</li>
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
                                <h4 class="text-primary">{{ $stats['total'] }}</h4>
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
                                <h4 class="text-success">{{ $stats['active'] }}</h4>
                                <p class="text-muted mb-0">{{ __('active_dormitories') }}</p>
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
                                <h4 class="text-info">{{ $stats['total_capacity'] }}</h4>
                                <p class="text-muted mb-0">{{ __('total_capacity') }}</p>
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
                                <h4 class="text-warning">{{ $stats['available_beds'] }}</h4>
                                <p class="text-muted mb-0">{{ __('available_beds') }}</p>
                            </div>
                            <div class="icon-large text-warning">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('dormitories_list') }}</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <a href="{{ route('dormitories.create') }}" class="btn btn-theme btn-sm">
                                        <i class="fa fa-plus-circle"></i> {{ __('add_dormitory') }}
                                    </a>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('dormitories.dashboard') }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-chart-bar"></i> {{ __('dashboard') }}
                                        </a>
                                        <a href="{{ route('dormitories.history') }}" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-history"></i> {{ __('allocation_history') }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Filters -->
                                <form method="GET" class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <input type="text" name="search" class="form-control" placeholder="{{ __('search_dormitories') }}" value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="gender" class="form-control">
                                                <option value="">{{ __('all_genders') }}</option>
                                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('male') }}</option>
                                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('female') }}</option>
                                                <option value="mixed" {{ request('gender') == 'mixed' ? 'selected' : '' }}>{{ __('mixed') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="status" class="form-control">
                                                <option value="">{{ __('all_status') }}</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('inactive') }}</option>
                                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('available') }}</option>
                                                <option value="full" {{ request('status') == 'full' ? 'selected' : '' }}>{{ __('full') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">{{ __('filter') }}</button>
                                            <a href="{{ route('dormitories.index') }}" class="btn btn-secondary">{{ __('clear') }}</a>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('name') }}</th>
                                                <th>{{ __('building') }}</th>
                                                <th>{{ __('gender') }}</th>
                                                <th>{{ __('capacity') }}</th>
                                                <th>{{ __('occupancy') }}</th>
                                                <th>{{ __('monthly_fee') }}</th>
                                                <th>{{ __('supervisor') }}</th>
                                                <th>{{ __('status') }}</th>
                                                <th>{{ __('actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dormitories as $dormitory)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6 class="mb-0">{{ $dormitory->name }}</h6>
                                                            @if($dormitory->description)
                                                                <small class="text-muted">{{ Str::limit($dormitory->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $dormitory->building }}
                                                        @if($dormitory->floor)
                                                            <br><small class="text-muted">{{ __('floor') }}: {{ $dormitory->floor }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $dormitory->gender == 'male' ? 'primary' : ($dormitory->gender == 'female' ? 'danger' : 'info') }}">
                                                            {{ ucfirst($dormitory->gender) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $dormitory->capacity }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span>{{ $dormitory->occupied }}/{{ $dormitory->capacity }}</span>
                                                            <div class="progress ms-2" style="width: 60px; height: 6px;">
                                                                <div class="progress-bar bg-{{ $dormitory->status_badge }}" 
                                                                     style="width: {{ $dormitory->occupancy_rate }}%"></div>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ number_format($dormitory->occupancy_rate, 1) }}%</small>
                                                    </td>
                                                    <td>${{ number_format($dormitory->monthly_fee, 2) }}</td>
                                                    <td>
                                                        @if($dormitory->supervisor_name)
                                                            <div>
                                                                <div>{{ $dormitory->supervisor_name }}</div>
                                                                @if($dormitory->supervisor_phone)
                                                                    <small class="text-muted">{{ $dormitory->supervisor_phone }}</small>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">{{ __('not_assigned') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $dormitory->status_badge }}">
                                                            {{ $dormitory->status_text }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                                {{ __('actions') }}
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('dormitories.show', $dormitory) }}">
                                                                    <i class="fa fa-eye"></i> {{ __('view') }}
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('dormitories.edit', $dormitory) }}">
                                                                    <i class="fa fa-edit"></i> {{ __('edit') }}
                                                                </a>
                                                                @if($dormitory->canAllocate())
                                                                    <a class="dropdown-item" href="{{ route('dormitories.allocate', $dormitory) }}">
                                                                        <i class="fa fa-plus"></i> {{ __('allocate_student') }}
                                                                    </a>
                                                                @endif
                                                                <div class="dropdown-divider"></div>
                                                                <form action="{{ route('dormitories.destroy', $dormitory) }}" method="POST" 
                                                                      onsubmit="return confirm('{{ __('are_you_sure') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fa fa-trash"></i> {{ __('delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">{{ __('no_data_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $dormitories->appends(request()->query())->links() }}
                                </div>
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
        // Auto-submit form on filter change
        $('select[name="gender"], select[name="status"]').change(function() {
            $(this).closest('form').submit();
        });
    </script>
@endsection