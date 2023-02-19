@extends('layouts.admin')

@section('title')
    {{ __('Inventorization') }} {{ $inventorization->warehouse->name }} {{ $inventorization->date }}
@endsection
@section('actions')
    @role('super_admin', 'warehouse', 'admin')
    @include('components.admin.dashboard-action', ['route' => route('admin.inventorization.add-all', $inventorization), 'title' => __('Add all warehouse products'), 'class' => 'btn-warning', 'icon' => 'bi bi-arrow-right-square'])

    <div class="btn-group">
        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-file-earmark-text"></i> {{ __('Export') }}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" target="_blank" href="{{ route('admin.inventorization.export', [$inventorization, 'pdf']) }}"><i class="bi bi-file-pdf"></i> PDF</a></li>
        </ul>
    </div>

    <form method="post" action="{{ route('admin.inventorization.destroy', $inventorization) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
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
            <div class="col-lg-12 order-last order-lg-first mt-3 mt-lg-0">
                <div class="shadow-sm card">
                    <div class="card-body">
                        @livewire('admin.edit-inventorization', ['inventorization' => $inventorization])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
