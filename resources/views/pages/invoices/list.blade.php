<x-default-layout>

    @section('title')

        @if(request()->routeIs('invoices.*') &&  request()->has('aucomptant'))
            {{__('Liste des avis au comptant')}}
        @elseif( request()->routeIs('invoices.*') && request()->input('notDelivery') == 1)
            {{__('Liste des avis non distribués')}}
        @elseif( request()->routeIs('invoices.*') &&  request()->has('notDelivery')&& request()->input('notDelivery') == 0 )
            {{__('Liste des avis distribués')}}
        @elseif(request()->has('state') && request()->input('state') == App\Enums\InvoiceStatusEnums::DRAFT)
            {{ "Liste des ".__('invoices')." sur titre en état de brouillon" }}
        @elseif(request()->has('state') && request()->input('state') == App\Enums\InvoiceStatusEnums::ACCEPTED)
            {{ "Liste des ".__('invoices')." sur titre  en attente de N° d'ordre de recette" }}
        @elseif(request()->has('state') && request()->input('state') == App\Enums\InvoiceStatusEnums::PENDING)
            {{ "Liste des ".__('invoices')." sur titre à prendre en charge/Rejeté" }}
        @else
            {{ "Liste des ".__('invoices')." sur titre" }}
        @endif

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
                <div id="agent-div" class="d-flex justify-content-end me-5 d-none" data-kt-invoice-table-toolbar="base">
                        <select class="form-select" id="r-select">
                            <option value="">Sélectioner un agent de recouvrement</option>
                            @foreach ($agent_recouvrements as $agent)
                                <option value="{{$agent->id }}">{{  $agent->name }}</option>
                            @endforeach
                        </select>

                </div>
                <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">
                        <div href="#" id="print-btn" class="btn btn-light  btn-flex btn-center ms-auto me-5 hover-elevate-up pulse pulse-success d-none"
                             data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            {{ __('print') }}
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            <span class="pulse-ring"></span>
                        </div>

                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                            data-kt-menu="true" id="print-modal">

                        </div>

                    </div>

                @if(request()->routeIs('invoices.*') &&  request()->has('aucomptant'))
                    @can('peut émettre un avis')
                        <div class="d-flex justify-content-end me-5" data-kt-invoice-table-toolbar="base">
                            <!--begin::Add user-->
                        <!-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice_no_taxpayer">
                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('create invoice') }}
                            </button> -->


                                <button type="button" class="btn btn-light-success ms-auto me-5" data-kt-user-id="1"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice_no_taxpayer"
                                        data-kt-action="add_no_invoice">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i> {{ __('Ajouter un avis au comptant') }}
                                </button>




                            <!--end::Add user-->
                        </div>
                    @endcan
                @else
                    @if( request()->routeIs('invoices.*') && !request()->has('notDelivery')  && !request()->has('state') && !request()->has('aucomptant'))
                        @can('peut générer automatiquement les avis')
                            @if (now()->format('m') === '01' || $app->environment('local'))
                                <div class="d-flex justify-content-end" data-kt-invoice-table-toolbar="base">
                                    <!--begin::Add user-->


                                    <button type="button" class="btn btn-light-danger ms-auto me-5"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_auto_invoice">
                                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                        {{ __('create invoice automaticaly') }}
                                    </button>

                                    <!--end::Add user-->
                                </div>
                        @endif

                    @endcan
                            <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">

                                <div class=" ms-5 mt-1 me-5">
                                    <livewire:export-button :table-id="$dataTable->getTableId()" auto-download="true" type="xlsx" buttonName="Export Excel"/>
                                </div>

                            </div>
                @endif
                @endif

            <!--end::Toolbar-->


                <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                    <!--begin::Add user-->
                    <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                        <span>
                            <i class="ki-outline ki-information fs-2tx text-warning"></i>
                        </span>
                    </a>
                    <!--end::Add user-->
                </div>

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

                        <div class="col-xxl-2">
                            <!--begin::Col-->
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('aproval') }}</label>
                            <!--begin::Select-->
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
                    @php
                        $aucomptant =  request()->routeIs('invoices.*') &&  request()->has('aucomptant');
                    @endphp
                    <input type="hidden" value="{{$aucomptant }}" name="accounted_state" id="accounted_state" />


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
                                <h4 class="text-gray-900 fw-bold">Tutoriel sur <a class="fw-bold"
                                                                                  href="#"> {{ __('taxpayers') }}</a>
                                </h4>
                                <div class="fs-6 text-gray-700">
                                    -> clicker ici
                                    <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                                       class="btn btn-outline btn-outline-dashed bg-light-secondary btn-outline-secondary btn-active-light-secondary mx-1 rotate"
                                       data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                        {{ __('advanced search') }} <i
                                            class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                                class="path1"></span><span class="path2"></span></i></a> pour afficher
                                    le formulaire de recherche avancée.
                                    <!-- </div>
                                    <div class="fs-6 text-gray-700"> -->
                                    -> clicker ici

                                    <button type="button" class="btn btn-light-success ms-auto mx-5" data-kt-user-id="1"
                                            data-bs-toggle="modal"
                                             data-bs-target="#kt_modal_add_invoice_no_taxpayer"
                                            data-kt-action="add_no_invoice">
                                        <i class="ki-duotone ki-add-files fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i> {{ __('create invoice') }}
                                    </button>
                                    <!--end::Add user-->
                                    pour creer un nouvel Avis et sur
                                    <button type="button" class="btn btn-light-danger ms-auto mx-5"
                                            data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_auto_invoice">
                                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                        {{ __('create invoice automaticaly') }}
                                    </button>

                                    pour faire une generation de masse des avis valide pour l'operation. Cet action est
                                    disponible juste au debut d'un nouvel exercice.
                                </div>
                                <div class="fs-6 text-gray-700 mt-2">
                                    -> utiliser le selecteur <a href="#"
                                                                class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                                data-kt-menu-target="#kt-users-actions"
                                                                data-kt-menu-trigger="click"
                                                                data-kt-menu-placement="bottom-end">
                                        {{ __('actions') }}
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>

                                    <!--begin::Menu-->
                                    <div
                                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
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
                                    pour plus de controle sur le tableau en dessous selon vos permissions. -> vous
                                    pouvez clicker sur le <code>Nom du Contribuable</code> ou sur
                                    <a href="#"
                                       class="btn btn-outline-success btn-light btn-active-light-primary btn-sm">{{ __('view') }}</a>
                                    pour acceder a la page de detail du contribuable.
                                    <!-- </div>
                                    <div class="fs-6 text-gray-700 mt-2"> -->
                                </div>
                                <div class="fs-6 text-gray-700 mt-2">
                                    -> clicker sur <a href="#"
                                                      class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                                    <a href="#"
                                       class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('delete') }}</a>
                                    ou pour
                                    pouvoir modifié ou supprimer le contribuable selon vos permissions.
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

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-no-taxpayer-modal/>
    <!--end::Modal-->

    <!--begin::Modal-->
    <livewire:payment.add-payment-modal/>
    <!--end::Modal-->

    <!--begin::Modal-->
    @if (now()->format('m') === '01' || $app->environment('local'))
        <livewire:invoice.auto-invoice-modal/>
    @endif
<!--end::Modal-->

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-modal/>
    <!--end::Modal-->

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['invoices-table'].search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function () {
                window.LaravelDataTables['invoices-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchTwo').addEventListener('keyup', function () {
                let s_id = this.value;
                window.LaravelDataTables['invoices-table'].column(1).search(s_id).draw();
            });
            document.getElementById('mySearchM').addEventListener('keyup', function () {
                window.LaravelDataTables['invoices-table'].column(8).search(this.value).draw();
            });


            let zone = "zone 1";
            document.getElementById('mySearchFive').addEventListener('change', function () {
                zone = this.value;
                window.LaravelDataTables['invoices-table'].column(4).search(this.value).draw();
            });

            document.getElementById('mySearchEight').addEventListener('change', function () {
                window.LaravelDataTables['invoices-table'].column(7).search(this.value).draw();
            });

            document.getElementById('mySearchTen').addEventListener('change', function () {
                window.LaravelDataTables['invoices-table'].column(11).search(this.value).draw();
            });

            document.getElementById('mySearchEleven').addEventListener('change', function () {
                window.LaravelDataTables['invoices-table'].column(14).search(this.value).draw();
            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_invoice').modal('hide');
                    $('#kt_modal_auto_invoice').modal('hide');
                    $('#kt_modal_add_payment').modal('hide');
                    $('#kt_modal_add_invoice_no_taxpayer').modal('hide');
                    window.LaravelDataTables['invoices-table'].ajax.reload();
                });
            });
            let agent;
            document.getElementById('r-select').addEventListener('change', function () {
                agent = this.value;
            });
            const printModal = document.getElementById('print-modal');
            const agentDiv = document.getElementById('agent-div');
            const aucomptant =  document.getElementById('accounted_state').value;
            // Sélectionnez le bouton
            const printButton = document.getElementById('print-btn');


            function removePrintMenuItems() {
                while (printModal.firstChild) {
                    printModal.removeChild(printModal.firstChild);
                }
            }

            function addPrintMenuItem(text, type) {
                const newMenuItem = document.createElement('div');
                newMenuItem.classList.add('menu-item', 'px-3');

                const newLink = document.createElement('a');
                newLink.classList.add('menu-link', 'px-3', 'print-link');
                newLink.href = '#';
                newLink.setAttribute('data-type', type);
                newLink.setAttribute('target', '_blank');

                newLink.textContent = text;

                newMenuItem.appendChild(newLink);
                printModal.appendChild(newMenuItem);
            }
            function onSelectedValueChanged(selectedValue) {
                removePrintMenuItems();
                const array = ['{{App\Enums\InvoiceStatusEnums::APPROVED}}','{{ App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION}}','{{  App\Enums\InvoiceStatusEnums::CANCELED }}','{{ App\Enums\InvoiceStatusEnums::PENDING}}',"{{ App\Enums\InvoiceStatusEnums::REJECTED }}",'{{ App\Enums\InvoiceStatusEnums::REDUCED}}'];
                if (array.includes(selectedValue)) {
                    printButton.classList.add('btn-active-light-primary');
                    printButton.classList.remove( "d-none");
                    const approve_array = ['{{App\Enums\InvoiceStatusEnums::APPROVED}}','{{ App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION}}','{{  App\Enums\InvoiceStatusEnums::CANCELED }}','{{ App\Enums\InvoiceStatusEnums::REDUCED}}'];
                    if (
                        approve_array.includes(selectedValue)
                    ) {


                        if( aucomptant){
                            addPrintMenuItem('{{ __('Registre-journal des déclarations préalables des usagers') }}', '77');
                        }else {
                            agentDiv.classList.remove( "d-none")
                            addPrintMenuItem('{{ __('Fiche de recouvrement des avis distribués') }}', '41');
                            addPrintMenuItem('{{ __('Fiche de distribution des avis') }}', '4');
                            addPrintMenuItem('{{ __('Journal des avis des sommes à payer confiés par le receveur') }}', '5');
                            addPrintMenuItem('{{ __('Registre-journal des avis distribués') }}', '3');
                        }
                    }else if(  selectedValue ==="{{ App\Enums\InvoiceStatusEnums::PENDING}}" ){
                        if(aucomptant){
                            addPrintMenuItem('{{ __('Registre-journal des déclarations préalables des usagers') }}', '77');

                        }else {
                            addPrintMenuItem('{{ __('Bordereau journal des avis des sommes à payer') }}', '1');
                            addPrintMenuItem('{{ __('Bordereau journal des avis de réduction ou d’annulation') }}', '2');
                        }
                        agentDiv.classList.add( "d-none")
                    }else addPrintMenuItem('', '1');

                }

                else {
                    printButton.classList.remove('btn-active-light-primary');
                    printButton.classList.add( "d-none");
                }
                printButton.classList.add('btn-active-light-primary');
                printButton.classList.remove( "d-none");
            }

            const selectElement = document.getElementById('mySearchTen');
            selectElement.addEventListener('change', function(event) {
                const selectedValue = event.target.value;
                onSelectedValueChanged(selectedValue);
                document.querySelectorAll('.print-link').forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        let selectedValue = link.getAttribute('data-type');
                        let table = document.getElementById("invoices-table");
                        let tbody =  table.getElementsByTagName('tbody');
                        let rows = tbody[0].querySelectorAll('tr');
                        let dataArray = [];
                        for (let i = 0; i < rows.length; i++) {
                            let id = rows[i].getAttribute('id');
                            dataArray.push(id);
                        }


                        let r_type = 2;
                        let jsonData = JSON.stringify(dataArray);
                        var url;
                        if (agent != null) {
                            url = "{{ route('generatePdf', ['data' => ':jsonData', 'type' => ':r_type', 'action' => ':selectedValue','id'=>':agent']) }}";
                            url = url.replace(':jsonData', encodeURIComponent(jsonData));
                            url = url.replace(':r_type', encodeURIComponent(r_type));
                            url = url.replace(':selectedValue', encodeURIComponent(selectedValue));
                            url = url.replace(':agent', encodeURIComponent(agent));
                        }
                        else{
                            url = "{{ route('generatePdf', ['data' => ':jsonData', 'type' => ':r_type', 'action' => ':selectedValue']) }}";
                            url = url.replace(':jsonData', encodeURIComponent(jsonData));
                            url = url.replace(':r_type', encodeURIComponent(r_type));
                            url = url.replace(':selectedValue', encodeURIComponent(selectedValue));
                        }


                        window.open(url,'_blank');
                    });
                });
            });




        </script>

    @endpush

</x-default-layout>
