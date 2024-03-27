@php
    use Illuminate\Support\Str;
@endphp

@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Show Timeline'])
    <div class="col-md-12 timeline-container">
        <i class="ni ni-fat-add" style="z-index:1;font-size:2rem;cursor:pointer" onclick="addNewItem()"></i>
        <div id="no-users-container"
            style="flex-direction:column;z-index:1;display:{{ count($items) > 0 ? 'none' : 'flex' }}">
            <h6 style="text-align: center;font-size: 2rem;color:white">No Timeline Items Found</h6>
            <img src="{{ asset('img/no-users-found.png') }}" style="margin:auto;width:500px;cursor:pointer"
                onclick="addNewItem()">

        </div>

        <ul class="timeline" style="display:{{ count($items) == 0 ? 'none' : 'grid' }}">
            @foreach ($items as $item)
                @switch($item->item->status)
                    @case('approved')
                        <li style="--accent-color:#446c41">
                        @break

                        @case('pending')
                        <li style="--accent-color:#FBCA3E">
                        @break

                        @case('declined')
                        <li style="--accent-color:#E24A68">
                        @break

                        @default
                        <li style="--accent-color:#1B5F8C">
                    @endswitch
                    @if (Str::endsWith($item->item_type, 'Meal'))
                        <div class="date">Day #{{ $item->day->name }}</div>
                        <div class="title">Meal : {{ $item->item->name }}</div>
                        <div class="descr">Meal Type : {{ $item->item->type->name }}</div>
                        <div class="descr">Quantity : {{ $item->item->qty }}</div>
                    @elseif (Str::endsWith($item->item_type, 'Exercise'))
                        <div class="date">Day #{{ $item->day->name }}</div>
                        <div class="title">Exercise : {{ $item->item->name }}</div>
                        <div class="descr">Exercise Type : {{ $item->item->type->name }}</div>
                    @endif
                </li>
            @endforeach
        </ul>

    </div>
@endsection
@push('js')
    <script>
        function addNewItem() {
            let url = "{{ route('coach.timelines.items.new', ['id' => $timeline_id]) }}"
            window.location.href = url;
        }
    </script>
@endpush
