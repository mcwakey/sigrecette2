<x-default-layout>
    @section('title')

        {{ "Liste générale des avis sur titre " }}

    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('invoices.index') }}
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
                               placeholder="{{ __('search') }}" id="mySearchInput"/>
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
                    <div class="separator separator-dashed mt-5 mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row g-8 mb-8">
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchOne"/>
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('invoice no') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchTwo"/>
                        </div>
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('Montant') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchM"/>
                        </div>
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


                        <div class="col-xxl-2 ">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('aproval') }}</label>
                            <select class="form-select" id="mySearchTen">
                                <option value=""></option>
                                <option value="{{App\Enums\InvoiceStatusEnums::APPROVED}}">{{ __('APROVED') }}</option>
                                <option value="{{ App\Enums\InvoiceStatusEnums::REJECTED }}">{{ __('REJECTED') }}</option>
                                <option value="{{  App\Enums\InvoiceStatusEnums::CANCELED }}">{{ __('CANCELED') }}</option>
                                <option value="{{  App\Enums\InvoiceStatusEnums::REDUCED }}">{{ __('REDUCED') }}</option>
                                <option value="{{ App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION}}">{{ __("AVIS D'ANNULATION/REDUCTION") }}</option>
                                <option value="{{ App\Enums\InvoiceStatusEnums::PENDING}}">{{ __('PENDING') }}</option>
                                <option value="{{ App\Enums\InvoiceStatusEnums::ACCEPTED}}">{{ __('ACCEPTED') }}</option>
                                <option value="{{ App\Enums\InvoiceStatusEnums::DRAFT}}">{{ __('DRAFT') }}</option>
                            </select>
                        </div>


                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('status') }}</label>
                            <select class="form-select" id="mySearchEleven">
                                <option value=""></option>
                                <option value="VALID">{{ __('VALID') }}</option>
                                <option value="EXPIRED">{{ __('EXPIRED') }}</option>
                                <option value="CANCELED">{{ __('CANCELED') }}</option>
                                <option value="ARCHIVED">{{ __('ARCHIVED') }}</option>
                            </select>

                        </div>

                    </div>



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

@push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                var table = $('#export-invoices-table').DataTable();

                table.on('xhr', function() {
                    var json = table.ajax.json();
                    if (json.data.length === 0) {
                        $('#no-data-message').hide();
                    } else {
                        $('#no-data-message').show();

                    }
                });
            });
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['export-invoices-table'].search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function () {
                window.LaravelDataTables['export-invoices-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchTwo').addEventListener('keyup', function () {
                let s_id = this.value;
                window.LaravelDataTables['export-invoices-table'].column(1).search(s_id).draw();
            });
            document.getElementById('mySearchM').addEventListener('keyup', function () {
                window.LaravelDataTables['export-invoices-table'].column(8).search(this.value).draw();
            });
            document.getElementById('mySearchFive').addEventListener('change', function () {
                zone = this.value;
                window.LaravelDataTables['export-invoices-table'].column(4).search(this.value).draw();
            });

            document.getElementById('mySearchEight').addEventListener('change', function () {
                window.LaravelDataTables['export-invoices-table'].column(7).search(this.value).draw();
            });

            document.getElementById('mySearchTen').addEventListener('change', function () {
                window.LaravelDataTables['export-invoices-table'].column(11).search(this.value).draw();
            });

            document.getElementById('mySearchEleven').addEventListener('change', function () {
                window.LaravelDataTables['export-invoices-table'].column(14).search(this.value).draw();
            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    window.LaravelDataTables['export-invoices-table'].ajax.reload();
                });
            });


        </script>

    @endpush

</x-default-layout>
