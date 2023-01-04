@extends('layouts.admin')

@section('title')
    {{ __('Product') }} {{ $product->name }}
@endsection
@section('actions')
    @role('warehouse', 'admin', 'super_admin')
{{--    @include('components.admin.modals.edit-product', ['product' => $product])--}}
    @endrole
    @role('super_admin', 'warehouse', 'admin')
    <form method="post" action="{{ route('product.destroy', $product) }}" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}')">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger d-inline-block">
            <i class="bi bi-trash3-fill"></i> {{ __('Delete') }}
        </button>
    </form>
    @endrole
@endsection
@section('content')
    <div class="mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-12 order-last order-lg-first mt-3 mt-lg-0">
                <div class="shadow-sm card">
                    <div class="card-body">
                        @livewire('admin.edit-product', ['product' => $product])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            window.livewire.on('productUpdated', () => {
                setTimeout(() => {
                    $('#edit-product-modal').modal('hide');
                    location.reload();
                }, 1000);
            });
        });
    </script>
@endsection
