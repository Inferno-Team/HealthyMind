@php
    use Illuminate\Support\Str;
@endphp

@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Show Timeline'])
    <div class="col-md-12 timeline-container">
        <div id="no-users-container"
            style="flex-direction:column;z-index:1;display:{{ count($items) > 0 ? 'none' : 'flex' }}">
            <h6 style="text-align: center;font-size: 2rem;color:white">No Timeline Items Found</h6>
            <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px;cursor:pointer"
                onclick="addNewItem()">
        </div>
        <div class="container card p-3" id="app" style="z-index:1">
            <calendar :items="{{ $items }}"/>
        </div>
    </div>
@endsection
@push('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        function addNewItem() {
            let url = "{{ route('coach.timelines.items.new', ['id' => $timeline_id]) }}"
            window.location.href = url;
        }
    </script>
@endpush
