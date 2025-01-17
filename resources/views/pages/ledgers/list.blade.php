<x-default-layout>

    @section('title')
    {{ __('livre journal de la regie') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-taxpayer-table-filter="search"
                        class="form-control w-250px ps-13" placeholder="{{ __('search') }} "
                        id="mySearchInput" />
                </div>

            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">

                    <div href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center ms-auto me-5"
                         data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        {{ __('print') }}
                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </div>


                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                         data-kt-menu="true" id="print-modal">
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" target="_blank">
                                {{ __('LIVRE-JOURNAL DE REGIE') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">

                    <div class=" ms-5 mt-1 me-5">
                        <livewire:export-button :table-id="$dataTable->getTableId()" auto-download="true" type="xlsx" buttonName="Export Excel"/>
                    </div>

                </div>
                    <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                        <!--begin::Add user-->
                        <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                            <span data-bs-toggle="tooltip" title="Onglet tutoriel">
                                <i class="ki-outline ki-information fs-2tx text-warning"></i>
                            </span>
                        </a>
                    </div>

            </div>

        </div>
        <div class="card-body py-4">


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
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['ledgers-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_stock_transfer').modal('hide');
                    window.LaravelDataTables['ledgers-table'].ajax.reload();
                });
            });
            document.querySelectorAll('.print-link').forEach(function(link) {

                function capitalizeFirstLetter(str) {
                    let array = ["NIC", "GPS"];

                    if (array.includes(str.toUpperCase())) {
                        return str;
                    } else {
                        let words = str.toLowerCase().split(' ');
                        for (let i = 0; i < words.length; i++) {
                            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
                        }
                        return words.join(' ');
                    }
                }
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    let selectedValue = link.getAttribute('data-type');
                    let dataArray = [];



                    let r_type = 10;
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
