<x-default-layout>

    @section('title')
    {{ __('etat de versement du regisseur title') }}
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

                <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                            data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                            {{ __('advanced search') }} <i
                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                    class="path1"></span><span class="path2"></span></i></a>
                    </div>
                <div class="d-flex align-items-center ms-5">
                    <select class="form-select" id="mySearchThree">
                        <option value="">{{ __("Type de recettes") }}</option>
                        <option value="{{App\Helpers\Constants::INVOICE_TYPE_TITRE}}">{{__('Recettes sur titre')}}</option>
                        <option value="{{App\Helpers\Constants::INVOICE_TYPE_COMPTANT}}">{{__('Recettes au comptant')}}</option>

                    </select>
                </div>
            </div>
            <!--begin::Card title-->

            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end me-5 d-none" data-kt-invoice-table-toolbar="base"  id="print-div">

                    <div href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center ms-auto me-5 "
                         data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        {{ __('print') }}
                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </div>


                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                         data-kt-menu="true" id="print-modal">

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" target="_blank">
                                {{ __('État de versement du Régisseur des Recettes') }}
                            </a>
                        </div>
                    </div>
                </div>

                    @can('peut effectuer un versement au receveur')

                        <div class="d-flex justify-content-end ms-5" data-kt-stock_request-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    id="addrdepositbtn"
                                data-bs-target="#kt_modal_add_accountant_deposit" data-kt-user-id="TITRE" data-kt-action="add_accountant_deposit">
                                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                {{ __('new deposit') }}
                            </button>
                            <!--end::Add user-->
                        </div>

                    @endcan
                <!--end::Toolbar-->

                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                        <!--begin::Add user-->
                        <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                            <span data-bs-toggle="tooltip" title="Onglet tutoriel">
                                <i class="ki-outline ki-information fs-2tx text-warning"></i>
                            </span>
                        </a>
                        <!--end::Add user-->
                    </div>
                <!--begin::Modal-->
                <!--end::Modal-->
            </div>

        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
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
            <div class="col-md-2">
                <label class="fs-6 form-label fw-bold text-dark">{{ __('No de demande') }}</label>
                <input type="text" class="form-control" name="tags" id="mySearchZero" />
            </div>
            <!--begin::Col-->
            <div class="col-md-3">
                <label class="fs-6 form-label fw-bold text-dark">{{ __('taxable') }}</label>
                <input type="text" class="form-control" name="tags" id="mySearchTwo" />
                <!-- <select class="form-select" aria-label="Select a Country" select2="true" data-placeholder="{{ __('select an option') }}" id="mySearchTwo"> -->
                    <!-- <select name="country" id="mySearchTwo" aria-label="Select a Country" data-control="select2" data-placeholder="{{ __('select an option') }}" class="form-select form-select-solid form-select-lg fw-semibold"> -->
                    <!-- <option value=""></option>
                    <option value="RECU">RECU</option>
                    <option value="VENDU">VENDU</option>
                    <option value="RENDU">RENDU</option>
                </select> -->



            </div>
            <div class="col-md-3">
                <label class="fs-6 form-label fw-bold text-dark">{{ __('type') }}</label>
                <!-- <input type="text" class="form-control" name="tags" id="mySearchOne" /> -->
                <select class="form-select" id="mySearchOne">
                    <option value=""></option>
                    <option value="RECU">RECU</option>
                    <option value="VENDU">VENDU</option>
                    <option value="RENDU">RENDU</option>
                </select>
            </div>
            <!--begin::Col-->
            <div class="col-md-2">
                <label class="fs-6 form-label fw-bold text-dark">{{ __('user') }}</label>
                <input type="text" class="form-control" name="tags" id="mySearchFour" />
                <!-- <select class="form-select" id="mySearchFour">
                    <option value=""></option>
                    <option value="Demande">Demande d'approvisionnement</option>
                    <option value="Etat">Etat de comptabilite</option>
                </select> -->
            </div>
            <div class="col-md-2">
                <label class="fs-6 form-label fw-bold text-dark">{{ __('status') }}</label>
                <!-- <input type="text" class="form-control" name="tags" id="mySearchFive" /> -->
                <select class="form-select" id="mySearchFive">
                    <option value=""></option>
                    <option value="ACTIVE">ACTIVE</option>
                    <option value="DONE">COMPTABILISE</option>
                    <option value="ARCHIVE">COMPTE RENDU</option>

                    <option value="RECU">RECU</option>
                    <option value="VENDU">VENDU</option>
                    <option value="RENDU">RENDU</option>
                </select>
            </div>
            <!-- </div> -->
            <!--end::Col-->
        </div>
        <!--end::Row-->

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
                    <h4 class="text-gray-900 fw-bold">Tutoriel sur <a class="fw-bold" href="#">Etat de comptabilite des VI du Regisseur</a></h4>
                    <div class="fs-6 text-gray-700">
                    -> clicker ici
                            <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed bg-light-secondary btn-outline-secondary btn-active-light-secondary mx-1 rotate"
                                data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                {{ __('advanced search') }} <i
                                    class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                        class="path1"></span><span class="path2"></span></i></a> pour afficher le formulaire de recherche avancée.
                    <!-- </div>
                    <div class="fs-6 text-gray-700"> -->
                    -- clicker ici

                        <button type="button" class="btn btn-outline-success btn-success mx-1" data-bs-toggle="modal" data-bs-target="#kt_modal_add_stock_request"  data-kt-action="add_request">
                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                            {{ __('new stock request') }}
                        </button> pour faire une nouvelle demande d'approvisionnement.
                    </div>
                    <div class="fs-6 text-gray-700 mt-2">
                    -> le STATUT <span class="badge badge-lg badge-light-warning d-inline">DEMANDE</span> est pour les demandes non comptabilisées.
                    -> le STATUT <span class="badge badge-lg badge-light-success d-inline">COMPTABILISE</span> est pour les demandes non comptabilisées au niveau des collecteurs.
                    -> le STATUT <span class="badge badge-lg badge-light-primary d-inline">COMPTE RENDU</span> est pour les demandes non comptabilisées au niveau du receveur.
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
                        pour plus de controle sur le tableau en dessous selon vos permissions.
                    <!-- </div>
                    <div class="fs-6 text-gray-700 mt-2"> -->
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
<!--begin::Table-->
<div class="table-responsive">
    {{ $dataTable->table() }}
</div>
<!--end::Table-->
</div>
        <!--end::Card body-->
    </div>
                <livewire:accountant_deposit.add-accountant-deposit-modal/>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['collector_deposits-table'].search(this.value).draw();
            });

            document.getElementById('mySearchZero').addEventListener('keyup', function() {
                window.LaravelDataTables['collector_deposits-table'].column(1).search(this.value).draw();
            });
            document.getElementById('mySearchThree').addEventListener('change', function() {
                window.LaravelDataTables['collector_deposits-table'].column(7).search(this.value).draw();
            });
            // document.getElementById('mySearchOne').addEventListener('change', function() {
            //     window.LaravelDataTables['collector_deposits-table'].column(2).search(this.value).draw();
            // });

            document.getElementById('mySearchTwo').addEventListener('keyup', function() {
                window.LaravelDataTables['collector_deposits-table'].column(2).search(this.value).draw();
            });

            document.getElementById('mySearchFour').addEventListener('keyup', function() {
                window.LaravelDataTables['collector_deposits-table'].column(11).search(this.value).draw();
            });

            document.getElementById('mySearchFive').addEventListener('change', function() {
                window.LaravelDataTables['collector_deposits-table'].column(12).search(this.value).draw();
            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_accountant_deposit').modal('hide');
                    window.LaravelDataTables['collector_deposits-table'].ajax.reload();
                });
            });
            const selectElement = document.getElementById('mySearchThree');
            const printdiv = document.getElementById('print-div')
            let r_type;
            selectElement.addEventListener('change', function(event) {
                const selectedValue = event.target.value;
                const true_array = [ '{{App\Helpers\Constants::INVOICE_TYPE_TITRE}}' ,'{{App\Helpers\Constants::INVOICE_TYPE_COMPTANT}}'];
                if (
                    true_array.includes(selectedValue)
                ) {
                    printdiv.classList.remove( "d-none");
                    if(selectedValue===true_array[0]){
                        r_type = 9;
                    }else {
                        r_type = 16;
                    }


                }else {
                    printdiv.classList.add( "d-none")
                }
                console.log(selectedValue);
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
                    let table = document.getElementById("collector_deposits-table");
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
                            rowData[header] = cellValue;
                        }

                        dataArray.push(rowData);
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
