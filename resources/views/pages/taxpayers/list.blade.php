<x-default-layout>

    @section('title')
    {{ __('taxpayers') }}
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection


    <header class="card-header d-flex justify-content-between border-0 mb-2 pt-6 w-100">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                <input type="text" data-kt-taxpayer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('search taxpayer') }}" id="mySearchInput" />
            </div>
            <!--end::Search-->
        </div>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice">
            {!! getIcon('plus', 'fs-2', '', 'i') !!}
            {{ __('New Invoice') }}
        </button>
    </header>

    <div class="card">
        <livewire:taxpayer.add-taxpayer-modal></livewire:taxpayer.add-taxpayer-modal>

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


    @push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{ $dataTable->scripts() }}
    <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
            window.LaravelDataTables['taxpayers-table'].search(this.value).draw();
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