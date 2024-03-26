@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Join Requests'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">
                <div id="no-users-container"
                    style="flex-direction:column;display:{{ count($users) > 0 ? 'none' : 'flex' }}">
                    <h6 style="text-align: center;font-size: 2rem;color:white">No Request Found</h6>
                    <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px">

                </div>
                <div class="card mb-4 h-75"
                    style="overflow:auto;min-height:500px;display:{{ count($users) == 0 ? 'none' : 'block' }}"
                    id="table-container">
                    <div class="card-header pb-0">
                        <h6>Join Request Table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Username</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Phone</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Request At</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody id="requests-table">
                                    @foreach ($users as $user)
                                        <tr id="item-{{ $user->id }}">
                                            <td>
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
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->phone }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->created_at->diffForHumans() }}</span>
                                            </td>
                                            @switch($user->status)
                                                @case('waiting')
                                                    <td class="align-middle">
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
        <div class="modal fade" tabindex="-1" role="dialog" id="request-changer-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Request Status</h5>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to accept this coach join request ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="onAcceptClicked()">Accept</button>
                        <button type="button" class="btn btn-danger" onclick="onDeclinedClicked()">Decline</button>
                        <button type="button" class="btn btn-secondary"
                            onclick="$('#request-changer-modal').modal('hide')">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
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
            axios({
                    method: 'POST',
                    url: "{{ route('admin.coach.request.change-status') }}",
                    data: {
                        id: id,
                        status: status
                    }
                })
                .then((response) => {
                    let data = response.data;
                    let msg = data.msg;
                    let newStatus = data.newStatus;
                    $.toast({
                        text: msg,
                        loaderBg: '#fb6340',
                        bgColor: status == 'approved' ? 'rgb(51 141 4)':'#fb4040',
                        hideAfter: 5000,
                    })

                    $("#item-" + id).remove();
                    $("#request-changer-modal").modal('hide')
                    var rowCount = $('#requests-table tr').length;
                    if (rowCount == 0) {
                        $("#no-users-container").css('display', 'flex');
                        $("#table-container").css('display', 'none');
                    }

                })
                .catch((error) => {
                    console.log(error)
                })
        }
    </script>
@endsection
