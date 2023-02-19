@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="post" action="{{ route('admin.tools.bonus_calculator.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="employee" class="form-label">{{ __('Employee') }}</label>
                        <input type="text" name="employee" id="employee" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <label for="product_file" class="form-label">{{ __('Report') }}</label>
                        <input type="file" name="report" id="product_file" class="form-control" accept=".csv,.xlsx" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
            </form>
        </div>
    </div>
@endsection
