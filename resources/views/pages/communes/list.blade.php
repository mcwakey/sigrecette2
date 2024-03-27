<x-default-layout>

    @section('title')
    {{ __('communes') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('communes.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->

                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
               @php
               $commune= \App\Models\Commune::getFirstCommune();
               @endphp

                @if(!$commune)
                    <div class="d-flex justify-content-end" data-kt-commune-table-toolbar="base">
                        <!--begin::Add user-->
                        <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_add_commune">
                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                            {{ __('create commune') }}
                        </button>
                        <!--end::Add user-->
                    </div>
                @endif

            </div>
            <!--end::Card toolbar-->
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

                <livewire:commune.add-commune-modal></livewire:commune.add-commune-modal>

    @push('scripts')

        {{ $dataTable->scripts() }}

        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['communes'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_commune').modal('hide');
                    window.LaravelDataTables['communes'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
