<div class="dashboard-sub-link-padding">
    <a
        href="{{ route($route) }}{{ isset($hash) ? '#'.$hash : '' }}"
        class="dashboard-sub-link"
        onclick="window.dispatchEvent(new CustomEvent('dashboard-sub-link-clicked'))"
    >
        <i class="{{ $icon }}"></i>
        {{ __($title) }}
    </a>
</div>
