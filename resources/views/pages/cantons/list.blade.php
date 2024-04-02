<x-default-layout>

    @section('title')
    {{ __('cantons') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('cantons.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-canton-table-filter="search" class="form-control w-250px ps-13" placeholder="{{ __('search') }}" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                @can('create canton')
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-canton-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-light-success h-45px ms-auto"  data-bs-toggle="modal" data-bs-target="#kt_modal_add_canton">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('new canton') }}
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->
                @endcan
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">

                {{$dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

                <!--begin::Modal-->
                <livewire:canton.add-canton-modal></livewire:canton.add-canton-modal>
                <!--end::Modal-->

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['cantons'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_canton').modal('hide');
                    window.LaravelDataTables['cantons'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
