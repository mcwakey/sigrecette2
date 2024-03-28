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
                        <input type="text" data-kt-invoice-table-filter="search" class="form-control w-250px ps-13"
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

                    <!--end:Action-->

                </div>
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">
                    @hasanyrole(['regisseur', 'administrateur_system'])
                        <div href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm me-2"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            {{ __('print') }}
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </div>
                    @endhasanyrole

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                        data-kt-menu="true" id="print-modal">
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" data-type="1" target="_blank">
                                {{ __('Bordereau journal des avis des sommes à payer') }}
                            </a>
                        </div>
                        <!--

                         <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" data-type="3" target="_blank">
                                {{ __('Journal des avis des sommes à payer') }}
                        </a>
                    </div>
                     <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" data-type="4" target="_blank">
                                {{ __('Fiche de distribution des avis') }}
                        </a>
                    </div>
                      <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" data-type="5" target="_blank">
                                {{ __('Fiche de recouvrement des avis distribués') }}
                        </a>
                    </div>
-->







                    </div>

                </div>
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">
                    <!--begin::Add user-->
                    <!-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice_no_taxpayer">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('create invoice') }}
                    </button> -->

                    @hasanyrole(['agent_assiette','administrateur_system'])

                    <button type="button" class="btn btn-light-success ms-auto me-5" data-kt-user-id="1"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice_no_taxpayer"
                        data-kt-action="add_no_invoice">
                        <i class="ki-duotone ki-add-files fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i> {{ __('create invoice') }}
                    </button>

                    @endhasanyrole


                    <!--end::Add user-->
                </div>
                <div class="d-flex justify-content-end" data-kt-invoice-table-toolbar="base">
                    <!--begin::Add user-->

                    @hasanyrole(['regisseur','administrateur_system'])

                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_auto_invoice">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('create invoice automaticaly') }}
                    </button>

                    @endhasanyrole

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
                            <input type="text" class="form-control" name="tags" id="mySearchOne" />
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('invoice no') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchTwo" />
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchFive">
                                <option value=""></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <!--end::Col-->
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <!--begin::Col-->
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxlabel') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchEight">
                                <option value=""></option>
                                @foreach ($tax_labels as $tax_label)
                                    <option value="{{ $tax_label->id }}">{{ $tax_label->code }} --
                                        {{ $tax_label->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                            <!--end::Row-->
                        </div>

                        <div class="col-xxl-2">
                            <!--begin::Col-->
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('aproval') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchTen">
                                <option value=""></option>
                                <option value="APROVED">{{ __('APROVED') }}</option>
                                <option value="REJECTED">{{ __('REJECTED') }}</option>
                                <option value="CANCELED">{{ __('CANCELED') }}</option>
                                <option value="PENDING">{{ __('PENDING') }}</option>
                            </select>
                            <!--end::Select-->
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <!--begin::Col-->
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('status') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchEleven">
                                <option value=""></option>
                                <option value="VALID">{{ __('VALID') }}</option>
                                <option value="EXPIRED">{{ __('EXPIRED') }}</option>
                                <option value="CANCELED">{{ __('CANCELED') }}</option>
                                <option value="ARCHIVED">{{ __('ARCHIVED') }}</option>
                            </select>

                            <!--end::Select-->
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
    <livewire:invoice.add-invoice-no-taxpayer-modal />
    <!--end::Modal-->

    <!--begin::Modal-->
    <livewire:payment.add-payment-modal />
    <!--end::Modal-->

    <!--begin::Modal-->
    @if (now()->format('m-d') === '01-01' || $app->environment('local'))
        <livewire:invoice.auto-invoice-modal />
    @endif
    <!--end::Modal-->

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-modal />
    <!--end::Modal-->

    @push('scripts')
<<<<<<< Updated upstream
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['invoices-table'].search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function() {
                window.LaravelDataTables['invoices-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchTwo').addEventListener('keyup', function() {
                window.LaravelDataTables['invoices-table'].column(1).search(this.value).draw();
            });

            document.getElementById('mySearchFive').addEventListener('change', function() {
                window.LaravelDataTables['invoices-table'].column(4).search(this.value).draw();
            });

            document.getElementById('mySearchEight').addEventListener('change', function() {
                window.LaravelDataTables['invoices-table'].column(7).search(this.value).draw();
            });

            document.getElementById('mySearchTen').addEventListener('change', function() {
                window.LaravelDataTables['invoices-table'].column(9).search(this.value).draw();
            });

            document.getElementById('mySearchEleven').addEventListener('change', function() {
                window.LaravelDataTables['invoices-table'].column(10).search(this.value).draw();
            });

            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                    $('#kt_modal_add_invoice').modal('hide');
                    $('#kt_modal_auto_invoice').modal('hide');
                    window.LaravelDataTables['invoices-table'].ajax.reload();
                });
            });

            document.querySelectorAll('.print-link').forEach(function(link) {
                function capitalizeFirstLetter(str) {
                    let array = ["NIC", "GPS"];

                    if (array.includes(str.toUpperCase())) {
                        return str;
                    } else {
                        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
                    }
                }
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    let selectedValue = link.getAttribute('data-type');
                    let table = document.getElementById("invoices-table");
                    let dataArray = [];
                    let headers = [];


                    // for (let i = 1; i < table.rows.length; i++) {let row = table.rows[i];let rowData = [];for (let j = 0; j < row.cells.length; j++) {let cellValue = row.cells[j].innerText.trim();rowData.push(cellValue);}dataArray.push(rowData);}

                    let headerRow = table.getElementsByTagName("thead")[0].getElementsByTagName("tr")[0];
                    for (let i = 0; i < headerRow.cells.length; i++) {
                        headers.push(headerRow.cells[i].innerText.trim());
                    }

                    for (let i = 1; i < table.rows.length; i++) {
                        let row = table.rows[i];
                        let rowData = {};
                        for (let j = 0; j < row.cells.length; j++) {
                            let cellValue = row.cells[j].innerText.trim();
                            let header = headers[j];
                            rowData[capitalizeFirstLetter(header)] = cellValue;
                        }

                        dataArray.push(rowData);
                    }

                    // console.log(dataArray);

                    let r_type = 2;
                    if (selectedValue === '3') {
                        r_type = 3;
                    } else if (selectedValue === '4') {
                        r_type = 4;
                    } else if (selectedValue === '5') {
                        r_type = selectedValue;
                    } else if (selectedValue === '6') {
                        r_type = selectedValue;
                    }
                    let jsonData = JSON.stringify(dataArray);
                    let url =
                        "{{ route('generatePdf', ['data' => ':jsonData', 'type' => ':r_type', 'action' => ':selectedValue']) }}";
                    url = url.replace(':jsonData', encodeURIComponent(jsonData));
                    url = url.replace(':r_type', encodeURIComponent(r_type));
                    url = url.replace(':selectedValue', encodeURIComponent(selectedValue));

                    window.location.href = url;
                });
            });
        </script>
    @endpush

</x-default-layout>
