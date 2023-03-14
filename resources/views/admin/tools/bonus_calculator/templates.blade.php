@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('subtitle')
    {{ __('Templates') }}
@endsection

@section('actions')
    {{ back_button(route('admin.tools.bonus_calculator')) }}
    @include('components.admin.modals.calculator-create-template')
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <thead>
                <tr class="bg-secondary text-white">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->id }}</td>
                        <td>{{ $template->name }}</td>
                        <td>
                            <a href="{{ route('admin.tools.bonus_calculator.templates.edit', $template) }}" class="btn btn-sm btn-primary">{{ __('Show') }}</a>
                            <a href="{{ route('admin.tools.bonus_calculator.templates.delete', $template) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">{{ __('Empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
