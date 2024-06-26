<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $title }} : Healthy
                    Mind</li>
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">

            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <form role="form" method="post" id="logout-form">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link text-white font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">Log out</span>
                        </a>
                    </form>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2  px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" data-bs-toggle="dropdown"
                        aria-expanded="false" id="dropdownMenuButton2">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton2">
                        <li class="mb-2">
                            <div class="dropdown-item border-radius-md" href="javascript:;" data-class="bg-dark">
                                <div class=" d-flex">
                                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 "
                                        href="{{ auth()->user()->type == 'admin' ? route('admin.profile') : route('coach.profile') }}">
                                        <h6 class="mb-0 me-6">
                                            My Profile
                                        </h6>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="dropdown-item border-radius-md" href="javascript:;" data-class="bg-dark">
                                <div class=" d-flex">
                                    <h6 class="mb-0 me-6">Light / Dark</h6>
                                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                                        <input class="form-check-input mt-1 float-end me-auto" type="checkbox"
                                            id="dark-version" onclick="darkMode(this)">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton" id='notifications'>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="{{ asset('img/team-2.jpg') }}" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="{{ asset('img/small-logos/logo-spotify.svg') }}"
                                            class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New album</span> by Travis Scott
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            1 day
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                                    fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background"
                                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                opacity="0.593633743"></path>
                                                            <path class="color-background"
                                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Payment successfully completed
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            2 days
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // get user notifications
        let notes = `{!! json_encode($notifications, true) !!}`
        // generate_notifications(JSON.parse(notes))
        // check if is dark mode saved.
        let themeMode = localStorage.getItem('theme-mode');
        let item = document.getElementById('dark-version')
        if (themeMode != undefined && themeMode != null) {
            if (themeMode === 'dark') {
                item.click();
            }
        }
        $(".new_meal_noteification").on('click', function() {
            let id = $(this).attr('data-id');
            axios.post("{{ route('notification.read') }}", {
                id: id
            })
            window.location.href = "{{ route('requests.meal') }}"
        });
    })

    function generate_notifications(notes) {
        let html = ``;
        notes.forEach(note => html += generate_notification(note));
        //document.getElementById('notifications').innerHtml(html)
        $('#notifications').html(html)
    }

    function generate_notification(note) {
        if (note.type == "NewMealRequestNotification")
            return generate_new_meal_notification(note)
        let icon = get_icon_by_type(note.type);
        return ` <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        ${icon}
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>`;
    }

    function generate_new_meal_notification(note) {
        return ` <li class="mb-2">
                            <a class="new_meal_noteification dropdown-item border-radius-md" data-id="${note.id}" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="${note.data.coach.avatar}" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New Meal</span> from ${note.data.coach.fullname}
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            ${note.created_at}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>`;
    }

    function get_icon_by_type(type) {
        switch (type) {
            case 'new-coach':
                return `<img src="{{ asset('/img/team-2.jpg') }}" class="avatar avatar-sm  me-3 ">`;
            default:
                return '';
        }
    }
</script>
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
        wssPort: "{{ env('PUSHER_PORT') }}",
        forceTLS: {{ env('PUSHER_USE_TLS', 'false') ? 'true' : 'false' }},
        disableStats: true,
        authEndpoint: '/authenticate_websocket',
        auth: {
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        }
    });
    let privateChannel = window.Echo.private("admin-channel");
    privateChannel.listen("NewCoach", function(e) {
        console.log(e);
    })
</script>
<!-- End Navbar -->
