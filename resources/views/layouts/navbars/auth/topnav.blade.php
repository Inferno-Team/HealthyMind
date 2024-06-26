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
        notes = JSON.parse(notes)
        if (notes.length == 0) {
            $("#notifications").append(no_notification())
        } else {
            generate_notifications(notes)
        }
        let coachChannelName = 'Coach.' + "{!! auth()->id() !!}";
        let coachChannel = window.Echo.private(coachChannelName);
        coachChannel.listen('.ExerciseNotification', function(e) {
            let type = e.type;
            let segments = type.split("\\");
            type = segments[segments.length - 1];
            e.type = type;
            if (notes == 0) $('#notifications').html("");
            let html = generate_notification({
                data: e,
                id: e.id,
                type: type,
                created_at: e.created_at
            }) + $('#notifications').html();
            $('#notifications').html(html)
        })
        coachChannel.listen('.MealNotification', function(e) {
            let type = e.type;
            let segments = type.split("\\");
            type = segments[segments.length - 1];
            e.type = type;
            if (notes == 0) $('#notifications').html("");
            let html = generate_notification({
                data: e,
                id: e.id,
                type: type,
                created_at: e.created_at
            }) + $('#notifications').html();
            $('#notifications').html(html)
        })
        coachChannel.listen('.TraineeBecomeProNotification', function(e) {
            let type = e.type;
            let segments = type.split("\\");
            type = segments[segments.length - 1];
            e.type = type;
            if (notes == 0) $('#notifications').html("");
            let html = generate_notification({
                data: e,
                id: e.id,
                type: type,
                created_at: e.created_at
            }) + $('#notifications').html();
            $('#notifications').html(html)
        })
        coachChannel.listen('.NewTraineeNotification', function(e) {
            let type = e.type;
            let segments = type.split("\\");
            type = segments[segments.length - 1];
            e.type = type;
            if (notes == 0) $('#notifications').html("");
            let html = generate_notification({
                data: e,
                id: e.id,
                type: type,
                created_at: e.created_at
            }) + $('#notifications').html();
            $('#notifications').html(html)
        })

        // check if is dark mode saved.
        let themeMode = localStorage.getItem('theme-mode');
        let item = document.getElementById('dark-version')
        if (themeMode != undefined && themeMode != null) {
            if (themeMode === 'dark') {
                item.click();
            }
        }
        $(document).on('click', ".meal_status_change_notification", async function() {
            let id = $(this).data('id'); // Use .data() to retrieve data-id

            try {
                // Send a POST request to mark the notification as read
                await axios.post("{{ route('notification.read') }}", {
                    id: id
                });

                // Remove the clicked element from the DOM
                $(this).remove();

                // Redirect to the specified URL after removing the element
                window.location.href = "{{ route('coach.meals.all') }}"
            } catch (error) {
                // Handle errors here, if necessary
                console.error('Error:', error);
            }
        });
        // Attach event handler to a parent element that exists in the DOM
        $(document).on('click', '.exercise_status_change_notification', async function() {
            let id = $(this).data('id'); // Use .data() to retrieve data-id

            try {
                // Send a POST request to mark the notification as read
                await axios.post("{{ route('notification.read') }}", {
                    id: id
                });

                // Remove the clicked element from the DOM
                $(this).remove();

                // Redirect to the specified URL after removing the element
                window.location.href = "{{ route('coach.exercises.all') }}";
            } catch (error) {
                // Handle errors here, if necessary
                console.error('Error:', error);
            }
        });

        $(document).on('click', '.new_trainee_status_change_notification', async function() {
            let id = $(this).data('id'); // Use .data() to retrieve data-id

            try {
                // Send a POST request to mark the notification as read
                await axios.post("{{ route('notification.read') }}", {
                    id: id
                });

                // Remove the clicked element from the DOM
                $(this).remove();

                // Redirect to the specified URL after removing the element
                window.location.href = "{{ route('coach.trainees') }}";
            } catch (error) {
                // Handle errors here, if necessary
                console.error('Error:', error);
            }
        });


    })

    function generate_notifications(notes) {
        let html = ``;
        notes.forEach(note => html += generate_notification(note));
        //document.getElementById('notifications').innerHtml(html)
        $('#notifications').html(html)
    }

    function generate_notification(note) {
        if (note.type == "MealStatusChangeNotification")
            return generate_meal_status_change_notification(note)
        else if (note.type == "ExerciseStatusChangeNotification")
            return generate_exercise_status_change_notification(note)
        else if (note.type == "NewTraineeNotification")
            return generate_new_trainee_status_change_notification(note)
        else if (note.type == "TraineeBecomeProNotification")
            return generate_trainee_become_pro_notification(note)

    }

    function generate_meal_status_change_notification(note) {
        return ` <li class="mb-2">
                            <a class="meal_status_change_notification dropdown-item border-radius-md" data-id="${note.id}" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="bx bxs-bowl-hot bx-sm " > </i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center ms-3">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">${note.data.name}</span> status changed to
                                                ${note.data.status}
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

    function generate_exercise_status_change_notification(note) {
        console.log(note);
        return ` <li class="mb-2">
                            <a class="exercise_status_change_notification dropdown-item border-radius-md" data-id="${note.id}" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="bx bx-cycling bx-sm" > </i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center ms-3">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">${note.data.exercise.name}</span> status changed to
                                                ${note.data.status}
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


    function generate_new_trainee_status_change_notification(note) {
        console.log(note);
        return ` <li class="mb-2">
                            <a class="new_trainee_status_change_notification dropdown-item border-radius-md" data-id="${note.id}" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="ni ni-user-run text-lg" > </i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center ms-3">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">${note.data.trainee.fullname}</span> Joined To ${note.data.timeline.name} Timeline
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


    function generate_trainee_become_pro_notification(note) {
        return ` <li class="mb-2">
                            <a class="new_trainee_status_change_notification dropdown-item border-radius-md" data-id="${note.id}" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="bx bxs-crown bx-sm" > </i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center ms-3">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">${note.data.trainee.fullname}</span> Joined To ${note.data.timeline.name} Timeline
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

    function no_notification() {
        return `
        <div class="mx-3">No Notifications</div>
        `;
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


<!-- End Navbar -->
