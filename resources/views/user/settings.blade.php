@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('User settings') }}</h2>
                        <form method="post" action="{{ route('user.settings.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-3">
                                    <label class="form-label">{{ __('Company name') }}</label>
                                    <input type="text" class="form-control" name="company_name" value="{{ $user->details ? $user->details->company_name : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <input type="text" class="form-control" name="address" value="{{ $user->details ? $user->details->address : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3">
                                    <label class="form-label">{{ __('Registration code') }}</label>
                                    <input type="text" class="form-control" name="registration_code" value="{{ $user->details ? $user->details->registration_code : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">{{ __('VAT number') }}</label>
                                    <input type="text" class="form-control" name="vat_number" value="{{ $user->details ? $user->details->vat_number : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3">
                                    <label class="form-label">{{ __('Phone number') }}</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $user->details ? $user->details->phone_number : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" role="switch" id="user_pvm" value="true" name="get_email_notifications" {{ $user->details ? ($user->details->get_email_notifications ? 'checked' : '') : 'checked' }}>
                                        <label class="form-check-label" for="user_pvm">{{ __('Get email notifications') }}</label>
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
