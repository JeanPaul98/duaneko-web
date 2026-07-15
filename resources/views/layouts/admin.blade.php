<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('admin-template/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-template/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-template/assets/fonts/material.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('admin-template/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Mobile header ] start -->
    <div class="pc-mob-header pc-header">
        <div class="pcm-logo">
            <img src="{{ asset('admin-template/assets/images/logo.svg') }}" alt="" class="logo logo-lg">
        </div>
        <div class="pcm-toolbar">
            <a href="#!" class="pc-head-link" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <a href="#!" class="pc-head-link" id="headerdrp-collapse">
                <i data-feather="align-right"></i>
            </a>
            <a href="#!" class="pc-head-link" id="header-collapse">
                <i data-feather="more-vertical"></i>
            </a>
        </div>
    </div>
    <!-- [ Mobile header ] End -->

    <!-- [ navigation menu ] start -->
    <nav class="pc-sidebar ">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    <img src="{{ asset('admin-template/assets/images/logo.svg') }}" alt="" class="logo logo-lg">
                    <img src="{{ asset('admin-template/assets/images/logo-sm.svg') }}" alt="" class="logo logo-sm">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="{{ route('home') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">home</i></span><span class="pc-mtext">Tableau de
                                    bord</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('reports.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">my_location</i></span><span
                                class="pc-mtext">Signalements</span></a>
                    </li>
                    
                    @if ((Auth::guard('admin')->check()) || (Auth::guard('manager')->check()))
                    <li class="pc-item">
                        <a href="{{ route('agents.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">group</i></span><span
                                class="pc-mtext">Agents</span></a>
                    </li>
                    @endif
                    @if(Auth::guard('admin')->check())
                    <li class="pc-item">
                        <a href="{{ route('companies.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">business</i></span><span
                                class="pc-mtext">Entreprises</span></a>
                    </li>
                    @endif
                    @if (Auth::guard('admin')->check())
                    <li class="pc-item">
                        <a href="{{ route('managers.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">airline_seat_recline_extra</i></span><span   
                                class="pc-mtext">Manageurs</span></a>
                    </li>
                   @endif
                   
                    <li class="pc-item">
                        <a href="{{ route('ramassages.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">cleaning_services</i></span><span
                                class="pc-mtext">Ramassages</span></a>
                    </li>
                    

                    <li class="pc-item">
                        <a href="{{ route('zones.index') }}" class="pc-link "><span class="pc-micon"><i
                                    class="material-icons-two-tone">business</i></span><span
                                class="pc-mtext">Zones</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
    <!-- [ Header ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="mr-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link active dropdown-toggle arrow-none mr-0" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ml-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ asset('admin-template/assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                class="user-avtar">
                            <span>
                                <span class="user-name">Non utilisateur</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pc-h-dropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="material-icons-two-tone">chrome_reader_mode</i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    @yield('content')

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Warning Section Ends -->
    <!-- Required Js -->
    <script src="{{ asset('admin-template/assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('admin-template/assets/js/pcoded.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script> -->
    <!-- <script src="assets/js/plugins/clipboard.min.js"></script> -->
    <!-- <script src="assets/js/uikit.min.js"></script> -->

    <!-- Apex Chart -->
    {{-- <script src="{{asset('admin-template/assets/js/plugins/apexcharts.min.js"></script> --}}

    <!-- custom-chart js -->
    {{-- <script src="{{asset('admin-template/assets/js/pages/dashboard-sale.js"></script> --}}

</body>

</html>
