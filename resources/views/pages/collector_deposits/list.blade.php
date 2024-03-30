<x-default-layout>

    @section('title')
    {{ __('etat de versement du collecteur') }}
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
                    <input type="text" data-kt-taxpayer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Taxpayer" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
                <livewire:stock_transfer.add-stock-transfer-modal/>
                <livewire:stock_request.add-stock-request-modal/>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['stock_requests-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_stock_request').modal('hide');
                    window.LaravelDataTables['stock_requests-table'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
