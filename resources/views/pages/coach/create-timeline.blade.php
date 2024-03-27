@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
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
                                <p class="mb-0">New Timeline</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="imeline-name" class="form-control-label">Timeline Name</label>
                                        <input class="form-control" type="text" name="timeline-name" id="timeline-name"
                                            placeholder="Timeline Name ..." autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="goals" class="form-control-label ">Goal</label>
                                        <select class="form-control" id="goals">
                                            <option data-id="0">Please Select a Goal</option>
                                            @foreach ($goals as $goal)
                                                <option data-id="{{ $goal->id }}">{{ $goal->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Plan</label>
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
                                        <label for="example-text-input" class="form-control-label">Disease</label>
                                        <select class="form-control" id="diseases">
                                            <option data-id="0">Please Select a Disease</option>
                                            @foreach ($diseases as $disease)
                                                <option data-id="{{ $disease->id }}">{{ $disease->name }}</option>
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
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#store-new-timeline-form').submit(function(event) {
                event.preventDefault();
                let selectedGoal = $('#goals option:selected').attr('data-id');
                let selectedPlan = $('#plans option:selected').attr('data-id');
                let selectedDisease = $('#diseases option:selected').attr('data-id');
                let isSelectedTrue = true;
                if (selectedGoal == 0 || selectedGoal == '0') {
                    $('#goals').addClass('is-invalid');
                    isSelectedTrue = false;
                }
                if (selectedPlan == 0 || selectedPlan == '0') {
                    $('#plans').addClass('is-invalid');
                    isSelectedTrue = false;
                }
                if (selectedDisease == 0 || selectedDisease == '0') {
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
                        goal: selectedGoal,
                        plan: selectedPlan,
                        disease: selectedDisease,

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
