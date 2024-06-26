@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('pages.admin.topnav', ['title' => 'Create User'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action="{{ route('new.user.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">New Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email address <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email"
                                            name="email" placeholder="Email" value="{{ old('email') }}"
                                            autocomplete="off">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- First name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-control-label">First name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control @error('first_name') is-invalid @enderror" type="text"
                                            name="first_name" autocomplete="off" placeholder="First Name"
                                            value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Last name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-control-label">Last name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control @error('last_name') is-invalid @enderror" type="text"
                                            name="last_name" placeholder="Last Name" autocomplete="off"
                                            value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">Phone Number (Optional)</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                            name="phone" placeholder="Phone Number" autocomplete="off"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                                            name="password" placeholder="Password" autocomplete="off">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Coach -->
                                <div class="col-md-6">
                                    <div class="form-group d-flex" style="margin-top:2rem">
                                        <h6 class="mb-0">Coach (Optional)</h6>
                                        <div class="form-check form-switch ps-0 ms-6 my-auto">
                                            <input class="form-check-input mt-1 float-end me-auto" name="coach"
                                                type="checkbox" {{ old('coach') ? 'checked' : '' }}>
                                        </div>
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
@endsection
