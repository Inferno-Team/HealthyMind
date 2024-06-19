@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('custom-style')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        .dropzone {
            position: relative;
            min-height: 150px;
            /* Set a minimum height */
            border: 1px solid #d2d6da;
            background: white;
            padding: 20px;
            border-radius: 0.5rem;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(0, 0, 0, 0.5);
            /* Adjust opacity as needed */
            font-size: 24px;
            pointer-events: none;
        }
        .watermark img{
            opacity:0.75
        }
    </style>
@endsection
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'New Exercise'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">

                <div class="card mb-4 h-75" style="overflow:auto;min-height:500px;" id="table-container">
                    <div class="card-header pb-0">
                        <h6>New Exercise</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="row p-3 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-name" class="form-control-label">Exercise Name</label>
                                    <input class="form-control" type="text" name="exercise-name" id="exercise-name"
                                        placeholder="Exercise Name ..." autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meal-qty" class="form-control-label">exercise duration</label>
                                    <input class="form-control" type="number" min="0" name="exercise-duration"
                                        id="exercise-duration" placeholder="Duration ... " autocomplete="off"
                                        oninput="this.value = this.value.replace(/[^\d]/, ''); if(parseInt(this.value) < 0) this.value = '0';">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meal-qty_type-select" class="form-control-label">Exercise Type</label>
                                    <select class="form-control" id="exercise-type-select">
                                        <option id="none"> Please select type</option>
                                        @foreach ($types as $type)
                                            <option id="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="targeted-muscle-select" class="form-control-label">Targeted Muscle</label>
                                    <select class="form-control" id="targeted-muscle-select">
                                        <option id="none"> Please select muscle</option>
                                        @foreach ($muscles as $muscle)
                                            <option id="{{ $muscle }}">{{ $muscle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="equipment-select" class="form-control-label">Equipments</label>
                                    <select class="form-control" id="equipment-select">
                                        <option id="none"> Please select equipment</option>
                                        @foreach ($equipments as $equipment)
                                            <option id="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="equipment-select" class="form-control-label">Description</label>
                                    <textarea class="form-control" id="exercise-description" rows="5"></textarea>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="equipment-select" class="form-control-label">Media File</label>
                                    <form action="/target" class="dropzone" id="my-element">
                                        <div class="watermark">
                                        <img src="{{ asset('img/dropzone-watermark.png') }}" width="110px">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary" style="width:100%"
                                        onclick="onCreateClicked()">Create</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        function onCreateClicked() {
            let formData = new FormData();
            if (myDropzone) {
                var uploadedFiles = myDropzone.getAcceptedFiles();
                if (uploadedFiles.length > 0) {
                    let media = uploadedFiles[0];
                    formData.append('media', media);
                }
            }
            let exerciseName = $("#exercise-name").val();
            let exerciseDescription = $("#exercise-description").val();
            let exerciseDuration = $("#exercise-duration").val();
            let exerciseType = $("#exercise-type-select option:selected").attr('id');
            let targetedMuscle = $("#targeted-muscle-select option:selected").attr('id');
            let equipmentSelect = $("#equipment-select option:selected").attr('id');
            formData.append("exerciseName", exerciseName);
            formData.append("exerciseDuration", exerciseDuration);
            formData.append("exerciseType", exerciseType);
            formData.append("targetedMuscle", targetedMuscle);
            formData.append("equipment", equipmentSelect);
            formData.append("exerciseDescription", exerciseDescription);
            axios.post("{{ route('coach.exercises.new') }}", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                })
                .then((response) => {
                    console.log(response.data)
                    let data = response.data;
                    let code = data.code;
                    let msg = data.msg;
                    $.toast({
                        text: msg,
                        loaderBg: '#fb6340',
                        bgColor: code == 200 ? 'rgb(51 141 4)' : '#fb4040',
                        hideAfter: 5000,
                    })
                    //window.location.reload();
                }).catch(console.error);
        }
    </script>
    <script>
        var myDropzone;
        Dropzone.options.myElement = {
            autoProcessQueue: false,
            addRemoveLinks: true,
            maxFilesize: 1024 * 1024, // on byte => 1 MB
//            acceptedFiles: "image/*",
            maxFiles:1,
            dictDefaultMessage: "",
            init: function() {
                myDropzone = this;
            },

        };
    </script>
@endpush
