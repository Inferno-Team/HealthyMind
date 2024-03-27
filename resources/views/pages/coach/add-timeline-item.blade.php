@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Timeline Item'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action="{{ route('coach.timelines.item.new.store') }}"
                        id="store-new-timeline-item-form">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">New Timeline Item</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Add</button>
                            </div>
                        </div>
                        <input type="hidden" id="selected-type">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="days" class="form-control-label ">Day</label>
                                        <select class="form-control" id="days">
                                            <option data-id="0">Please Select a Day</option>
                                            @foreach ($days as $day)
                                                <option data-id="{{ $day->id }}">{{ $day->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="types" class="form-control-label ">Type</label>
                                        <select class="form-control" id="types">
                                            <option data-id="0">Please Select a Type</option>
                                            <option data-id="meal">Meal</option>
                                            <option data-id="exercise">Exercise</option>

                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display:none" id="meal-container">
                                    <div class="form-group">
                                        <label for="meals" class="form-control-label ">Meal</label>
                                        <select class="form-control" id="meals">
                                            <option data-id="0">Please Select a Meal</option>
                                            <option data-id="create">Create Meal</option>
                                            @foreach ($meals as $meal)
                                                <option data-id="{{ $meal->id }}">{{ $meal->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display:none" id="exercise-container">
                                    <div class="form-group">
                                        <label for="exercises" class="form-control-label">Exercise</label>
                                        <select class="form-control" id="exercises">
                                            <option data-id="0">Please Select a Exercise</option>
                                            <option data-id="create">Create Exercise</option>
                                            @foreach ($exercises as $exercise)
                                                <option data-id="{{ $exercise->id }}">{{ $exercise->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @include('layouts.footers.auth.footer')
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="meal-exercise-create-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Item</h5>
                </div>
                <div class="modal-body">
                    <p>your item will be created and wait for admin approval<br>
                        you can use it in creation of timeline item<br>
                        but be aware that this item will not be shown to users<br>
                        if the admin doesn't approve it.</p>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="item-name" class="form-control-label">Item Name</label>
                                <input class="form-control" type="text" name="timeline-name" id="item-name"
                                    placeholder="Item Name ..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-5" style="display:none" id="item-qty-container">
                            <div class="form-group">
                                <label for="item-name" class="form-control-label">Item qty</label>
                                <input class="form-control" type="text" name="timeline-name" id="item-qty"
                                    placeholder="Item qty ..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="item-name" class="form-control-label">Item Type</label>
                                <select class="form-control" id="item-type-select">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="onCreateClicked()">Create</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="$('#meal-exercise-create-modal').modal('hide')">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#store-new-timeline-item-form').submit(function(event) {
                event.preventDefault();
                let selectedType = $('#types option:selected').attr('data-id');
                let selectedDay = $("#days option:selected").attr('data-id');
                let isSelectedTrue = true;
                let selectedItem = "0";
                if (selectedType == '0') {
                    $('#types').addClass('is-invalid');
                    return false;
                }
                if (selectedType == 'meal') {
                    selectedItem = $('#meals option:selected').attr('data-id');

                } else if (selectedType == 'exercise') {
                    selectedItem = $('#exercises option:selected').attr('data-id');
                }

                if (selectedDay == '0') {
                    $(`#days`).addClass('is-invalid');
                    isSelectedTrue = false;
                }
                if (selectedItem == '0') {
                    $(`#${selectedType}s`).addClass('is-invalid');
                    isSelectedTrue = false;
                }

                if (!isSelectedTrue) {
                    return false;
                }

                $.ajax({
                    url: $(this).attr('action'), // URL to submit the form data
                    type: $(this).attr('method'), // HTTP method (POST, GET, PUT, DELETE, etc.)
                    data: {
                        timeline_id: {{ $timeline_id }},
                        item_id: selectedItem,
                        item_type: selectedType,
                        day: selectedDay
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle the success response
                        console.log('Form submitted successfully');
                        $('#store-new-timeline-item-form').get(0).reset();
                        let data = response;
                        let code = data.code;
                        if (code == 200) {
                            alert(data.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Form submission failed:', error);
                    }
                });
            });
            $('#days').change(function() {
                // Remove error class if a value is selected
                if ($(this).attr('data-id') != '0') {
                    $(this).removeClass('is-invalid');
                }
            });
            $('#types').change(function() {
                // Remove error class if a value is selected
                if ($(this).attr('data-id') != '0') {
                    $(this).removeClass('is-invalid');
                }

            });
            $('#meals').change(function() {
                let selected = $("#meals option:selected");
                console.log($(selected).attr('data-id'))
                if ($(selected).attr('data-id') != '0') {
                    $(this).removeClass('is-invalid');
                }
                if ($(selected).attr('data-id') == 'create') {
                    let types = JSON.parse(`{!! json_encode($meal_types) !!}`)
                    $("#item-qty-container").css('display', 'block')
                    let html = generate_item_options(types);
                    $("#item-type-select").empty()
                    $("#item-type-select").append(html)
                    $("#meal-exercise-create-modal").modal('show')
                }
            });
            $('#exercises').change(function() {
                let selected = $("#exercises option:selected");
                console.log(selected);
                if ($(selected).attr('data-id') != '0') {
                    $(this).removeClass('is-invalid');
                }
                if ($(selected).attr('data-id') == 'create') {
                    let types = JSON.parse(`{!! json_encode($exercise_types) !!}`)
                    let html = generate_item_options(types);
                    $("#item-qty-container").css('display', 'none')
                    $("#item-type-select").empty()
                    $("#item-type-select").append(html)
                    $("#meal-exercise-create-modal").modal('show')
                }
            });
            $("#types").change(function() {

                if ($(this).val() == 'Meal') {
                    $("#meal-container").css('display', 'block');
                    $("#exercise-container").css('display', 'none');
                } else if ($(this).val() == 'Exercise') {
                    $("#meal-container").css('display', 'none');
                    $("#exercise-container").css('display', 'block');
                }

            });
        });

        function onCreateClicked() {
            let selectedType = $('#types option:selected').attr('data-id');
            let selectedItemType = $('#item-type-select option:selected').attr('data-id');
            let name = $("#item-name").val();
            let qty = $("#item-qty").val();
            axios.post("{{ route('coach.item.type.new.store') }}", {
                type: selectedType,
                item_name: name,
                qty: qty,
                item_type: selectedItemType
            }).then((response) => {
                let data = response.data;
                let code = data.code;
                if (code == 200) {
                    window.location.reload();
                } else {
                    alert(data.msg)
                }
            }).catch(console.error)
        }

        function generate_item_options(options) {
            let html = '';
            options.forEach((option) => {
                html += `<option data-id="${option.id}">${option.name}</option>`;
            })
            return html;
        }
    </script>
@endpush
