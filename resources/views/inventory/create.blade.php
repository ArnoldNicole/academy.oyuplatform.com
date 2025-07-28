@extends('layouts.master')

@section('title')
    {{ __('add_item') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('add_item') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">{{ __('inventory') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('add_item') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('add_item') }}</h4>
                        
                        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('item_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}"
                                               placeholder="{{ __('enter_item_name') }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">{{ __('item_code') }} <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="code" 
                                               id="code" 
                                               class="form-control @error('code') is-invalid @enderror" 
                                               value="{{ old('code') }}"
                                               placeholder="{{ __('enter_item_code') }}" 
                                               required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">{{ __('description') }}</label>
                                        <textarea name="description" 
                                                  id="description" 
                                                  class="form-control @error('description') is-invalid @enderror" 
                                                  rows="3"
                                                  placeholder="{{ __('enter_description') }}">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Category and Details -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category">{{ __('category') }} <span class="text-danger">*</span></label>
                                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                            <option value="">{{ __('select_category') }}</option>
                                            <option value="stationery" {{ old('category') == 'stationery' ? 'selected' : '' }}>{{ __('stationery') }}</option>
                                            <option value="books" {{ old('category') == 'books' ? 'selected' : '' }}>{{ __('books') }}</option>
                                            <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>{{ __('equipment') }}</option>
                                            <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>{{ __('furniture') }}</option>
                                            <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>{{ __('electronics') }}</option>
                                            <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>{{ __('sports') }}</option>
                                            <option value="medical" {{ old('category') == 'medical' ? 'selected' : '' }}>{{ __('medical') }}</option>
                                            <option value="cleaning" {{ old('category') == 'cleaning' ? 'selected' : '' }}>{{ __('cleaning') }}</option>
                                            <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>{{ __('food') }}</option>
                                            <option value="uniform" {{ old('category') == 'uniform' ? 'selected' : '' }}>{{ __('uniform') }}</option>
                                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>{{ __('other') }}</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="brand">{{ __('brand') }}</label>
                                        <input type="text" 
                                               name="brand" 
                                               id="brand" 
                                               class="form-control @error('brand') is-invalid @enderror" 
                                               value="{{ old('brand') }}"
                                               placeholder="{{ __('enter_brand') }}">
                                        @error('brand')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="model">{{ __('model') }}</label>
                                        <input type="text" 
                                               name="model" 
                                               id="model" 
                                               class="form-control @error('model') is-invalid @enderror" 
                                               value="{{ old('model') }}"
                                               placeholder="{{ __('enter_model') }}">
                                        @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Information -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="unit">{{ __('unit') }} <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="unit" 
                                               id="unit" 
                                               class="form-control @error('unit') is-invalid @enderror" 
                                               value="{{ old('unit', 'piece') }}"
                                               placeholder="{{ __('enter_unit') }}" 
                                               required>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="current_stock">{{ __('current_stock') }} <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="current_stock" 
                                               id="current_stock" 
                                               class="form-control @error('current_stock') is-invalid @enderror" 
                                               value="{{ old('current_stock', 0) }}"
                                               min="0" 
                                               required>
                                        @error('current_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="minimum_stock">{{ __('minimum_stock') }} <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="minimum_stock" 
                                               id="minimum_stock" 
                                               class="form-control @error('minimum_stock') is-invalid @enderror" 
                                               value="{{ old('minimum_stock', 0) }}"
                                               min="0" 
                                               required>
                                        @error('minimum_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="maximum_stock">{{ __('maximum_stock') }}</label>
                                        <input type="number" 
                                               name="maximum_stock" 
                                               id="maximum_stock" 
                                               class="form-control @error('maximum_stock') is-invalid @enderror" 
                                               value="{{ old('maximum_stock') }}"
                                               min="0">
                                        @error('maximum_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_cost">{{ __('unit_cost') }} <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="unit_cost" 
                                               id="unit_cost" 
                                               class="form-control @error('unit_cost') is-invalid @enderror" 
                                               value="{{ old('unit_cost', 0) }}"
                                               step="0.01" 
                                               min="0" 
                                               required>
                                        @error('unit_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selling_price">{{ __('selling_price') }}</label>
                                        <input type="number" 
                                               name="selling_price" 
                                               id="selling_price" 
                                               class="form-control @error('selling_price') is-invalid @enderror" 
                                               value="{{ old('selling_price') }}"
                                               step="0.01" 
                                               min="0">
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplier">{{ __('supplier') }}</label>
                                        <input type="text" 
                                               name="supplier" 
                                               id="supplier" 
                                               class="form-control @error('supplier') is-invalid @enderror" 
                                               value="{{ old('supplier') }}"
                                               placeholder="{{ __('enter_supplier_name') }}">
                                        @error('supplier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplier_contact">{{ __('supplier_contact') }}</label>
                                        <input type="text" 
                                               name="supplier_contact" 
                                               id="supplier_contact" 
                                               class="form-control @error('supplier_contact') is-invalid @enderror" 
                                               value="{{ old('supplier_contact') }}"
                                               placeholder="{{ __('enter_supplier_contact') }}">
                                        @error('supplier_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location">{{ __('location') }}</label>
                                        <input type="text" 
                                               name="location" 
                                               id="location" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               value="{{ old('location') }}"
                                               placeholder="{{ __('enter_storage_location') }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="barcode">{{ __('barcode') }}</label>
                                        <input type="text" 
                                               name="barcode" 
                                               id="barcode" 
                                               class="form-control @error('barcode') is-invalid @enderror" 
                                               value="{{ old('barcode') }}"
                                               placeholder="{{ __('enter_barcode') }}">
                                        @error('barcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expiry_date">{{ __('expiry_date') }}</label>
                                        <input type="date" 
                                               name="expiry_date" 
                                               id="expiry_date" 
                                               class="form-control @error('expiry_date') is-invalid @enderror" 
                                               value="{{ old('expiry_date') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condition">{{ __('condition') }} <span class="text-danger">*</span></label>
                                        <select name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror" required>
                                            <option value="">{{ __('select_condition') }}</option>
                                            <option value="new" {{ old('condition', 'new') == 'new' ? 'selected' : '' }}>{{ __('new') }}</option>
                                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>{{ __('good') }}</option>
                                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>{{ __('fair') }}</option>
                                            <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>{{ __('poor') }}</option>
                                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>{{ __('damaged') }}</option>
                                        </select>
                                        @error('condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">{{ __('image') }}</label>
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">{{ __('notes') }}</label>
                                        <textarea name="notes" 
                                                  id="notes" 
                                                  class="form-control @error('notes') is-invalid @enderror" 
                                                  rows="3"
                                                  placeholder="{{ __('enter_notes') }}">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           class="form-check-input" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('active') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> {{ __('save') }}
                                </button>
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('back') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Auto-generate code based on name
        $('#name').on('blur', function() {
            if (!$('#code').val()) {
                let name = $(this).val().toUpperCase().replace(/\s+/g, '');
                let code = name.substring(0, 6) + Math.floor(Math.random() * 1000);
                $('#code').val(code);
            }
        });

        // Calculate selling price based on unit cost
        $('#unit_cost').on('input', function() {
            let unitCost = parseFloat($(this).val()) || 0;
            if (unitCost > 0 && !$('#selling_price').val()) {
                let sellingPrice = (unitCost * 1.2).toFixed(2); // 20% markup
                $('#selling_price').val(sellingPrice);
            }
        });
    </script>
@endsection