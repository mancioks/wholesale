<ul class="nav flex-column">
    @role('admin', 'super_admin')
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.dashboard', 'icon' => 'bi bi-house', 'title' => 'Dashboard'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'home', 'icon' => 'bi bi-globe2', 'title' => 'D.BNVI.LT', 'target' => '_blank'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.product.index', 'icon' => 'bi bi-cart', 'title' => 'Products'])
        @include('components.admin.dashboard-sub-link', ['route' => 'admin.product.index', 'icon' => 'bi bi-plus-circle', 'title' => 'Create product', 'hash' => 'create-product-modal'])
        @include('components.admin.dashboard-sub-link', ['route' => 'product.import', 'icon' => 'bi bi-filetype-csv', 'title' => 'Import products'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.order.index', 'icon' => 'bi bi-card-checklist', 'title' => 'Orders'])
        @include('components.admin.dashboard-sub-link', ['route' => 'admin.order.index', 'icon' => 'bi bi-plus-circle', 'title' => 'Create order', 'hash' => 'create-order-modal'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'payments', 'icon' => 'bi bi-cash-coin', 'title' => 'Payments'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.user.index', 'icon' => 'bi bi-people', 'title' => 'Users'])
        @include('components.admin.dashboard-sub-link', ['route' => 'user.create', 'icon' => 'bi bi-plus-circle', 'title' => 'Create user'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.warehouse.index', 'icon' => 'bi bi-building', 'title' => 'Warehouses'])
        @include('components.admin.dashboard-sub-link', ['route' => 'warehouse.create', 'icon' => 'bi bi-plus-circle', 'title' => 'Add warehouse'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.settings', 'icon' => 'bi bi-gear', 'title' => 'Settings'])
    </li>
    @endrole

    @role('warehouse')
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.dashboard', 'icon' => 'bi bi-house', 'title' => 'Dashboard'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'home', 'icon' => 'bi bi-globe2', 'title' => 'D.BNVI.LT', 'target' => '_blank'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.product.index', 'icon' => 'bi bi-cart', 'title' => 'Products'])
        @include('components.admin.dashboard-sub-link', ['route' => 'admin.product.index', 'icon' => 'bi bi-plus-circle', 'title' => 'Create product', 'hash' => 'create-product-modal'])
        @include('components.admin.dashboard-sub-link', ['route' => 'product.import', 'icon' => 'bi bi-filetype-csv', 'title' => 'Import products'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.order.index', 'icon' => 'bi bi-card-checklist', 'title' => 'Orders'])
        @include('components.admin.dashboard-sub-link', ['route' => 'admin.order.index', 'icon' => 'bi bi-plus-circle', 'title' => 'Create order', 'hash' => 'create-order-modal'])
    </li>
    <li class="nav-item">
        @include('components.admin.dashboard-link', ['route' => 'admin.warehouse.index', 'icon' => 'bi bi-building', 'title' => 'Warehouses'])
        @include('components.admin.dashboard-sub-link', ['route' => 'warehouse.create', 'icon' => 'bi bi-plus-circle', 'title' => 'Add warehouse'])
    </li>
    @endrole
</ul>
