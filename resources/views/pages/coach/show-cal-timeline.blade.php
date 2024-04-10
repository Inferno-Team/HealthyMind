@php
    use Illuminate\Support\Str;
@endphp

@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Show Timeline'])
    <div class="col-md-12 timeline-container">
        
        <div class="container card p-3" id="app" style="z-index:1">
            <calendar :items="{{ $items }}" :timeline_id="{{ $timeline_id }}" :meals="{{ $meals }}" :exercises="{{ $exercises }}" />
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
