@extends('layouts.admin')

@section('title')
    {{ __('Categories') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.categories')
        </div>
    </div>
@endsection
