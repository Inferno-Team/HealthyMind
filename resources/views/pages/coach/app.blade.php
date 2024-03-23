<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='{{ asset('assets/css/jquery.toast.min.css') }}' rel='stylesheet'>

    <title>
        Healthy Mind
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/theme-default.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <script type="module">
        import Echo from "{{ asset('assets/js/echo.js') }}"
        import {
            Pusher
        } from "{{ asset('assets/js/pusher.js') }}"

        window.Pusher = Pusher

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: "{{ env('PUSHER_APP_KEY') }}",
            wsHost: "{{ env('PUSHER_HOST') }}",
            wsPort: "{{ env('PUSHER_PORT') }}",
            forceTLS: false,
            disableStats: true,
            authEndpoint: '/authenticate_websocket',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            }
        });
    </script>

</head>

<body class="{{ $class ?? '' }}">

    @guest
        @yield('content')
    @endguest
    <div class="layout-wrapper layout-content-navbar ">
        <div class="layout-container">
            @auth
                @include('layouts.menu.verticalMenu')
                <div class="min-height-300 bg-primary position-absolute w-100"></div>

                <main class="main-content border-radius-lg w-100">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/axios.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.toast.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @stack('js')
    @yield('js-script')
</body>

</html>
