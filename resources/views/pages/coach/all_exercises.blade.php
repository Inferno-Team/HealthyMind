@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'All Exercises'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">
                <div id="no-users-container"
                    style="flex-direction:column;display:{{ count($exercises) > 0 ? 'none' : 'flex' }}">
                    <h6 style="text-align: center;font-size: 2rem;color:white">No Exercises Found</h6>
                    <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px">

                </div>
                <div class="card mb-4 h-75"
                    style="overflow:auto;min-height:500px;display:{{ count($exercises) == 0 ? 'none' : 'block' }}"
                    id="table-container">
                    <div class="card-header pb-0">
                        <h6>Exercises Table</h6>
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
                                            GIF</th>
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
                                <tbody id="exercises-table">
                                    @foreach ($exercises as $exercise)
                                        <tr id="item-{{ $exercise->id }}">
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $exercise->name }}</p>
                                            </td>
                                            <td>
                                                @if (!empty($exercise->gif_url))
                                                    <a url="{{ $exercise->gif_url }}">
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            SHOW GIF</p>
                                                    </a>
                                                @else
                                                    <p class="text-xs font-weight-bold mb-0"> NO GIF</p>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $exercise->type->name }}</p>
                                            </td>

                                            <td class="text-xs font-weight-bold mb-0">
                                                @switch($exercise->status)
                                                    @case('pending')
                                                        <span
                                                            class="badge badge-sm bg-gradient-warning">{{ $exercise->status }}</span>
                                                    @break

                                                    @case('approved')
                                                        <span
                                                            class="badge badge-sm bg-gradient-success">{{ $exercise->status }}</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge badge-sm bg-gradient-danger">{{ $exercise->status }}</span>
                                                @endswitch

                                            </td>
                                            <td class="">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $exercise->created_at->diffForHumans() }}</span>
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
