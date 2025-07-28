@extends('layouts.master')

@section('title')
    {{ __('allocate_student') }} - {{ $dormitory->name }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('allocate_student') }} - {{ $dormitory->name }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.index') }}">{{ __('dormitories') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.show', $dormitory) }}">{{ $dormitory->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('allocate_student') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('allocate_student') }}</h4>
                        
                        <form action="{{ route('dormitories.store-allocation', $dormitory) }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="student_id">{{ __('select_student') }} <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                    <option value="">{{ __('select_student') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->full_name }} ({{ $student->admission_no }})
                                            @if($student->user->gender)
                                                - {{ ucfirst($student->user->gender) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bed_number">{{ __('bed_number') }}</label>
                                <input type="text" 
                                       name="bed_number" 
                                       id="bed_number" 
                                       class="form-control @error('bed_number') is-invalid @enderror" 
                                       value="{{ old('bed_number') }}"
                                       placeholder="{{ __('enter_bed_number') }}">
                                @error('bed_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="expected_checkout_date">{{ __('expected_checkout_date') }}</label>
                                <input type="date" 
                                       name="expected_checkout_date" 
                                       id="expected_checkout_date" 
                                       class="form-control @error('expected_checkout_date') is-invalid @enderror" 
                                       value="{{ old('expected_checkout_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                @error('expected_checkout_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="allocation_notes">{{ __('allocation_notes') }}</label>
                                <textarea name="allocation_notes" 
                                          id="allocation_notes" 
                                          class="form-control @error('allocation_notes') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="{{ __('enter_allocation_notes') }}">{{ old('allocation_notes') }}</textarea>
                                @error('allocation_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> {{ __('allocate_student') }}
                                </button>
                                <a href="{{ route('dormitories.show', $dormitory) }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('back') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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

                        @if($students->count() == 0)
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('no_eligible_students') }}
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
        // Add Select2 for better student selection
        $(document).ready(function() {
            $('#student_id').select2({
                placeholder: "{{ __('select_student') }}",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection