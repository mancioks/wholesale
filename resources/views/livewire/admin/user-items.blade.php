<div>
    <h4>{{ __('Ordered items') }}</h4>
    <div>
        <div class="row">
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="product_type" class="form-label">{{ __('User') }}</label>
                    <select class="form-select" id="product_type" wire:model="selectedUser">
                        <option hidden>{{ __('Select') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ __($user->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="order_type" class="form-label">{{ __('Type') }}</label>
                    <select class="form-select" id="order_type" wire:model="orderType">
                        @foreach($orderTypes as $type => $name)
                            <option value="{{ $type }}">{{ __($name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="product_type" class="form-label">{{ __('From') }}</label>
                    <input type="date" id="filter_from" class="form-control" wire:model="filteringFrom" max="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="product_type" class="form-label">{{ __('To') }}</label>
                    <input type="date" id="filter_to" class="form-control" wire:model="filteringTo" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>
    @if($selectedUser)
        @if($filtering)
            <div class="mb-3">
                {{ __('Showing') }}
                @if($filteringFrom)
                    {{ sprintf(__('from %s'), $filteringFrom) }}
                @endif
                @if($filteringTo)
                    {{ sprintf(__('to %s'), $filteringTo) }}
                @endif
                <div class="mt-n2">
                    <a href="#" class="small text-decoration-none text-primary" wire:click.prevent="clearFilter">{{ __('Show all') }}</a>
                </div>
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
            <tr class="bg-secondary text-white">
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
    @endif
</div>
