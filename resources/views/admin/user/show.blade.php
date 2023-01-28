@extends('layouts.admin')

@section('title')
    {{ __('User') }} {{ $user->name }}
@endsection
@section('actions')
    @role('admin', 'super_admin')
    @if($user->activated)
        @include('components.admin.dashboard-action', ['route' => route('user.deactivate', $user->id), 'title' => __('Deactivate'), 'class' => 'btn-warning', 'icon' => 'bi bi-universal-access'])
    @else
        @include('components.admin.dashboard-action', ['route' => route('user.activate', $user->id), 'title' => __('Activate'), 'class' => 'btn-primary', 'icon' => 'bi bi-universal-access'])
    @endif
    @include('components.admin.modals.edit-user')
    @endrole
    @role('super_admin')
    <form method="post" action="{{ route('user.destroy', $user) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger d-inline-block">
            <i class="bi bi-trash3-fill"></i> {{ __('Delete') }}
        </button>
    </form>
    @endrole
@endsection
@section('content')
    <div class="mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="shadow-sm card">
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <thead class="table-dark">

                            </thead>
                            <tbody>
                            <tr>
                                <th scope="col">{{ __('Full name') }}</th>
                                <th scope="col">{{ $user->name }}</th>
                            </tr>
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
                            <tr>
                                <th scope="row">{{ __('Activated') }}</th>
                                <td>
                                    {{ $user->activated ? __('Yes') : __('No') }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="shadow-sm card mt-3">
                    <div class="card-body">
                        <h4>{{ __('User prices') }}</h4>
                        @livewire('admin.user-prices', ['user' => $user])
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shadow-sm card">
                    <div class="card-body">
                        <h4>{{ __('Orders') }}</h4>
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th>#</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Created') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.order.show', $order) }}">{{ $order->number }}</a>
                                        </td>
                                        <td>
                                            <div class="badge {{ $order->list_class }}">
                                                {{ $order->status->name }}
                                            </div>
                                        </td>
                                        <td>{{ $order->total }} €</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">{{ __('No orders') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            window.livewire.on('userUpdated', () => {
                setTimeout(() => {
                    $('#edit-user-modal').modal('hide');
                    location.reload();
                }, 1000);
            });
        });
    </script>
@endsection
