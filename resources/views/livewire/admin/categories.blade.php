<div>
    <div class="input-group">
        <input type="text" class="form-control" wire:model="categoryName" placeholder="{{ __('Category name') }}">
        <select class="form-select" wire:model="selectedCategory">
            <option value="">{{ __('Select parent category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ __($category->name) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" wire:click="add">{{ __('Add') }}</button>
    </div>
    @error('categoryName') <div class="text-danger">{{ $message }}</div> @enderror
    @error('selectedCategory') <div class="text-danger">{{ $message }}</div> @enderror

    <div class="mt-3">
        @include('partials.admin.category', ['categories' => $masterCategories])
    </div>
</div>
