<x-default-layout>
    @section('title')
        @if((request()->routeIs('taxpayers.*')  && !request()->has('disable')) or (request()->routeIs('invoicing.*')) )
            {{ "Liste des ".__('contribuables') }}
        @else
            {{ "Liste des ".__('contribuables') . " " .__('désactivés') }}
        @endif
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
                        <input type="text" data-kt-taxpayer-table-filter="search" class="form-control w-250px ps-13"
                            placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                            class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                            data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                            {{ __('advanced search') }} <i
                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                    class="path1"></span><span class="path2"></span></i></a>
                    </div>


            </div>





        </div>
        @if(request()->routeIs('taxpayers.*'))

            <div class="card-toolbar">

                @if(!request()->has('disable') && !request()->has('state'))

                    @can('peut créer un contribuable')
                        <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal" id="taxpayerbtn"
                                    data-bs-target="#kt_modal_add_taxpayer">
                                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                {{ __('new taxpayer') }}
                            </button>
                            <!--end::Add user-->
                        </div>
                @endcan
                        <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">

                            <div class=" ms-5 mt-1 me-5">
                                <livewire:export-button :table-id="$dataTable->getTableId()" auto-download="true" type="xlsx"  buttonName="Export Excel"/>
                            </div>


                        </div>
                        <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                            <!--begin::Add user-->
                            <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                                <span>
                                    <i class="ki-outline ki-information fs-2tx text-warning"></i>
                                </span>
                            </a>
                            <!--end::Add user-->
                        </div>
                @endif

            </div>
            @endif



        <div class="card-body py-4">

            <form action="#">
                <div class="collapse" id="kt_advanced_search_form">
                    <!--begin::Separator-->
                    <!-- <div class="separator separator-dashed mt-5 mb-5"></div> -->
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row mb-8">
                        <!--begin::Col-->
                        <!-- <div class="col-xxl-6"> -->
                        <!--begin::Col-->
                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('id') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchZero" />
                        </div>
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchOne" />
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('mobilephone') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchThree" />
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('canton') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchFour" />
                        </div>
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('town') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchFive" />
                        </div>
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('erea') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchSix" />
                        </div>
                        <!-- </div> -->
                        <!--begin::Col-->
                        <!-- <div class="col-xxl-6"> -->
                        <!-- <div class="col-xxl-2">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('erea') }}</label>
                                <input type="text" class="form-control" name="tags" value="products, users, events" id="mySearchTwo" />
                            </div> -->
                        <!--end::Col-->
                        <!--begin::Col-->
                        <!-- <div class="col-xxl-2">
                                <label class="fs-6 form-label fw-bold text-dark">{{ __('address') }}</label>
                                <input type="text" class="form-control" name="tags" value="products, users, events" id="mySearchTwo" />
                            </div> -->
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                            <!-- <input type="text" class="form-control" name="tags" /> -->

                            <!--begin::Select-->
                            <select class="form-select" id="mySearchEight">
                                <option value=""></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <!-- </div> -->
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <div class="separator separator-dashed mt-5 mb-5"></div>
                </div>

                <div class="collapse" id="kt_tutorial_form">
                    <!--begin::Notice-->
                    <div class="notice d-flex bg-light-danger rounded border-warning border border-dashed p-6">
                        <!--begin::Icon-->
                        <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                        <!--end::Icon-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1">
                            <!--begin::Content-->
                            <div class="fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Tutoriel sur <a class="fw-bold" href="#"> {{ __('taxpayers') }}</a></h4>
                                <div class="fs-6 text-gray-700">
                                   -> clicker ici
                                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed bg-light-secondary btn-outline-secondary btn-active-light-secondary mx-1 rotate"
                                            data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                            {{ __('advanced search') }} <i
                                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                                    class="path1"></span><span class="path2"></span></i></a> pour afficher le formulaire de recherche avancée.
                                <!-- </div>
                                <div class="fs-6 text-gray-700"> -->
                                   -> clicker ici

                                        <!--begin::Add user-->
                                        <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal"
                                        @can("create taxpayer") data-bs-target="#kt_modal_add_taxpayer" @endcan >
                                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                            {{ __('new taxpayer') }}
                                        </button>
                                        <!--end::Add user-->
                                     pour faire une nouvelle demande d'approvisionnement.
                                </div>
                                <div class="fs-6 text-gray-700 mt-2">
                                 -> utiliser le selecteur <a href="#"
                                                            class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                            data-kt-menu-target="#kt-users-actions"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">
                                                            {{ __('actions') }}
                                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                        </a>

                                                        <!--begin::Menu-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                            data-kt-menu="true" data-kt-menu-id="#kt-users-actions">
                                                            <!--begin::Menu item-->

                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('view') }}
                                                                </a>
                                                            </div>
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('edit') }}
                                                                </a>
                                                            </div>
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('delete') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                    pour plus de controle sur le tableau en dessous selon vos permissions. -> vous pouvez clicker sur le <code>Nom du Contribuable</code> ou sur
                                                                <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-sm">{{ __('view') }}</a> pour acceder a la page de detail du contribuable.
                                <!-- </div>
                                <div class="fs-6 text-gray-700 mt-2"> -->
                                </div>
                                <div class="fs-6 text-gray-700 mt-2">
                                                                -> clicker sur <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                                                                <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('delete') }}</a> ou pour
                                                                pouvoir modifié ou supprimer le contribuable selon vos permissions.
                                </div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Notice-->

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
        <livewire:taxpayer.add-taxpayer-modal />
    @push('scripts')
        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
        <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
        <script src="/vendor/datatables/buttons.server-side.js"></script> -->

        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].search(this.value).draw();
            });

            document.getElementById('mySearchZero').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(1).search(this.value).draw();
            });

            // document.getElementById('mySearchTwo').addEventListener('keyup', function() {
            //     window.LaravelDataTables['taxpayers-table'].column(2).search(this.value).draw();
            // });

            document.getElementById('mySearchThree').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(3).search(this.value).draw();
            });

            document.getElementById('mySearchFour').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(4).search(this.value).draw();
            });

            // document.getElementById('mySearchFour').addEventListener('keyup', function () {
            //     var query = this.value.toLowerCase(); // Convert search query to lowercase
            //     var table = window.LaravelDataTables['taxpayers-table'];

            //     // Construct search query with logical OR between conditions for each column
            //     var columns = [4,5,6]; // Columns to search
            //     var columnQueries = columns.map(function (index) {
            //         return { search: query, column: index, regex: false };
            //     });

            //     table.search(columnQueries).draw();
            // });

            // document.getElementById('mySearchFour').addEventListener('keyup', function () {
            //     var query = this.value.toLowerCase(); // Convert search query to lowercase
            //     var table = window.LaravelDataTables['taxpayers-table'];

            //     // Construct search query with logical OR between conditions for each column
            //     table.search(function (data, index, rowData) {
            //         var searchData = [
            //             data[4], // Column 1 (name)
            //             data[5], // Column 2 (gender)
            //             data[6]  // Column 3 (mobilephone)
            //             // Add additional columns as needed
            //         ];

            //         // Perform a search in each column and return true if any match is found
            //         return searchData.some(function (columnData) {
            //             return columnData.toLowerCase().includes(query);
            //         });
            //     }).draw();
            // });



            document.getElementById('mySearchFive').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(5).search(this.value).draw();
            });

            document.getElementById('mySearchSix').addEventListener('keyup', function() {
                window.LaravelDataTables['taxpayers-table'].column(6).search(this.value).draw();
            });

            document.getElementById('mySearchEight').addEventListener('change', function() {
                window.LaravelDataTables['taxpayers-table'].column(8).search(this.value).draw();
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
