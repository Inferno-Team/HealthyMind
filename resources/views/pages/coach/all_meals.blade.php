@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'All Meals'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">
                <div id="no-users-container" style="flex-direction:column;display:{{ count($meals) > 0 ? 'none' : 'flex' }}">
                    <h6 style="text-align: center;font-size: 2rem;color:white">No Meals Found</h6>
                    <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px">

                </div>
                <div class="card mb-4 h-75"
                    style="overflow:auto;min-height:500px;display:{{ count($meals) == 0 ? 'none' : 'block' }}"
                    id="table-container">
                    <div class="card-header pb-0">
                        <h6>Meals Table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>

                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            qty</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Type</th>

                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Created At</th>
                                    </tr>
                                </thead>
                                <tbody id="meals-table">
                                    @foreach ($meals as $meal)
                                        <tr id="item-{{ $meal->id }}">
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $meal->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $meal->qty }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $meal->type->name }}</p>
                                            </td>

                                            <td class="text-xs font-weight-bold mb-0">
                                                @switch($meal->status)
                                                    @case('pending')
                                                        <span class="badge badge-sm bg-gradient-warning">{{ $meal->status }}</span>
                                                    @break

                                                    @case('approved')
                                                        <span class="badge badge-sm bg-gradient-success">{{ $meal->status }}</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-sm bg-gradient-error">{{ $meal->status }}</span>
                                                @endswitch

                                            </td>
                                            <td class="">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $meal->created_at->diffForHumans() }}</span>
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
@endsection
