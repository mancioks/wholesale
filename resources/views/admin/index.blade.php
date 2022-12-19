@extends('layouts.admin')

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.user-items')
        </div>
    </div>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.user-items-discount')
        </div>
    </div>
@endsection
