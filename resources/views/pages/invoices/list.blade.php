<x-default-layout>

    @section('title')
    {{ __('invoices') }}
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('taxpayers.index') }}
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
                        <input type="text" data-kt-invoice-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Invoice" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ml-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">Advanced Search</a>
                    </div>
                    <!--end:Action-->


                </div>
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-invoice-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('New Invoice') }}
                    </button>
                    <!--end::Add user-->
                </div>
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
                        <!--begin::Col-->
                        <div class="col-xxl-3">
                            <label class="fs-6 form-label fw-bold text-dark">Tags</label>
                            <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchOne" />
                        </div>
                        <!--begin::Col-->
                        <div class="col-xxl-4">
                            <label class="fs-6 form-label fw-bold text-dark">Tags</label>
                            <input type="text" class="form-control form-control form-control-solid" name="tags" value="products, users, events" id="mySearchTwo" />
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-5">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Team Type</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Select Group</label>
                                    <!--begin::Radio group-->
                                    <div class="nav-group nav-group-fluid">
                                        <!--begin::Option-->
                                        <label>
                                            <input type="radio" class="btn-check" name="type" value="has" checked="checked" />
                                            <span class="btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4">All</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label>
                                            <input type="radio" class="btn-check" name="type" value="users" />
                                            <span class="btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4">Users</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label>
                                            <input type="radio" class="btn-check" name="type" value="orders" />
                                            <span class="btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4">Orders</span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Radio group-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row g-8">
                        <!--begin::Col-->
                        <div class="col-xxl-7">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Min. Amount</label>
                                    <!--begin::Dialer-->
                                    <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="1000" data-kt-dialer-max="50000" data-kt-dialer-step="1000" data-kt-dialer-prefix="$" data-kt-dialer-decimals="2">
                                        <!--begin::Decrease control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                            <i class="ki-outline ki-minus-circle fs-1"></i>
                                        </button>
                                        <!--end::Decrease control-->
                                        <!--begin::Input control-->
                                        <input type="text" class="form-control form-control-solid border-0 ps-12" data-kt-dialer-control="input" placeholder="Amount" name="manageBudget" readonly="readonly" value="$50" />
                                        <!--end::Input control-->
                                        <!--begin::Increase control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                            <i class="ki-outline ki-plus-circle fs-1"></i>
                                        </button>
                                        <!--end::Increase control-->
                                    </div>
                                    <!--end::Dialer-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Max. Amount</label>
                                    <!--begin::Dialer-->
                                    <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="1000" data-kt-dialer-max="50000" data-kt-dialer-step="1000" data-kt-dialer-prefix="$" data-kt-dialer-decimals="2">
                                        <!--begin::Decrease control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                            <i class="ki-outline ki-minus-circle fs-1"></i>
                                        </button>
                                        <!--end::Decrease control-->
                                        <!--begin::Input control-->
                                        <input type="text" class="form-control form-control-solid border-0 ps-12" data-kt-dialer-control="input" placeholder="Amount" name="manageBudget" readonly="readonly" value="$100" />
                                        <!--end::Input control-->
                                        <!--begin::Increase control-->
                                        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                            <i class="ki-outline ki-plus-circle fs-1"></i>
                                        </button>
                                        <!--end::Increase control-->
                                    </div>
                                    <!--end::Dialer-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <label class="fs-6 form-label fw-bold text-dark">Team Size</label>
                                    <input type="text" class="form-control form-control form-control-solid" name="city" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-5">
                            <!--begin::Row-->
                            <div class="row g-8">
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Category</label>
                                    <!--begin::Select-->
                                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="In Progress" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">Not started</option>
                                        <option value="2" selected="selected">Select</option>
                                        <option value="3">Done</option>
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <label class="fs-6 form-label fw-bold text-dark">Status</label>
                                    <div class="form-check form-switch form-check-custom form-check-solid mt-1">
                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchChecked" checked="checked" />
                                        <label class="form-check-label" for="flexSwitchChecked">Active</label>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
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

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-modal></livewire:invoice.add-invoice-modal>
    <!--end::Modal-->

    @push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
            window.LaravelDataTables['invoices-table'].search(this.value).draw();
        });

        document.getElementById('mySearchOne').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['invoices-table'];

            // Perform a specific search query for column 1
            table.column(1).search(query).draw();
        });

        document.getElementById('mySearchTwo').addEventListener('keyup', function() {
            var query = this.value;
            var table = window.LaravelDataTables['invoices-table'];

            // Perform a specific search query for column 2
            table.column(5).search(query).draw();
        });


        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                $('#kt_modal_add_invoice').modal('hide');
                window.LaravelDataTables['invoices-table'].ajax.reload();
            });
        });
    </script>
    @endpush

</x-default-layout>