@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('custom-style')
    <style>
        .tw-65 {
            text-wrap: wrap;
            width: 65%;
            word-break: break-word;

        }
    </style>
@endsection
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Timelines'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">
                <div id="no-users-container"
                    style="flex-direction:column;display:{{ count($timelines) > 0 ? 'none' : 'flex' }}">
                    <h6 style="text-align: center;font-size: 2rem;color:white">No Timelines Found</h6>
                    <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px">

                </div>
                <div class="card mb-4 h-75"
                    style="overflow:auto;min-height:500px;height: 28rem;display:{{ count($timelines) == 0 ? 'none' : 'block' }}"
                    id="table-container">
                    <div class="card-header pb-0">
                        <h6>Timelines Table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 " style="overflow: auto">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>

                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Goal</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Plan</th>

                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Disease</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created At</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody id="timelines-table">
                                    @foreach ($timelines as $timeline)
                                        <tr id="item-{{ $timeline->id }}">
                                            <td class="ps-4">
                                                <a href="{{ route('coach.timeline.show', ['id' => $timeline->id]) }}">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $timeline->name }}</p>
                                                </a>

                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 tw-65">
                                                    {{ $timeline->goal_plan_disease?->goals_name() }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $timeline->goal_plan_disease?->plan->name }}</p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 tw-65">
                                                    {{ $timeline->goal_plan_disease?->disease_name() }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $timeline->created_at->diffForHumans() }}</span>
                                            </td>

                                            <td class="align-middle">
                                                <input type="hidden" id="selected-timeline">
                                                <a href="javascript:;"
                                                    class="text-secondary font-weight-bold text-xs remove-timeline"
                                                    data-toggle="tooltip" data-original-title="change"
                                                    data-id="{{ $timeline->id }}">
                                                    <i class="ni ni-fat-remove" style="font-size:2rem "></i>
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
        <div class="modal fade" tabindex="-1" role="dialog" id="timeline-removal-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove Timeline</h5>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to remove this timeline ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="onDeleteClicked()">Yes</button>
                        <button type="button" class="btn btn-secondary"
                            onclick="$('#timeline-removal-modal').modal('hide')">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection

@section('js-script')
    <script>
        $(".remove-timeline").on('click', function() {
            let id = $(this).attr('data-id');
            $("#selected-timeline").val(id);
            $("#timeline-removal-modal").modal('show')
        })

        function onDeleteClicked() {
            let id = $("#selected-timeline").val();
            axios({
                    method: 'DELETE',
                    url: "{{ route('coach.timelines.delete') }}",
                    data: {
                        id: id,
                    }
                })
                .then((response) => {
                    let data = response.data;
                    let msg = data.msg;
                    let code = data.code;
                    if (code == 200) {
                        $("#item-" + id).remove();
                        $("#timeline-removal-modal").modal('hide')
                        var rowCount = $('#timelines-table tr').length;
                        if (rowCount == 0) {
                            $("#no-users-container").css('display', 'flex');
                            $("#table-container").css('display', 'none');
                            $.toast({
                                text: msg,
                                loaderBg: '#fb6340',
                                bgColor: 'rgb(51 141 4)',
                                hideAfter: 5000,
                            })
                        }
                    } else {
                        $.toast({
                            text: msg,
                            loaderBg: '#fb6340',
                            bgColor: '#fb4040',
                            hideAfter: 5000,
                        })
                    }




                })
                .catch((error) => {
                    console.log(error)
                })
        }
    </script>
@endsection
