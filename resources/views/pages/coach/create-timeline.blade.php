@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('custom-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Timeline'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action="{{ route('coach.timelines.new.store') }}"
                        id="store-new-timeline-form">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0 ">New Timeline</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="imeline-name" class="form-control-label">Timeline Name<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="timeline-name" id="timeline-name"
                                            placeholder="Timeline Name ..." autocomplete="off">
                                        <div class="invalid-feedback">
                                            this field is required.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Goal<span class="text-danger">*</span></label>
                                        <select class="form-select " id="goals" data-placeholder="Choose Goals"
                                            multiple>
                                            @foreach ($goals as $goal)
                                                <option data-id="{{ $goal->id }}">{{ $goal->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select one option at least.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Plan<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="plans">
                                            <option data-id="0">Please Select a Plan</option>
                                            @foreach ($plans as $plan)
                                                <option data-id="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Disease<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="diseases" data-placeholder="Choose Diseases"
                                            multiple>
                                            @foreach ($diseases as $disease)
                                                <option data-id="{{ $disease->id }}">{{ $disease->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select one option at least.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Description<span
                                                class="text-danger">(Optinal)</span></label>
                                        <textarea class="form-control" rows="5" name="description" id="description"></textarea>

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
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>


    <script>
        $('#goals').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
            allowClear: true,
        });
        $('#diseases').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
            allowClear: true,
        });
        $(document).ready(function() {
            $('#store-new-timeline-form').submit(function(event) {
                event.preventDefault();
                let selectedPlan = $('#plans option:selected').attr('data-id');
                let selectedGoalsItem = $('#goals option:selected');
                let selectedDiseasesItem = $('#diseases option:selected');
                let selectedGoals = '';
                let selectedDiseases = '';
                let lastGoalItemId = selectedGoalsItem.last().attr('data-id');
                let lastDiseaseItemId = selectedDiseasesItem.last().attr('data-id');
                if (selectedGoalsItem.length > 0) {
                    selectedGoalsItem.each(function() {
                        let id = $(this).attr('data-id');
                        selectedGoals += `${id}`;
                        if (id != lastGoalItemId)
                            selectedGoals += `,`;
                    })
                }
                if (selectedDiseasesItem.length > 0) {
                    selectedDiseasesItem.each(function() {
                        let id = $(this).attr('data-id');
                        selectedDiseases += `${id}`;
                        if (id != lastDiseaseItemId)
                            selectedDiseases += `,`;
                    })
                }

                let isSelectedTrue = true;

                if (selectedGoalsItem.length == 0) {
                    $('#goals').addClass('is-invalid');
                    isSelectedTrue = false;
                }
                if ($('#timeline-name').val() == '') {
                    $('#timeline-name').addClass('is-invalid');
                    isSelectedTrue = false;
                }

                if (selectedPlan == 0 || selectedPlan == '0') {
                    $('#plans').addClass('is-invalid');
                    isSelectedTrue = false;
                }
                if (selectedDiseasesItem.length == 0) {
                    $('#diseases').addClass('is-invalid');
                    isSelectedTrue = false;
                }

                if (!isSelectedTrue) {

                    return false;
                }

                $.ajax({
                    url: $(this).attr('action'), // URL to submit the form data
                    type: $(this).attr('method'), // HTTP method (POST, GET, PUT, DELETE, etc.)
                    data: {
                        name: $('#timeline-name').val(),
                        goals: selectedGoals,
                        plan: selectedPlan,
                        diseases: selectedDiseases,
                        description: $("#description").val()

                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle the success response
                        console.log('Form submitted successfully');
                        $('#store-new-timeline-form').get(0).reset();
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
            $('#goals').change(function() {
                // Remove error class if a value is selected
                if ($(this).val() != '0') {
                    $(this).removeClass('is-invalid');
                }
            });
            $('#plans').change(function() {
                // Remove error class if a value is selected
                if ($(this).val() != '0') {
                    $(this).removeClass('is-invalid');
                }
            });
            $('#diseases').change(function() {
                // Remove error class if a value is selected
                if ($(this).val() != '0') {
                    $(this).removeClass('is-invalid');
                }
            });

        });
    </script>
@endpush
