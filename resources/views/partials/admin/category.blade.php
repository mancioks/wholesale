@foreach($categories as $category)
    <div class="bg-light border border-secondary border-opacity-50 px-2 py-1 rounded rounded-1 shadow shadow-sm" style="max-width: 300px; margin-bottom: 3px;">
        {{ __($category->name) }}
    </div>
    @if($category->children->count() > 0)
        <div class="ms-4">
            @include('partials.admin.category', ['categories' => $category->children])
        </div>
    @endif
@endforeach
