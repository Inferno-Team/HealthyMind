@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('pages.admin.topnav', ['title' => 'Meal Requests'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">
                <div id="no-users-container"
                    style="flex-direction:column;display:{{ count($requests) > 0 ? 'none' : 'flex' }}">
                    <h6 style="text-align: center;font-size: 2rem;color:white">No Request Found</h6>
                    <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px">

                </div>
                <div class="card mb-4 h-75"
                    style="overflow:auto;min-height:500px;display:{{ count($requests) == 0 ? 'none' : 'block' }};width:65rem"
                    id="table-container">
                    <div class="card-header pb-0">
                        <h6>Meals Request Table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Meal Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Meal Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            quantity</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            quantity Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            ingredients</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            description</th>

                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Coach Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Request At</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="requests-table">
                                    @foreach ($requests as $request)
                                        <tr id="item-{{ $request->id }}">
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 ps-4">{{ $request->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->type->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $request->qty }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $request->qty_type->title }}</p>
                                            </td>
                                            <td>
                                                @if (str_word_count($request->ingredients) > 5)
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ implode(' ', array_slice(explode(' ', $request->ingredients), 0, 5)) }}
                                                        <br><b style="cursor:pointer"
                                                            data-text="{{ $request->ingredients }}"
                                                            class="show-more-ingredients"
                                                            data-title="{{ $request->name }}"> Show More</b>
                                                    </p>
                                                @else
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $request->ingredients }}
                                                    </p>
                                                @endif

                                            </td>
                                            <td>
                                                @if (str_word_count($request->description) > 5)
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ implode(' ', array_slice(explode(' ', $request->description), 0, 5)) }}
                                                        <br><b style="cursor:pointer"
                                                            data-text="{{ $request->description }}"
                                                            class="show-more-description"
                                                            data-title="{{ $request->name }}"> Show More</b>
                                                    </p>
                                                @else
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $request->description }}
                                                    </p>
                                                @endif

                                            </td>


                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->coach?->fullname }}
                                                </p>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $request->created_at->diffForHumans() }}</span>
                                            </td>

                                            <td>
                                                <input type="hidden" id="selected-request">
                                                <a href="javascript:;"
                                                    class="text-secondary font-weight-bold text-xs change-request-status"
                                                    data-toggle="tooltip" data-original-title="change"
                                                    data-id="{{ $request->id }}">
                                                    change
                                                </a>
                                            </td>
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
                        <p>Do you want to accept this meal request ?</p>
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
        <div class="modal fade" tabindex="-1" role="dialog" id="show-text-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="show-text-modal-title"></h5>
                    </div>
                    <div class="modal-body">
                        <p id="show-text-modal-body"></p>
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.footers.auth.footer')
    </div>
@endsection

@section('js-script')
    <script>
        $(".show-more-ingredients").on('click', function() {
            let ingredients = $(this).attr('data-text');
            let title = $(this).attr('data-title');

            $('#show-text-modal-title').text(`${title} ingredients`)
            $('#show-text-modal-body').text(ingredients)
            $("#show-text-modal").modal("show")

        });

        $(".show-more-description").on('click', function() {
            let description = $(this).attr('data-text');
            let title = $(this).attr('data-title');

            $('#show-text-modal-title').text(`${title} description`)
            $('#show-text-modal-body').text(description)
            $("#show-text-modal").modal("show")

        });


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
                    url: "{{ route('admin.meal.request.change-status') }}",
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
