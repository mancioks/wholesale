@extends('layouts.admin')

@section('title')
    {{ __('Settings') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="post" action="{{ route('settings.update') }}">
                @csrf
                @method('put')
                @foreach($settings as $setting)
                    <div class="row mb-3">
                        <div class="col-lg-5">
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
@endsection
