<x-default-layout>

    @section('title')
    {{ __('invoices') }}
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <!--begin::Input group-->
                    <div class="d-flex align-items-center position-relative my-1">
                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                        <input type="text" data-kt-invoice-table-filter="search" class="form-control w-250px ps-13" placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate" data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                        {{ __('advanced search') }} <i class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span class="path1"></span><span class="path2"></span></i></a>
                    </div>

                    <!--end:Action-->

                </div>
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">
                    <button type="button" class="btn btn-outline-secondary" >
                        <a href="#" id="imprimerTableau">  {{ __('print') }}</a>
                    </button>
                </div>
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-invoice-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('create invoice') }}
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">

            <form action="#">
                <div class="collapse" id="kt_advanced_search_form">
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mt-5 mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row g-8 mb-8">
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                            <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchOne" />
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('invoice no') }}</label>
                            <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchTwo" />
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                    <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                    <label class="fs-6 form-label fw-bold text-dark">{{ __('taxlabel') }}</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                            </div>
                            <!--end::Row-->
                        </div>

                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                    <label class="fs-6 form-label fw-bold text-dark">{{ __('aproval') }}</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                    <label class="fs-6 form-label fw-bold text-dark">{{ __('status') }}</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                            </div>
                            <!--end::Row-->
                        </div>
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

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-modal></livewire:invoice.add-invoice-modal>
    <!--end::Modal-->

    @push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
            window.LaravelDataTables['invoices-table'].search(this.value).draw();
        });

        document.getElementById('mySearchOne').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['invoices-table'];

            // Perform a specific search query for column 1
            table.column(1).search(query).draw();
        });

        document.getElementById('mySearchTwo').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['invoices-table'];

            // Perform a specific search query for column 2
            table.column(5).search(query).draw();
        });


        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                $('#kt_modal_add_invoice').modal('hide');
                window.LaravelDataTables['invoices-table'].ajax.reload();
            });
        });
        $(document).ready(function () {
            $('#imprimerTableau').on('click', function () {
                var table = document.getElementById("invoices-table");
                var dataArray = [];

                for (var i = 1; i < table.rows.length; i++) {
                    var row = table.rows[i];
                    var rowData = [];
                    for (var j = 0; j < row.cells.length; j++) {
                        var cellValue = row.cells[j].innerText.trim();
                        rowData.push(cellValue) ;
                    }
                    dataArray.push(rowData);
                }
                var jsonData = JSON.stringify(dataArray);
                var url = "{{ route('generatePdf', ['data' => ':jsonData', 'type' => 2]) }}";
                url = url.replace(':jsonData', encodeURIComponent(jsonData));
                window.location.href = url;
            });
        });




    </script>
    @endpush

</x-default-layout>
