@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('pages.admin.topnav', ['title' => 'Edit Goal'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form role="form" method="POST" action="{{ route('goals.edit.update') }}">
                    @csrf
                    <div class="card-header pb-0">
                        @if (session()->has('msg'))
                        <div class="alert alert-info" style="text-align:center">
                            {{ session('msg') }}
                        </div>
                        @endif
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Goal Information</p>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Edit</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="id" value="{{ $goal->id }}">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        name="name" placeholder="goal name" value="{{ $goal->name ?? old('name') }}"
                                        autocomplete="off">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection