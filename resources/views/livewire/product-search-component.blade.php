<div>
    <div class="position-relative">
        <input wire:model="searchQuery" type="text" placeholder="{{ __('Search') }}" class="form-control rounded-pill shadow bg-white border-0 ps-3">
    </div>
    {{ $searchQuery }}
    @foreach($searchResults as $result)
        <div>{{ $result->name }}</div>
    @endforeach
</div>
