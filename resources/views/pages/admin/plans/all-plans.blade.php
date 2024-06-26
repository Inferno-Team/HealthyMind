@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('pages.admin.topnav', ['title' => 'Plans Table'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 h-100" style="overflow:auto">
                <div class="card-header pb-0">
                    <h6>Plans table</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2 ">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">
                                        Plan Id</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Plan Name</th>
                                    <th class="text-secondary opacity-7"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 ps-4">
                                            {{ $plan->id }}</p>

                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $plan->name }}</p>
                                    </td>
                                    <td>
                                        <input type="hidden" id="selected-request">
                                        <a href="javascript:;"
                                            class="text-secondary font-weight-bold text-xs remove-item"
                                            data-toggle="tooltip" data-original-title="delete"
                                            data-id="{{ $plan->id }}">
                                            <i class='bx bx-md bxs-folder-minus'></i>
                                        </a>
                                        <div style="width: 30px;display: inline-block;"></div>
                                        <a href="{{ route('plans.edit.view',['id' => $plan->id]) }}"
                                            class="text-secondary font-weight-bold text-xs " data-toggle="tooltip"
                                            data-original-title="change" data-id="{{ $plan->id }}">
                                            <i class='bx bx-md bx-edit-alt'></i>
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
    @include('layouts.footers.auth.footer')
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="request-changer-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Plan</h5>
            </div>
            <div class="modal-body">
                <p>Do you want to delete this plan ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="onAcceptClicked()">Yes</button>
                <button type="button" class="btn btn-secondary"
                    onclick="$('#request-changer-modal').modal('hide')">No</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js-script')
<script>
    $(".remove-item").on('click', function() {
            let id = $(this).attr('data-id');
            $("#selected-request").val(id);
            $("#request-changer-modal").modal('show')
        })

        function onAcceptClicked() {
            let id = $("#selected-request").val();
            axios({
                    method: 'POST',
                    url: "{{ route('plans.delete') }}",
                    data: {
                        id: id,
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

@endsection