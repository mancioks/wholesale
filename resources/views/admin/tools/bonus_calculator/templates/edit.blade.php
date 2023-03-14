@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('subtitle')
    {{ __('Template') }} {{ $template->name }}
@endsection

@section('actions')
    {{ back_button(route('admin.tools.bonus_calculator.templates')) }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @livewire('admin.calculator.edit-template', ['template' => $template])
        </div>
    </div>
@endsection
