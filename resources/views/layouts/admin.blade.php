<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.lt.min.js" integrity="sha512-baSR4+Wsg+wOLRRW4S2ZYU4MJYFWyF5ehdeEUs1Y4jNZJPlL1O47rN0oJPAX87ojSaEvIzq1V1DZyVAKaHG+Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" integrity="sha512-rxThY3LYIfYsVCWPCW9dB0k+e3RZB39f23ylUYTEuZMDrN/vRqLdaCBo/FbvVT6uC2r0ObfPzotsfKF9Qc5W5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- bootstrap select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

                        @yield('livewire-messages')

                        @hasSection('actions')
                            <div class="dashboard-actions">
                                @yield('actions')
                                @yield('inner-actions')
                            </div>
                        @else
                            @hasSection('inner-actions')
                                <div class="dashboard-actions">
                                    @yield('inner-actions')
                                </div>
                            @endif
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
        // scroll to top when datatable page changes
        $(document).on('click', '.dataTables_paginate a', function() {
            $('html, body').animate({
                scrollTop: 0
            }, 0);
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

    <script>
        document.querySelectorAll('[data-click-button]').forEach(function (element) {
            element.addEventListener('click', function () {
                document.getElementById(element.getAttribute('data-click-button')).click();
            });
        });
    </script>

    <script>
        function initSelect2() {
            $(".select2").select2({
                theme: "bootstrap-5",
                selectionCssClass: "select2--small",
                dropdownCssClass: "select2--small",
            });
        }

        // init bootstrap tooltips on page load
        $(function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })

        // init select2 on page load
        $(function () {
            initSelect2();
        });

        // live wire hook to init select2 after livewire component is loaded
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', () => {
                initSelect2();
            });
        });

        // fix select2 dropdown closing when clicking on it
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.select2-container').length) {
                $('.select2').select2('close');
            }
        });
    </script>

    @yield('scripts')
    @livewireScripts
</body>
</html>
