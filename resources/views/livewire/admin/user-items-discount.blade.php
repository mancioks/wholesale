<div>
    <div class="row">
        <div class="col-6">
            <h4>{{ __('Ordered items') }}</h4>
        </div>
        <div class="col-6 text-end">
            @if($selectedUser && count($items) > 0)
                <a href="#" class="btn btn-sm btn-primary d-inline-block" wire:click="exportStats">
                    <i class="bi bi-file-earmark-spreadsheet"></i> {{ __('Export') }}
                </a>
            @endif
        </div>
    </div>
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
                <th width="10"></th>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Code') }}</th>
                <th scope="col">{{ __('Price') }}</th>
                <th scope="col">{{ __('Price with PVM') }}</th>
                <th scope="col">{{ __('Prime cost') }}</th>
                <th scope="col">{{ __('Quantity') }}</th>
                <th scope="col">{{ __('Amount') }}</th>
                <th scope="col">{{ __('Discount') }}</th>
                <th scope="col">{{ __('Total') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><img src="{{ asset($item['item']->image->name) }}" class="card-img-top w-auto" style="height: 30px;"></td>
                    <td>{{ $item['item']->name }}</td>
                    <td>{{ $item['item']->code }}</td>
                    <td>{{ $item['item']->price }} €</td>
                    <td>{{ $item['item']->priceWithPvm }} €</td>
                    <td>{{ $item['item']->prime_cost }} €</td>
                    <td>{{ $item['quantity'] }} {{ $item['item']->units }}</td>
                    <td>{{ $item['amount'] }} €</td>
                    <td>
                        @if(isset($item['discount']))
                            {{ $item['discount_size'] }}
                            {{ $item['discount']->type == 'percent' ? '%' : '€ / ' . $item['item']->units }}
                        @else
                            0
                        @endif
                    </td>
                    <td>{{ $item['total'] }} €</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">{{ __('No items') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endif
</div>
