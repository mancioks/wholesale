@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('subtitle')
    <b>{{ $calculation->employee }}</b>
    {{ $calculation->name }}
    {{ __('Created at') }}: {{ $calculation->created_at }}
    {{ __('Updated') }}: {{ $calculation->updated_at }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.bonus-calculator', ['calculation' => $calculation])
        </div>
    </div>
@endsection
