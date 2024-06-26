@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('pages.admin.topnav', ['title' => 'Users Table'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 h-75" style="overflow:auto">
                    <div class="card-header pb-0">
                        <h6>Users table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            UserName</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Type</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created At</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Approved / Declined At</th>

                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ $user->type == 'coach' ? route('show.coach.profile', ['id' => $user->id]) : route('show.user.profile', ['id' => $user->id]) }} ">
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="{{ $user->avatar ?? asset('/img/team-2.jpg') }}"
                                                                class="avatar avatar-sm me-3" alt="user1">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $user->first_name }}
                                                                {{ $user->last_name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                <b>@</b>{{ $user->username }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $user->isPro ? 'Premium' : $user->type }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($user->isPro)
                                                    <span class="badge badge-sm user-pro-status ">
                                                        <i class='bx bxs-crown'></i> Pro
                                                    </span>
                                                @else
                                                    @switch($user->status)
                                                        @case('waiting')
                                                            <span class="badge badge-sm bg-gradient-secondary">
                                                            @break

                                                            @case('approved')
                                                                <span class="badge badge-sm bg-gradient-success">
                                                                @break

                                                                @case('declined')
                                                                    <span class="badge badge-sm bg-gradient-danger">
                                                                    @break

                                                                    @default
                                                                @endswitch
                                                                {{ $user->status }}</span>
                                                @endif

                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->status !== 'waiting' ? $user->updated_at->diffForHumans() : '-' }}</span>
                                            </td>

                                            @switch($user->status)
                                                @case('waiting')
                                                    <td>
                                                        <input type="hidden" id="selected-request">
                                                        <a href="javascript:;"
                                                            class="text-secondary font-weight-bold text-xs change-request-status"
                                                            data-toggle="tooltip" data-original-title="change"
                                                            data-id="{{ $user->id }}">
                                                            change
                                                        </a>
                                                    </td>
                                                @break

                                                @default
                                            @endswitch
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="request-changer-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change User Status</h5>
                </div>
                <div class="modal-body">
                    <p>Do you want to change this user status ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="onAcceptClicked()">Approve</button>
                    <button type="button" class="btn btn-danger" onclick="onDeclinedClicked()">Decline</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="$('#request-changer-modal').modal('hide')">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-script')
    <script>
        $(".change-request-status").on('click', function() {
            let id = $(this).attr('data-id');
            $("#selected-request").val(id);
            $("#request-changer-modal").modal('show')
        })

        function onAcceptClicked() {
            let id = $("#selected-request").val();
            sendChangeStatus(id, 'approved')
        }

        function onDeclinedClicked() {
            let id = $("#selected-request").val();
            sendChangeStatus(id, 'declined')
        }

        function sendChangeStatus(id, status) {
            // ajax , axios , fetch
            axios({
                    method: 'POST',
                    url: "{{ route('admin.user.status.change') }}",
                    data: {
                        id: id,
                        status: status
                    }
                })
                .then((response) => {
                    $("#selected-request").val("");
                    let data = response.data;
                    let msg = data.msg;
                    let code = data.code;
                    if (code == 404) {
                        $.toast({
                            text: msg,
                            loaderBg: '#fb6340',
                            bgColor: '#fb4040',
                            hideAfter: 5000,
                        })
                        return;
                    }
                    let newStatus = data.newStatus;
                    $.toast({
                        text: msg,
                        loaderBg: '#fb6340',
                        bgColor: status == 'approved' ? 'rgb(51 141 4)' : '#fb4040',
                        hideAfter: 5000,
                    })

                    $("#request-changer-modal").modal('hide')
                    window.location.reload();
                })
                .catch((error) => {
                    console.log(error)
                })
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".show-media").on('click', function() {
                let url = $(this).attr('data-url');
                $('#show-media-modal-body').empty()
                let image = $("<img>");
                image.attr('src', url);
                image.attr('style', "margin:auto");
                image.appendTo("#show-media-modal-body")
                $("#show-media-modal").modal('show')
            });

        })
    </script>
@endsection
