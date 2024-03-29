<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    @livewireStyles
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white position-fixed w-100" style="z-index:1030;">
            <div class="container d-block d-md-flex">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset(setting('logo')) }}" alt="" height="34" class="d-inline-block align-text-bottom">
                </a>
                <button class="navbar-toggler float-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @auth
                    <a class="btn btn-warning float-end me-2 d-md-none position-relative" href="{{ route('cart') }}">
                        <i class="bi bi-basket3-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ auth()->user()->cart_count }}
                        </span>
                        {{ auth()->user()->sub_total }}€
                    </a>
                @endauth
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @role('super_admin', 'warehouse')
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">{{ __('Administration') }}</a>
                            </li>
                            @endrole

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a href="{{ route('user.settings') }}" class="dropdown-item">{{ __('Settings') }}</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item ms-2 d-none d-md-block">
                                <a class="btn btn-warning position-relative" href="{{ route('cart') }}">
                                    <i class="bi bi-basket3-fill"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->cart_count }}
                                    </span>
                                    {{ auth()->user()->sub_total }}€
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div style="height: 60px;"></div>
        <main class="py-4">
            @route('order.create', 'cart', 'order.review')
                @if(auth()->user()->acting()->exists())
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="alert alert-primary">
                                    {{ sprintf(__('Creating order for %s'), auth()->user()->acting->name) }}
                                    <div class="pt-2">
                                        <a href="{{ route('user.remove-acting') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endroute
            @if ($errors->any())
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('status'))
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @yield('scripts')
    @livewireScripts
</body>
</html>
