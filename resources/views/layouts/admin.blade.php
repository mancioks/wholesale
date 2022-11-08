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
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @yield('styles')
    @livewireStyles
</head>
<body class="bg-white">
    <div id="app">
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset(setting('logo.white')) }}" alt="" height="34" class="d-inline-block align-text-bottom">
            </a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <div class="dropdown d-inline-block position-relative">
                        <a href="#" class="btn text-white border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset(setting('images.path').'user-avatar-default.png') }}" alt="{{ auth()->user()->name }}" class="bg-white mt-n1 rounded-1" height="25">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu position-absolute mt-2 dropdown-menu-end me-1">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <main class="py-4">
            <div class="container-fluid">
                <div class="row">
                    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                        <div class="position-sticky ps-3 pe-3 pt-3">
                            @include('components.admin.dashboard-navigation')
                        </div>
                    </nav>

                    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                        <h2>@yield('title')</h2>

                        @hasSection('subtitle')
                            <p>@yield('subtitle')</p>
                        @endif

                        @include('components.admin.dashboard-messages')

                        @hasSection('actions')
                            <div class="dashboard-actions">@yield('actions')</div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $.extend( true, $.fn.dataTable.defaults, {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/lt.json",
            },
            "processing": true,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            scrollX: true,
        });
    </script>

    <script>
        // open bootstrap modal with url hash
        $(document).ready(function() {
            // check if page was reloaded
            if (!(window.performance && window.performance.navigation.type === 1)) {
                if (window.location.hash) {
                    var hash = window.location.hash.substring(1);
                    if (hash) {
                        var modal = $('#' + hash);
                        if (modal.length) {
                            modal.modal('show');
                        }
                    }
                }
            }
        });

        // do something when event is triggered
        window.addEventListener('dashboard-sub-link-clicked', event => {
            setTimeout(function() {
                if (window.location.hash) {
                    var hash = window.location.hash.substring(1);
                    if (hash) {
                        var modal = $('#' + hash);
                        if (modal.length) {
                            modal.modal('show');
                        }
                    }
                }
            }, 10);

        });
    </script>

    @yield('scripts')
    @livewireScripts
</body>
</html>
