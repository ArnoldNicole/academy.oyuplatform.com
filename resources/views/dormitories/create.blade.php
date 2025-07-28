@extends('layouts.master')

@section('title')
    {{ __('add_dormitory') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('add_dormitory') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dormitories.index') }}">{{ __('dormitories') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('add_dormitory') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('dormitory_information') }}</h4>
                        
                        <form class="pt-3" action="{{ route('dormitories.store') }}" method="POST" novalidate="novalidate">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('dormitory_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="{{ __('enter_dormitory_name') }}" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">{{ __('gender') }} <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">{{ __('select_gender') }}</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('male') }}</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('female') }}</option>
                                            <option value="mixed" {{ old('gender') == 'mixed' ? 'selected' : '' }}>{{ __('mixed') }}</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity">{{ __('capacity') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                               placeholder="{{ __('enter_capacity') }}" value="{{ old('capacity') }}" min="1" required>
                                        @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monthly_fee">{{ __('monthly_fee') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="monthly_fee" id="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                                   placeholder="0.00" value="{{ old('monthly_fee') }}" step="0.01" min="0" required>
                                        </div>
                                        @error('monthly_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="building">{{ __('building') }}</label>
                                        <input type="text" name="building" id="building" class="form-control @error('building') is-invalid @enderror" 
                                               placeholder="{{ __('enter_building_name') }}" value="{{ old('building') }}">
                                        @error('building')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="floor">{{ __('floor') }}</label>
                                        <input type="text" name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" 
                                               placeholder="{{ __('enter_floor') }}" value="{{ old('floor') }}">
                                        @error('floor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('description') }}</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="{{ __('enter_description') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Facilities -->
                            <div class="form-group">
                                <label>{{ __('facilities') }}</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="wifi" id="wifi"
                                                   {{ is_array(old('facilities')) && in_array('wifi', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="wifi">{{ __('wifi') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="ac" id="ac"
                                                   {{ is_array(old('facilities')) && in_array('ac', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ac">{{ __('air_conditioning') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="laundry" id="laundry"
                                                   {{ is_array(old('facilities')) && in_array('laundry', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="laundry">{{ __('laundry') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="study_room" id="study_room"
                                                   {{ is_array(old('facilities')) && in_array('study_room', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="study_room">{{ __('study_room') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="cafeteria" id="cafeteria"
                                                   {{ is_array(old('facilities')) && in_array('cafeteria', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cafeteria">{{ __('cafeteria') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="parking" id="parking"
                                                   {{ is_array(old('facilities')) && in_array('parking', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="parking">{{ __('parking') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="security" id="security"
                                                   {{ is_array(old('facilities')) && in_array('security', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="security">{{ __('security') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facilities[]" value="recreation" id="recreation"
                                                   {{ is_array(old('facilities')) && in_array('recreation', old('facilities')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="recreation">{{ __('recreation_area') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Supervisor Information -->
                            <h5 class="mt-4 mb-3">{{ __('supervisor_information') }}</h5>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="supervisor_name">{{ __('supervisor_name') }}</label>
                                        <input type="text" name="supervisor_name" id="supervisor_name" class="form-control @error('supervisor_name') is-invalid @enderror" 
                                               placeholder="{{ __('enter_supervisor_name') }}" value="{{ old('supervisor_name') }}">
                                        @error('supervisor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="supervisor_phone">{{ __('supervisor_phone') }}</label>
                                        <input type="tel" name="supervisor_phone" id="supervisor_phone" class="form-control @error('supervisor_phone') is-invalid @enderror" 
                                               placeholder="{{ __('enter_phone_number') }}" value="{{ old('supervisor_phone') }}">
                                        @error('supervisor_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="supervisor_email">{{ __('supervisor_email') }}</label>
                                        <input type="email" name="supervisor_email" id="supervisor_email" class="form-control @error('supervisor_email') is-invalid @enderror" 
                                               placeholder="{{ __('enter_email_address') }}" value="{{ old('supervisor_email') }}">
                                        @error('supervisor_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('active') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-theme">{{ __('submit') }}</button>
                                <a href="{{ route('dormitories.index') }}" class="btn btn-secondary">{{ __('cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection