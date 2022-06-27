@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Settings') }}</h2>
                        <form method="post" action="{{ route('settings.update') }}">
                            @csrf
                            @method('put')
                            @foreach($settings as $setting)
                                <div class="row mb-3">
                                    <div class="col-3">
                                        <label class="form-label">{{ $setting->title }}</label>
                                        <input type="hidden" class="form-control" name="name[]" value="{{ $setting->name }}" required>
                                        @if($setting->type != 'textarea')
                                            <input type="{{ $setting->type }}" class="form-control" name="value[]" value="{{ $setting->value }}" required>
                                        @else
                                            <textarea class="form-control" name="value[]" rows="5">{{ $setting->value }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
