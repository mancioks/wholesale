@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('subtitle')
    {{ __('Manager') }}: <b>{{ $calculation->manager->name }}</b><br>
    {{ __('Installer') }}: <b>{{ $calculation->installer->name }}</b><br>
    {{ __('Object') }}: <b>{{ $calculation->object }}</b><br>
    {{ __('Date') }}: <b>{{ $calculation->date }}</b><br>
    {{ __('Created at') }}: <b>{{ $calculation->created_at }}</b>
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.bonus-calculator', ['calculation' => $calculation])
        </div>
    </div>
@endsection
