<div>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Create discount rule') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @if($success)
            <div class="alert alert-success">
                {{ __('Discount rule created successfully') }}
            </div>
        @endif
        <div class="row">
            <div class="col-3">
                <label class="form-label" for="model-name">{{ __('Applies to') }}</label>
                <select class="form-select" id="model-name" wire:model="discountRule.model_name">
                    <option selected>{{ __('Select model name') }}</option>
                    @foreach($models as $model => $modelName)
                        <option value="{{ $model }}">{{ $modelName }}</option>
                    @endforeach
                </select>
                @error('discountRule.model_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-3">
                <label class="form-label" for="discount-type">{{ __('Discount type') }}</label>
                <select id="discount-type" class="form-select" wire:model="discountRule.type">
                    <option selected>{{ __('Select type') }}</option>
                    @foreach($types as $type => $typeName)
                        <option value="{{ $type }}">{{ $typeName }}</option>
                    @endforeach
                </select>
                @error('discountRule.type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-2">
                <label class="form-label" for="discount-size">{{ __('Discount size') }}</label>
                <input type="number" class="form-control" id="discount-size" wire:model="discountRule.size">
                @error('discountRule.size') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-2">
                <label class="form-label" for="discount-from">{{ __('From') }}</label>
                <input type="number" class="form-control" id="discount-from" wire:model="discountRule.from">
                @error('discountRule.from') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-2">
                <label class="form-label" for="discount-to">{{ __('To') }}</label>
                <input type="number" class="form-control" id="discount-to" wire:model="discountRule.to">
                @error('discountRule.to') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($discountRule->model_name !== '')
            <div class="mt-3">
                @if ($selectedModel)

                    <div class="input-group">
                        <div class="form-control">{{ $selectedModel->name }}</div>
                        <button class="btn btn-primary" wire:click="changeSelectedModel">{{ __('Change') }}</button>
                    </div>
                @else
                    @if ($discountRule->model_name === 'product')
                        <div class="position-relative">
                            <input wire:model.debounce.200ms="searchQuery" id="product-search" placeholder="{{ __('Select product') }}" class="form-control shadow-none {{ !$searchQuery ?: 'rounded-0 rounded-top' }}">
                            <div class="form-control position-absolute start-0 end-0 {{ !$searchQuery ? 'd-none' : 'rounded-0 rounded-bottom border-top-0 shadow' }}">
                                @if ($searchResults->isNotEmpty())
                                    <div class="products-wrapper row gx-2 gy-2 pt-1 pb-1">
                                        @foreach($searchResults as $searchResult)
                                            <div class="col-lg-3 col-md-3 col-sm-4 col-6" wire:click="selectProduct({{ $searchResult->id }})" role="button">
                                                <div class="card shadow-sm">
                                                    <div class="card-body p-2">
                                                        <div class="bg-light p-2 text-center mb-2 rounded">
                                                            <img src="{{ asset($searchResult->image->name) }}" style="max-height: 50px">
                                                        </div>
                                                        <h5 class="card-title fs-6 mb-0">{{ $searchResult->name }}</h5>
                                                        <p class="card-text mb-n1 fs-6">
                                                            {{ $searchResult->original_price }}€ / {{ $searchResult->units }}
                                                            @if($searchResult->pivot && !$searchResult->pivot->enabled)
                                                                <span class="badge bg-danger ms-1">{{ __('Not for sale') }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    @if ($searchQuery && $searchQuery !== '')
                                        <div>No results</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ($discountRule->model_name === 'category')
                        <select id="discount-category" class="form-select" wire:model="discountRule.model_id">
                            <option selected>{{ __('Select category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    @endif
                @endif
            </div>
        @endif
        @error('discountRule.model_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button wire:click.prevent="submit" class="btn btn-primary">{{ __('Create') }}</button>
    </div>
</div>
