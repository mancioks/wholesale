<div class="dashboard-sub-link-padding">
    <a href="{{ route($route) }}" class="dashboard-sub-link {{ \Request::route()->getName() === $route ? 'text-light' : '' }}">
        <i class="{{ $icon }}"></i>
        {{ __($title) }}
    </a>
</div>
