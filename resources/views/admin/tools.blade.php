@extends('layouts.admin')

@section('title')
    {{ __('Tools') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="list-group">
                        <a href="{{ route('admin.tools.bonus_calculator') }}" class="list-group-item list-group-item-action lh-1 active">
                            <i class="bi bi-percent"></i> {{ __('BONUS calculator') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
