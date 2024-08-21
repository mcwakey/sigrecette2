<x-default-layout>
    @section('title')
        {{ "Exportation des ".__('contribuables') }}
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
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">

                    <div id="no-data-message" style="display: none;">
                        <div class=" ms-5 mt-1 me-5">
                            <livewire:export-button :table-id="$dataTable->getTableId()" auto-download="true" type="xlsx" buttonName="Export Excel"/>
                        </div>
                    </div>


                </div>

            </div>

        </div>


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
                            <select class="form-select" id="mySearchFour">
                                <option value=""></option>
                                @foreach ($cantons as $canton)
                                    <option value="{{ $canton->name }}">{{ $canton->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('town') }}</label>
                            <select class="form-select" id="mySearchFive">
                                <option value=""></option>
                                @foreach ($towns as $town)
                                    <option value="{{  $town->name }}">{{ $town->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                            <!-- <input type="text" class="form-control" name="tags" /> -->

                            <!--begin::Select-->
                            <select class="form-select" id="mySearchSix">
                                <option value=""></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->name }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('Catégorie') }}</label>
                            <select class="form-select" id="myCat">
                                <option value=""></option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('Activité') }}</label>
                            <select class="form-select" id="myAct">
                                <option value=""></option>
                                @foreach ($activities as $activity)
                                    <option value="{{ $activity->name }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-1">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('authorisation') }}</label>
                            <select class="form-select" id="myAuth">
                                <option></option>
                                <option value="YES">{{ __('yes') }}</option>
                                <option value="NO">{{ __('no') }}</option>
                            </select>
                        </div>
                    </div>

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
                                     pour créer  un nouveau contribuable.
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
            {{-- <br/> --}}
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                var table = $('#export-taxpayers-table').DataTable();

                table.on('xhr', function() {
                    var json = table.ajax.json();
                    if (json.data.length === 0) {
                        $('#no-data-message').hide();
                    } else {
                        $('#no-data-message').show();

                    }
                });
            });
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['export-taxpayers-table'].search(this.value).draw();
            });

            document.getElementById('mySearchZero').addEventListener('keyup', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(1).search(this.value).draw();
            });

            // document.getElementById('mySearchTwo').addEventListener('keyup', function() {
            //     window.LaravelDataTables['export-taxpayers-table'].column(2).search(this.value).draw();
            // });

            document.getElementById('mySearchThree').addEventListener('keyup', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(3).search(this.value).draw();
            });

            document.getElementById('mySearchFour').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(17).search(this.value).draw();
            });


            document.getElementById('mySearchFive').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(18).search(this.value).draw();
            });

            document.getElementById('mySearchSix').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(19).search(this.value).draw();
            });

            document.getElementById('myAct').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(8).search(this.value).draw();
            });
            document.getElementById('myCat').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(12).search(this.value).draw();
            });
            document.getElementById('myAuth').addEventListener('change', function() {
                window.LaravelDataTables['export-taxpayers-table'].column(15).search(this.value).draw();
            });
            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                                       window.LaravelDataTables['export-taxpayers-table'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
