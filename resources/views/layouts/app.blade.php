<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/apple-icon.png">
    <link rel="icon" type="image/png" href="./img/favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/fonts/boxicons.css')}}" />

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
            @include('layouts.menu.verticalMenu')
            @auth
                @if (in_array(request()->route()->getName(), [
                        'sign-in-static',
                        'sign-up-static',
                        'login',
                        'register',
                        'recover-password',
                        'rtl',
                        'virtual-reality',
                    ]))
                    @yield('content')
                @else
                    @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                        <div class="min-height-300 bg-primary position-absolute w-100"></div>
                    @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                        <div class="position-absolute w-100 min-height-300 top-0"
                            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                            <span class="mask bg-primary opacity-6"></span>
                        </div>
                    @endif
                    <main class="main-content border-radius-lg">
                        @yield('content')
                    </main>
                @endif
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
    <script src="assets/js/argon-dashboard.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Your code to run after the document is ready

            // Create a new script element
            var script1 = document.createElement('script');
            var script2 = document.createElement('script');

            // Set the source of the script
            script1.src = "{{ asset('assets/js/main.js') }}";
            script2.src = "{{ asset('assets/js/custom.js') }}";

            // Append the script to the document body
            document.body.appendChild(script1);
            document.body.appendChild(script2);
        });
    </script>
    @stack('js');
</body>

</html>
