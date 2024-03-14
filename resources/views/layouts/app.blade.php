<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/apple-icon.png">
    <link rel="icon" type="image/png" href="./img/favicon.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>
        Healthy Mind
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="./assets/css/core.css" rel="stylesheet" />
    <link href="./assets/css/theme-default.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/argon-dashboard.css" rel="stylesheet" />
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

                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @stack('js');
</body>

</html>
