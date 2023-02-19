@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('actions')
    @include('components.admin.dashboard-action', ['route' => route('admin.tools.bonus_calculator.create'), 'title' => __('Create'), 'class' => 'btn-success', 'icon' => 'bi bi-percent'])
    @include('components.admin.dashboard-action', ['route' => route('admin.tools.bonus_calculator.rules'), 'title' => __('Rules'), 'class' => 'btn-warning', 'icon' => 'bi bi-subtract'])
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <thead>
                <tr class="bg-secondary text-white">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Employee') }}</th>
                    <th scope="col">{{ __('Calculated by') }}</th>
                    <th scope="col">{{ __('Date') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($calculations as $calculation)
                    <tr>
                        <td>{{ $calculation->id }}</td>
                        <td>{{ $calculation->name }}</td>
                        <td>{{ $calculation->employee }}</td>
                        <td>{{ $calculation->user->name }}</td>
                        <td>{{ $calculation->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.tools.bonus_calculator.show', $calculation) }}" class="btn btn-primary btn-sm">{{ __('Show') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">{{ __('Empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
