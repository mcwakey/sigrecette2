<x-default-layout>

    @section('title')
    {{ __('taxpayers') }}
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection

    <div class="card">


        <div class="card-header d-flex justify-content-between border-0 mb-2 pt-6 w-100">
            <!--begin::Card title-->
            <div class="card-title">

                <div class="d-flex align-items-center">
                    <!--begin::Input group-->
                    <div class="d-flex align-items-center position-relative my-1">
                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                        <input type="text" data-kt-taxpayer-table-filter="search" class="form-control w-250px ps-13" placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate" data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                        Advanced Search <i class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span class="path1"></span><span class="path2"></span></i></a>
                    </div>

                    <!--end:Action-->


                </div>
            </div>
            <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer">
                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                {{ __('new taxpayer') }}
            </button>

        </div>

        <!--begin::Card body-->
        <div class="card-body py-4">

            <form action="#">
                <div class="collapse" id="kt_advanced_search_form">
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mt-5 mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row mb-8">
                        <!--begin::Col-->
                        <!-- <div class="col-xxl-6"> -->
                            <!--begin::Col-->
                            <div class="col-xxl-3">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('id')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" id="mySearchThree" />
                            </div>
                            <div class="col-xxl-3">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" id="mySearchOne" />
                            </div>
                            <!--begin::Col-->
                            <div class="col-xxl-3">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('mobilephone')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" id="mySearchTwo" />
                            </div>
                            <!--begin::Col-->
                            <div class="col-xxl-3">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('canton-town')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" id="mySearchThree" />
                            </div>
                        <!-- </div> -->
                        <!--begin::Col-->
                        <!-- <div class="col-xxl-6"> -->
                            <div class="col-xxl-2">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('erea')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchTwo" />
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xxl-2">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('address')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchTwo" />
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xxl-2">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('zone')}}</label>
                                <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchTwo" />
                            </div>
                        <!-- </div> -->
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row g-8">
                        <!--begin::Col-->
                        <div class="col-xxl-7">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Min. Amount</label>
                                    <!--begin::Dialer-->
                                    <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="1000" data-kt-dialer-max="50000" data-kt-dialer-step="1000" data-kt-dialer-prefix="$" data-kt-dialer-decimals="2">
                                        <!--begin::Decrease control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                            <i class="ki-outline ki-minus-circle fs-1"></i>
                                        </button>
                                        <!--end::Decrease control-->
                                        <!--begin::Input control-->
                                        <input type="text" class="form-control form-control-solid border-0 ps-12" data-kt-dialer-control="input" placeholder="Amount" name="manageBudget" readonly="readonly" value="$50" />
                                        <!--end::Input control-->
                                        <!--begin::Increase control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                            <i class="ki-outline ki-plus-circle fs-1"></i>
                                        </button>
                                        <!--end::Increase control-->
                                    </div>
                                    <!--end::Dialer-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Max. Amount</label>
                                    <!--begin::Dialer-->
                                    <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="1000" data-kt-dialer-max="50000" data-kt-dialer-step="1000" data-kt-dialer-prefix="$" data-kt-dialer-decimals="2">
                                        <!--begin::Decrease control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                            <i class="ki-outline ki-minus-circle fs-1"></i>
                                        </button>
                                        <!--end::Decrease control-->
                                        <!--begin::Input control-->
                                        <input type="text" class="form-control form-control-solid border-0 ps-12" data-kt-dialer-control="input" placeholder="Amount" name="manageBudget" readonly="readonly" value="$100" />
                                        <!--end::Input control-->
                                        <!--begin::Increase control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                            <i class="ki-outline ki-plus-circle fs-1"></i>
                                        </button>
                                        <!--end::Increase control-->
                                    </div>
                                    <!--end::Dialer-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Team Size</label>
                                    <input type="text" class="form-control form-control form-control-solid" name="city" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-5">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Category</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">Select</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Status</label>
                                    <div class="form-check form-switch form-check-custom form-check-solid mt-1">
                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchChecked" checked="checked" />
                                        <label class="form-check-label" for="flexSwitchChecked">Active</label>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <div class="separator separator-dashed mt-5 mb-5"></div>
                </div>

            </form>
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>


    <livewire:taxpayer.add-taxpayer-modal></livewire:taxpayer.add-taxpayer-modal>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{ $dataTable->scripts() }}
    <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
            window.LaravelDataTables['taxpayers-table'].search(this.value).draw();
        });

        document.getElementById('mySearchOne').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['taxpayers-table'];

            // Perform a specific search query for column 1
            table.column(1).search(query).draw();
        });

        document.getElementById('mySearchTwo').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['taxpayers-table'];

            // Perform a specific search query for column 2
            table.column(3).search(query).draw();
        });

        // document.getElementById('mySearchThree').addEventListener('keyup', function() {
        //     var query = this.value;
        //     var table = window.LaravelDataTables['taxpayers-table'];

        //     // Perform a specific search query for column 2
        //     table.column(3).search(query).draw();
        // });

//         document.getElementById('mySearchThree').addEventListener('keyup', function () {
//     var query = this.value;
//     var table = window.LaravelDataTables['taxpayers-table'];

//     // Perform a search query for column 1 OR column 2
//     table.columns([3, 4]).search(query, true, false).draw();
// });

document.getElementById('mySearchThree').addEventListener('keyup', function () {
    var query = this.value;
    var table = window.LaravelDataTables['taxpayers-table'];

    // Perform a specific search query for column 1
    //table.column(3).search(query).draw();

    // Perform a specific search query for column 2
    table.columns([4,5,6]).search(query).draw();
});



        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                $('#kt_modal_add_taxpayer').modal('hide');
                window.LaravelDataTables['taxpayers-table'].ajax.reload();
            });
        });

        // const createdAtFieldCheckBox = document.getElementById('created_at');
        // const genderFieldCheckBox = document.getElementById('gender');
        // const townFieldCheckBox = document.getElementById('town');
        // const zoneFieldCheckBox = document.getElementById('zone');
        // const cantonFieldCheckBox = document.getElementById('canton');
        // const addressFieldCheckBox = document.getElementById('address');
        // const mobilePhoneFieldCheckBox = document.getElementById('mobilephone');

        // /**
        //  * Toggles the visibility of DOM elements with a given class name 
        //  * based on the checked state of a checkbox input.
        //  * 
        //  * @param {HTMLInputElement} fieldCheckBoxInput - The checkbox input element.
        //  */
        // function toggleField(fieldCheckBoxInput) {
        //     const inputState = fieldCheckBoxInput.checked;
        //     const fields = document.querySelectorAll(`.${fieldCheckBoxInput.id}`);

        //     fields.forEach((field) => {
        //         if (inputState && field.classList.contains('d-none')) {
        //             field.classList.replace('d-none', 'd-inline-block')
        //         } else if (inputState) {
        //             field.classList.add('d-inline-block')
        //         } else {
        //             field.classList.add('d-none');
        //         }
        //     });
        // }

        // createdAtFieldCheckBox.addEventListener('click', () => toggleField(createdAtFieldCheckBox));
        // zoneFieldCheckBox.addEventListener('click', () => toggleField(zoneFieldCheckBox));
        // genderFieldCheckBox.addEventListener('click', () => toggleField(genderFieldCheckBox));
    </script>
    @endpush

</x-default-layout>