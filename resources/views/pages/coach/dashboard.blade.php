@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Counts</p>
                                    <h5 class="font-weight-bolder">
                                    </h5>
                                    <p class="mb-0">
                                        @if ($differenceEventPercentage > 0)
                                            <span
                                                class="text-success text-sm font-weight-bolder">+{{ $differenceEventPercentage }}%</span>
                                        @else
                                            <span
                                                class="text-danger text-sm font-weight-bolder">{{ $differenceEventPercentage }}%</span>
                                        @endif
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="bx bx-calendar-event bx-sm opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Trainee Count</p>
                                    <h5 class="font-weight-bolder">

                                    </h5>
                                    <p class="mb-0">
                                        @if ($differenceTraineesPercentage > 0)
                                            <span
                                                class="text-success text-sm font-weight-bolder">+{{ $differenceTraineesPercentage }}%</span>
                                        @else
                                            <span
                                                class="text-danger text-sm font-weight-bolder">{{ $differenceTraineesPercentage }}%</span>
                                        @endif
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">

                                    <i class="ni ni-user-run text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card" style="height:113px">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">All Meals</p>
                                    <h5 class="font-weight-bolder">

                                    </h5>
                                    <p class="mb-0">
                                        @if ($differenceMealsPercentage > 0)
                                            <span
                                                class="text-success text-sm font-weight-bolder">+{{ $differenceMealsPercentage }}%</span>
                                        @else
                                            <span
                                                class="text-danger text-sm font-weight-bolder">{{ $differenceMealsPercentage }}%</span>
                                        @endif
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i style="margin-top:-4px" class=" bx bxs-bowl-hot bx-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card" style="height:113px">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">All Exercises</p>
                                    <h5 class="font-weight-bolder">

                                    </h5>
                                    <p class="mb-0">
                                        @if ($differenceExercisesPercentage > 0)
                                            <span
                                                class="text-success text-sm font-weight-bolder">+{{ $differenceExercisesPercentage }}%</span>
                                        @else
                                            <span
                                                class="text-danger text-sm font-weight-bolder">{{ $differenceExercisesPercentage }}%</span>
                                        @endif
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i style="margin-top:-4px" class=" bx bx-cycling bx-sm" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card  h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Overview</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let channels = {!! $channels !!}
            channels.forEach((item) => {
                for (const channel in item) {
                    const name = item[channel];
                    switch (channel) {
                        case 'private':
                            let privateChannel = window.Echo.private(name);
                            privateChannel.listen('.NewChat', (e) => {
                                let newChannelName = e.newChannelName;
                                setTimeout(() => {
                                    window.Echo.private(newChannelName)
                                        .listen('.NewMessage', newMessageListener());
                                }, 1000);
                            });
                            break;
                    }
                }
            })
        });

        function newMessageListener(e) {
            console.log(e);
        }
    </script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");
        let borderWidth = 60;
        let maxBarThickness = 60;
        let fill = true;
        let minBarLength = 5;
        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                        label: "Meals",
                        borderColor: "#2dcecc",
                        borderWidth: borderWidth,
                        fill: fill,
                        data: JSON.parse('{!! json_encode($mealsTimelineValues) !!}'),
                        maxBarThickness: maxBarThickness,
                        minBarLength: minBarLength,
                    },
                    {
                        label: "Exercises",
                        borderColor: "rgb(153, 102, 255)",
                        borderWidth: borderWidth,
                        fill: fill,
                        data: JSON.parse('{!! json_encode($exercisesTimelineValues) !!}'),
                        maxBarThickness: maxBarThickness,
                        minBarLength: minBarLength,
                    },
                    {
                        label: "Events",
                        borderColor: "#fb6340",
                        borderWidth: borderWidth,
                        fill: fill,
                        data: JSON.parse('{!! json_encode($eventsTimelineValues) !!}'),
                        maxBarThickness: maxBarThickness,
                        minBarLength: minBarLength,
                    },
                    {
                        label: "Trainees",
                        borderColor: "#f5365c",
                        borderWidth: borderWidth,
                        fill: fill,
                        data: JSON.parse('{!! json_encode($traineesTimelineValues) !!}'),
                        maxBarThickness: maxBarThickness,
                        minBarLength: minBarLength,
                    },

                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: 'black',
                            padding: 20,
                            font: {
                                size: 15,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush
