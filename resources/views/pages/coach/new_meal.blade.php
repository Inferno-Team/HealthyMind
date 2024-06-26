@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'New Meal'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12" style="z-index:1">

                <div class="card mb-4 h-75" style="overflow:auto;min-height:500px;" id="table-container">
                    <div class="card-header pb-0">
                        <h6>New Meal</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ">
                        <div class="row p-3 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item-name" class="form-control-label">Meal Name<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="meal-name" id="meal-name"
                                        placeholder="Meal Name ..." autocomplete="off">
                                    <div class="invalid-feedback">
                                        this field is required.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meal-qty" class="form-control-label">meal quantity<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="number" min="0" name="meal-qty" id="meal-qty"
                                        placeholder="meal qty" autocomplete="off"
                                        oninput="this.value = this.value.replace(/[^\d]/, ''); if(parseInt(this.value) < 0) this.value = '0';">
                                    <div class="invalid-feedback">
                                        this field is required.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meal-qty_type-select" class="form-control-label">Meal Quantity Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="meal-qty_type-select">
                                        <option id="none"> Please select qty type</option>
                                        @foreach ($qty_types as $type)
                                            <option id="{{ $type->id }}">{{ $type->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select one option.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meal-type-select" class="form-control-label">Meal Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="meal-type-select">
                                        <option id="none"> Please select meal type</option>
                                        @foreach ($types as $type)
                                            <option id="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select one option.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="equipment-select" class="form-control-label">Ingredients<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="ingredients" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        this field is required.
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="equipment-select" class="form-control-label">Description<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        this field is required.
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" style="width:100%"
                                    onclick="onCreateClicked()">Create</button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script>
        function onCreateClicked() {
            let mealName = $("#meal-name").val();
            let mealType = $("#meal-type-select option:selected").attr('id');
            let mealQtyType = $("#meal-qty_type-select option:selected").attr('id');
            let mealQTY = $("#meal-qty").val();
            let ingredients = $("#ingredients").val();
            let description = $("#description").val();
            let isSelectedTrue = true;
            if (mealName == '') {
                $("#meal-name").addClass('is-invalid');
                isSelectedTrue = false;
            }
            if (mealQTY == '') {
                $("#meal-qty").addClass('is-invalid');
                isSelectedTrue = false;
            }
            if (ingredients == '') {
                $("#ingredients").addClass('is-invalid');
                isSelectedTrue = false;
            }
            if (description == '') {
                $("#description").addClass('is-invalid');
                isSelectedTrue = false;
            }
            if (mealType == 'none') {
                $("#meal-type-select").addClass('is-invalid');
                isSelectedTrue = false;
            }
            if (mealQtyType == 'none') {
                $("#meal-qty_type-select").addClass('is-invalid');
                isSelectedTrue = false;
            }

            if (!isSelectedTrue) return;
            axios.post("{{ route('coach.meals.new') }}", {
                    name: mealName,
                    type: mealType,
                    qty: mealQTY,
                    qty_type: mealQtyType,
                    description: description,
                    ingredients: ingredients,
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
@endpush
