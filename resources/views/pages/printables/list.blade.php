<x-default-layout>

    @section('title')
        {{ __('Impression') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('prints') }}
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
                        <input type="text" data-kt-payment-table-filter="search" class="form-control w-250px ps-13"
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

                        <div href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center ms-auto me-5 d-none"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            {{ __('print') }}
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                        data-kt-menu="true" id="print-modal">


                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 print-link" data-type="5" target="_blank">
                                {{ __('Journal des avis des sommes à payer confiés par le receveur') }}
                            </a>
                        </div>


                    </div>

                </div>
                <!--begin::Toolbar-->
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
                        <div class="col-xxl-2">
                            <!--begin::Row-->
                            <!--begin::Col-->
                            <label class="fs-6 form-label fw-bold text-dark">{{ __("Type de fichers d'impression") }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchThree">
                                <option value="">Selectionner une option</option>
                                <option value="{{App\Enums\PrintNameEnums::BORDEREAU}}">{{App\Enums\PrintNameEnums::BORDEREAU}}</option>
                                <option value="{{App\Enums\PrintNameEnums::BORDEREAU_REDUCTION}}">{{App\Enums\PrintNameEnums::BORDEREAU_REDUCTION}}</option>
                                <option value="{{App\Enums\PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS}}">{{App\Enums\PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS}}</option>
                                <option value="{{App\Enums\PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES}}">{{App\Enums\PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES}}</option>


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



    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>


            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['recoveries-table'].search(this.value).draw();
            });

            document.getElementById('mySearchOne').addEventListener('keyup', function() {
                window.LaravelDataTables['recoveries-table'].column(0).search(this.value).draw();
            });

            document.getElementById('mySearchTwo').addEventListener('keyup', function() {
                window.LaravelDataTables['recoveries-table'].column(1).search(this.value).draw();
            });

            document.getElementById('mySearchThree').addEventListener('change', function() {
                window.LaravelDataTables['recoveries-table'].column(3).search(this.value).draw();
            });


            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                    window.LaravelDataTables['recoveries-table'].ajax.reload();
                });
            });

        </script>
    @endpush

</x-default-layout>
