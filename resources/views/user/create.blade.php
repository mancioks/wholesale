@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Create user') }}</h2>
                        <form method="post" action="{{ route('user.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div>
                                        <label for="user_name" class="form-label">{{ __('Full name') }}</label>
                                        <input type="text" class="form-control" id="user_name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div>
                                        <label for="user_email" class="form-label">{{ __('Email') }}</label>
                                        <input type="email" class="form-control" id="user_email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div>
                                        <label for="user_password" class="form-label">{{ __('Password') }}</label>
                                        <input type="password" class="form-control" id="user_password" name="password" value="{{ old('password') }}" required>
                                    </div>
                                    <div>
                                        <label for="user_role" class="form-label">{{ __('Role') }}</label>
                                        <select class="form-select" id="user_role" name="role_id">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $role->id == old('role_id') ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="user_pvm" value="true" name="pvm" checked>
                                            <label class="form-check-label" for="user_pvm">{{ __('PVM') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
