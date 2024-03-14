@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Your Profile'])
    <div class="card shadow-lg mx-4 ">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <input type="file" id="avatarInput" style="display: none;">
                        <img src="{{ auth()->user()->avatar ?? '/img/team-1.jpg' }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm" id="avatarImage">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->first_name ?? 'Firstname' }} {{ auth()->user()->last_name ?? 'Lastname' }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ auth()->user()->type ?? 'Normal User' }}
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action="{{ route('user.self.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ old('username', auth()->user()->username) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">First name</label>
                                        <input class="form-control" type="text" name="first_name"
                                            value="{{ old('first_name', auth()->user()->first_name) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Last name</label>
                                        <input class="form-control" type="text" name="last_name"
                                            value="{{ old('last_name', auth()->user()->last_name) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <script>
        document.getElementById('avatarImage').addEventListener('click', function() {
            // Trigger click on the hidden file input
            document.getElementById('avatarInput').click();
        });
        // Listen for changes on the file input
        document.getElementById('avatarInput').addEventListener('change', function() {
            // Get the selected file
            const file = this.files[0];

            // Create FormData object and append the file to it
            const formData = new FormData();
            formData.append('avatar', file);

            // Send the FormData to the backend API using fetch or XMLHttpRequest
            fetch("{{ route('user.self.update.avatar') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // If you're using CSRF protection
                    },
                })
                .then(response => {
                    // Handle the response from the backend
                    if (response.ok) {
                        // If successful, update the image source to the newly uploaded image
                        document.getElementById('avatarImage').src = URL.createObjectURL(file);
                    } else {
                        // Handle error
                        console.error('Error uploading avatar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
