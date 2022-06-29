@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Edit warehouse') }}</h2>
                        <form method="post" action="{{ route('warehouse.update', $warehouse) }}">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="name" value="{{ $warehouse->name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <input type="text" class="form-control" name="address" value="{{ $warehouse->address }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label class="form-label">{{ __('Phone number') }}</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $warehouse->phone_number }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ $warehouse->email }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" role="switch" id="active_warehouse" value="true" name="active" {{ !$warehouse->active ?: 'checked' }}>
                                        <label class="form-check-label" for="active_warehouse">{{ __('Can select') }}</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
