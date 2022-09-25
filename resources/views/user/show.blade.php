@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('user.show', $user) }}" class="btn btn-primary">{{ __('User info') }}</a>
                            <a href="{{ route('user.orders', $user) }}" class="btn btn-outline-primary">{{ __('Orders') }}</a>
                            <a href="{{ route('user.items', $user) }}" class="btn btn-outline-primary">{{ __('Ordered items') }}</a>
                            <a href="{{ route('user.prices', $user) }}" class="btn btn-outline-primary">{{ __('User prices') }}</a>
                        </div>
                        <h2 class="d-inline-block">{{ __('User') }}: {{ $user->name }}</h2>
                        <div class="d-inline-block">
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-sm ms-3 d-inline-block mt-n2">{{ __('Edit') }}</a>
                        </div>
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">{{ __('Full name') }}</th>
                                <th scope="col">{{ $user->name }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Email') }}</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Role') }}</th>
                                <td>{{ $user->role->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Warehouse') }}</th>
                                <td>
                                    @if($user->warehouse)
                                        {{ $user->warehouse->name }}
                                    @else
                                        {{ __('Not selected') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('VAT') }}</th>
                                <td>{{ $user->pvm_size }}%</td>
                            </tr>
                            @if($user->details)
                                <tr>
                                    <th scope="row">{{ __('Company name') }}</th>
                                    <td>{{ $user->details->company_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Address') }}</th>
                                    <td>{{ $user->details->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Registration code') }}</th>
                                    <td>{{ $user->details->registration_code }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('VAT number') }}</th>
                                    <td>{{ $user->details->vat_number }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Phone number') }}</th>
                                    <td>{{ $user->details->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Get email notifications') }}</th>
                                    <td>{{ $user->get_emails ? 'Enabled' : 'Disabled' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <th scope="row">{{ __('Details') }}</th>
                                    <td>{{ __('Not provided') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">{{ __('Registered') }}</th>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
