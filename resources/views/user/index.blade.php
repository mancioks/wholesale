@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ __('Users') }}</h2>
                        @role('admin', 'super_admin')
                        <div class="actions-wrapper">
                            <a href="{{ route('user.create') }}" class="btn btn-success">Create user</a>
                        </div>
                        @endrole
                        <table class="table mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Role') }}</th>
                                <th scope="col">{{ __('Activated') }}</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td><a href="{{ route('user.show', $user->id) }}" class="text-black fw-bold">{{ $user->name }}</a></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->name }}</td>
                                    <td>{{ $user->activated ? __('Yes') : __('No') }}</td>
                                    <td>
                                        @role('admin', 'super_admin')
                                            @if($user->activated)
                                                <a href="{{ route('user.deactivate', $user->id) }}" class="btn btn-warning btn-sm d-inline-block">{{ __('Deactivate') }}</a>
                                            @else
                                                <a href="{{ route('user.activate', $user->id) }}" class="btn btn-success btn-sm d-inline-block">{{ __('Activate') }}</a>
                                            @endif
                                        @endrole
                                    </td>
                                    <td>
                                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-primary btn-sm d-inline-block">{{ __('View') }}</a>
                                        <a href="{{ route('user.act-as', $user->id) }}" class="btn btn-secondary btn-sm d-inline-block">{{ __('Create order') }}</a>
                                        @role('admin', 'super_admin')
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm d-inline-block">{{ __('Edit') }}</a>
                                        <form method="post" action="{{ route('user.destroy', $user->id) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm d-inline-block">Delete</button>
                                        </form>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">{{ __('No users') }}</td>
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
