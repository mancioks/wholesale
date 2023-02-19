@extends('layouts.admin')

@section('title')
    {{ __('BONUS calculator rules') }}
@endsection

@section('content')
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('admin.tools.bonus_calculator.create-rule') }}" method="POST">
                @csrf
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr class="bg-secondary text-white">
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Sum') }}</th>
                        <th scope="col">{{ __('MIN price') }}</th>
                        <th scope="col">{{ __('MIN sum') }}</th>
                        <th scope="col">{{ __('MID price') }}</th>
                        <th scope="col">{{ __('MID sum') }}</th>
                        <th scope="col">{{ __('MAX price') }}</th>
                        <th scope="col">{{ __('MAX sum') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rules as $rule)
                        <tr>
                            <td>{{ $rule->name }}</td>
                            <td>{{ $rule->price }}</td>
                            <td>{{ $rule->sum }}</td>
                            <td>{{ $rule->min_price }}</td>
                            <td>{{ $rule->min_sum }}</td>
                            <td>{{ $rule->mid_price }}</td>
                            <td>{{ $rule->mid_sum }}</td>
                            <td>{{ $rule->max_price }}</td>
                            <td>{{ $rule->max_sum }}</td>
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">{{ __('Empty') }}</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td><input type="text" class="form-control form-control-sm" name="name"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="price"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="sum"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="min_price"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="min_sum"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="mid_price"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="mid_sum"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="max_price"/></td>
                        <td><input type="text" class="form-control form-control-sm" name="max_sum"/></td>
                        <td><button type="submit" class="btn btn-sm btn-success">{{ __('Create') }}</button></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
