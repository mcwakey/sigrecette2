<x-default-layout>

    @section('title')
    {{ __('Taxables') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-taxpayer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Taxe" id="mySearchInput"/>
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-taxable-table-toolbar="base">
                    <div id="no-data-message" style="display: none;">
                        <div class=" ms-5 mt-1 me-5">
                            <livewire:export-button :table-id="$dataTable->getTableId()" auto-download="true" type="xlsx" buttonName="Export Excel"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                var table = $('#export-taxables-table').DataTable();

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
                window.LaravelDataTables['export-taxables-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    window.LaravelDataTables['export-taxables-table'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
