@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('pages.admin.topnav', ['title' => "$user->fullname Profile"])
    <div class="card shadow-lg mx-4 ">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ $user->avatar ?? asset('/img/team-1.jpg') }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm" id="avatarImage">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $user->first_name ?? 'Firstname' }} {{ $user->last_name ?? 'Lastname' }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $user->isPro ? 'Premium User' : 'Normal User' }}
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Profile</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">User Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Username</label>
                                    <input class="form-control" type="text" name="username"
                                        value="{{ old('username', $user->username) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email address</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ old('email', $user->email) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">First name</label>
                                    <input class="form-control" type="text" name="first_name"
                                        value="{{ old('first_name', $user->first_name) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Last name</label>
                                    <input class="form-control" type="text" name="last_name"
                                        value="{{ old('last_name', $user->last_name) }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    @if ($user->enabled_timeline() == null)
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">No Selected Timeline</p>
                            </div>
                        </div>
                    @else
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Selected Timeline</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mt-3 text-sm">{{ $user->enabled_timeline()?->timeline->name }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Plan</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ $user->enabled_timeline()?->timeline->goal_plan_disease->plan->name }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Goals</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ $user->enabled_timeline()?->timeline->goal_plan_disease->goals_name() }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Diseases</label>
                                        <input class="form-control" type="text" name="first_name"
                                            value="{{ $user->enabled_timeline()?->timeline->goal_plan_disease->disease_name() }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Descriptipn</label>
                                        <textarea class="form-control" disabled rows="5">{{ $user->enabled_timeline()?->timeline->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
