@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('user.show', $user) }}" class="btn btn-outline-primary">{{ __('User info') }}</a>
                            <a href="{{ route('user.orders', $user) }}" class="btn btn-outline-primary">{{ __('Orders') }}</a>
                            <a href="{{ route('user.items', $user) }}" class="btn btn-primary">{{ __('Ordered items') }}</a>
                            <a href="{{ route('user.prices', $user) }}" class="btn btn-outline-primary">{{ __('User prices') }}</a>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <h2>{{ $user->name }} {{ __('Ordered items') }}</h2>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('user.items', $user) }}" method="get">
                                <div class="row g-2 mb-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="filter_from" class="col-form-label">{{ __('From') }}</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" id="filter_from" class="form-control" name="filter_from" max="{{ date('Y-m-d') }}" value="{{ request()->get('filter_from') }}">
                                    </div>
                                    <div class="col-auto">
                                        <label for="filter_to" class="col-form-label">{{ __('To') }}</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" id="filter_to" class="form-control" name="filter_to" max="{{ date('Y-m-d') }}" value="{{ request()->get('filter_to') }}">
                                    </div>
                                    <div class="col-auto pe-2">
                                        <button type="submit" class="btn btn-primary">{{ __('Do filter') }}</button>
                                    </div>
                                    <div class="col-auto">
                                        @if($filtering)
                                            <div>
                                                {{ __('Showing') }}
                                                @if(request()->get('filter_from'))
                                                    {{ sprintf(__('from %s'), request()->get('filter_from')) }}
                                                @endif
                                                @if(request()->get('filter_to'))
                                                    {{ sprintf(__('to %s'), request()->get('filter_to')) }}
                                                @endif
                                                <div class="mt-n2">
                                                    <a href="{{ route('user.items', $user) }}" class="small text-decoration-none text-primary">{{ __('Show all') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <table class="table">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Units') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $name => $group)
                                    @foreach($group as $price => $item)
                                        <tr>
                                            <td>{{ $name }}</td>
                                            <td>{{ $price }}</td>
                                            <td>{{ $item->sum('qty') }}</td>
                                            <td>{{ $item->first()->units }}</td>
                                            <td>
                                                <img src="{{ asset($item->first()->image->name) }}" class="card-img-top w-auto" style="height: 30px;">
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5">{{ __('No items') }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
