<a
    class="dashboard-link {{ \Request::route()->getName() === $route ? 'bg-primary' : '' }}"
    href="{{ route($route) }}"
    {!! isset($target) ? 'target="'.$target.'"' : '' !!}
>
    <i class="{{ $icon }} me-1"></i>
    {{ __($title) }}
</a>
