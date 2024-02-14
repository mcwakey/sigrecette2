<x-default-layout>

    @section('title')
    {{ __('taxpayers information') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.show', $taxpayer) }}
    @endsection

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Summary-->
                    <!--begin::User Info-->
                    <div class="d-flex flex-center flex-column py-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            @if($taxpayer->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="image"/>
                            @else
                                <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxpayer->name) }}">
                                    {{ substr($taxpayer->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#" class="fs-3 text-gray-800 text-hover-success fw-bold mb-3">{{ $taxpayer->name }}</a>
                        <!--end::Name-->
                        <!--begin::Position-->
                        <div class="mb-9">
                            {{-- @foreach($user->roles as $role) --}}
                                <!--begin::Badge-->
                                <div class="badge badge-lg badge-light-danger d-inline">{{ $taxpayer->gender}}</div>
                                <!--begin::Badge-->
                            {{-- @endforeach --}}
                        </div>
                        <!--end::Position-->
                        <!--begin::Info-->
                        <!--begin::Info heading-->
                        <!-- <div class="fw-bold mb-3">Outstanding Balance
                            <span class="ms-2" ddata-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Number of support tickets assigned, closed and pending this week.">
                                <i class="ki-duotone ki-information fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div> -->
                        <!--end::Info heading-->
                        <!-- <div class="d-flex flex-wrap flex-center"> -->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-75px">243 000</span>
                                    <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Total</div>
                            </div> -->
                            <!--end::Stats-->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-50px">56 000</span>
                                    <i class="ki-duotone ki-arrow-down fs-3 text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Paid</div>
                            </div> -->
                            <!--end::Stats-->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-50px">188</span>
                                    <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Open</div>
                            </div> -->
                            <!--end::Stats-->
                        <!-- </div> -->
                        <!--end::Info-->
                    </div>
                    <!--end::User Info-->
                    <!--end::Summary-->
                    <!--begin::Details toggle-->
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">{{ __('details') }}
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>
                        <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="{{ __('edit Taxpayers details') }}">
                            <a href="#" class="btn btn-sm btn-light-success" data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer" data-kt-action="update_row">{{ __('edit') }}</a>
                        </span>
                    </div>

                
                    <!--end::Details toggle-->
                    <div class="separator"></div>
                    <!--begin::Details content-->
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('account id') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->tnif }}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('mobilephone') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->mobilephone}} / {{ $taxpayer->telephone}}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('email') }}</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary">{{ $taxpayer->email }}</a>
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('address') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->address }}
                                <br />{{ $taxpayer->erea }},
                                <br />{{ $taxpayer->town }},
                                <br />{{ $taxpayer->canton }}.
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('zone') }}</div>
                            <div class="text-gray-600"><span class="badge badge-light-info">{{ $taxpayer->zone_id }}</span></div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('joined date') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->created_at->format('d M Y') }}</div>
                            <!--begin::Details item-->
                        </div>
                    </div>
                    <!--end::Details content-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-15">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-success pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">{{ __('overview') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-success pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_view_overview_security">{{ __('invoices payments') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-success pb-4" data-bs-toggle="tab" href="#kt_user_view_overview_events_and_logs_tab">{{ __('events logs') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item ms-auto">
                    <!--begin::Action menu-->
                    <a href="#" class="btn btn-success ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">{{ __('actions') }}
                        <i class="ki-duotone ki-down fs-2 me-0"></i></a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">{{ __('payments') }}</div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer_taxable">{{ __('create asset') }}</a>
                        </div>
                        
                        <livewire:taxpayer_taxable.add-taxpayer-taxable-modal></livewire:taxpayer_taxable.add-taxpayer-taxable-modal>

                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer">{{ __('create invoice') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer">{{ __('create payment') }}</a>
                                <span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
                                    <i class="ki-duotone ki-information fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span></a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-3"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">{{ __('account') }}</div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('reports') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5 my-1">
                            <a href="#" class="menu-link px-5">{{ __('account settings') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link text-danger px-5">{{ __('delete taxpayer') }}</a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                    <!--end::Menu-->
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->
            <!--begin:::Tab content-->
            <div class="tab-content" id="myTabContent">
                <!--begin:::Tab pane-->
                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">{{ __('taxpayers assets') }}</h2>
                                <div class="fs-6 fw-semibold text-muted">{{ __('registered assets') }}</div>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                            <button type="button" class="btn btn-light-success btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer_taxable">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>{{ __('create asset') }}</button>
                            </div>
                            <!-- <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_schedule">
                                    <i class="ki-duotone ki-brush fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    View All
                                </button>
                            </div> -->
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body p-9 pt-4">
                            <!--begin::Tab Content-->
                            <div class="tab-content">
                                <!--begin::Day-->
                                <div id="kt_schedule_day_1" class="tab-pane fade show active">
                                    <!--begin::Time-->
                                    @foreach($taxpayer->taxpayertaxables as $taxpayertaxable)
                                        <div class="row">
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                    <div class="col-md-7 fw-semibold ms-5">
                                                        <!--begin::Title-->
                                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-success mb-2">{{ $taxpayertaxable->taxable->tax_label->name }}</a> -> {{ $taxpayertaxable->taxable->name }}
                                                        <!--end::Title-->
                                                        <!--begin::Time-->
                                                        <div class="fs-7 mb-1">{{ $taxpayertaxable->name }} : {{ $taxpayertaxable->seize }}
                                                            <span class="fs-7 text-muted text-uppercase">{{ $taxpayertaxable->taxable->unit }}</span>
                                                        </div>
                                                        <!--end::Time-->
                                                        <!--begin::User-->
                                                        <div class="fs-7 mb-1">{{ __('located at') }}
                                                            <span class="fs-7 text-muted">{{ $taxpayertaxable->location }}</span>
                                                        </div>
                                                        <!--end::User-->
                                                    </div>
                                                    <div class="col-md-2 fw-semibold ms-5">
                                                        @if($taxpayertaxable->invoice_id == null)
                                                        <div class="badge badge-lg badge-light-danger d-inline">{{ __('not billed') }}</div>
                                                        @else
                                                        <div class="badge badge-lg badge-light-success d-inline">{{ __('billed') }} : {{ $taxpayertaxable->invoice->invoice_no }}</div>
                                                        @endif
                                                    </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-success btn-sm">{{ __('view') }}</a>
                                                <!--end::Action-->
                                            </div>
                                        </div>
                                    @endforeach
                                    <!--end::Time-->
                                </div>
                                <!--end::Day-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Card body-->
                    </div>


                    <!--end::Card-->
                    <!--begin::Tasks-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">{{ __('taxpayers geolocation') }}</h2>
                                <div class="fs-6 fw-semibold text-muted">Long: {{ $taxpayer->longitude}} Lat: {{ $taxpayer->latitude}}</div>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <!-- <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_task">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Add Task</button>
                            </div> -->
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex flex-column">
                            <!--begin::Item-->
                            <div class="card-body">
                                <div id="location_map" class="w-100 rounded" style="height:350px"></div>
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Tasks-->
                </div>

                <!--begin::Modal-->
                <livewire:taxpayer.add-taxpayer-modal></livewire:taxpayer.add-taxpayer-modal>
                <!--end::Modal-->

                <!--end:::Tab pane-->
                <!--begin:::Tab pane-->
                <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">
                    <!--begin::Card-->
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2>{{ __('taxpayers invoices') }}</h2>
                                <!-- <div class="fs-6 fw-semibold text-muted">{{ __('most recents payments') }}</div> -->
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Add-->
                                <!--begin::Add-->
                                <button type="button" wire:click="selectTaxpayer({{ $taxpayer->id }})" class="btn btn-light-success btn-sm"data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice">
                                    <i class="ki-duotone ki-fingerprint-scanning fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>{{ __('create invoice') }}</button>

                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer" data-kt-action="update_row">
                                        {{ __('edit') }}
                                        </a>
                                    </div>
                                <!--begin::Menu-->
                                <!-- <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-200px py-4" data-kt-menu="true"> -->
                                    <!--begin::Menu item-->
                                    <!-- <div class="menu-item px-3"> -->
                                        <!-- <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_auth_app">Use authenticator app</a> -->
                                    <!-- </div> -->
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <!-- <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_one_time_password">Enable one-time password</a>
                                    </div> -->
                                    <!--end::Menu item-->
                                <!-- </div> -->
                                <!--end::Menu-->
                                <!--end::Add-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                <input type="text" data-kt-taxpayer_invoices-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Invoice" id="mySearchInput"/>
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-taxpayer_invoices-table-toolbar="base">
                <!--begin::Add user-->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice">
                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                    {{ __('New Invoice') }}
                </button>
                <!--end::Add user-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Modal-->
            <livewire:invoice.add-invoice-modal></livewire:invoice.add-invoice-modal>
            <!--end::Modal-->
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
                        <!--end::Card body-->
                    </div>

                    

                    


                    <!--end::Card-->
                    <!--begin::Card-->
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">{{ __('taxpayers payments') }}</h2>
                                <div class="fs-6 fw-semibold text-muted">{{ __('most recents payments') }}</div>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Add-->
                                <button type="button" class="btn btn-light-success btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-brush fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>{{ __('create payment') }}</button>
                                <!--begin::Menu-->
                                <!-- <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-200px py-4" data-kt-menu="true"> -->
                                    <!--begin::Menu item-->
                                    <!-- <div class="menu-item px-3"> -->
                                        <!-- <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_auth_app">Use authenticator app</a> -->
                                    <!-- </div> -->
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <!-- <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_one_time_password">Enable one-time password</a>
                                    </div> -->
                                    <!--end::Menu item-->
                                <!-- </div> -->
                                <!--end::Menu-->
                                <!--end::Add-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-5">
                            <!--begin::Table wrapper-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-50px">{{ __('payment date') }}</th>
                                            <th class="min-w-50px">{{ __('receipt no') }}</th>
                                            <th class="min-w-50px">{{ __('invoice no') }}</th>
                                            <th class="min-w-50px">{{ __('amount') }}</th>
                                            <th class="min-w-50px">{{ __('type') }}</th>
                                            <th class="min-w-50px">{{ __('description') }}</th>
                                            <th class="min-w-50px">{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                    @foreach($taxpayer->invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $invoice->order_no}}</td>
                                            <td>{{ $invoice->invoice_no}}</td>
                                            <td>1000.00</td>
                                            
                                            <td><span class="badge badge-light-secondary">CASH</span></td>

                                            <td>PAIMENT POUR MARS</td>
                                            <td><a href="#" class="btn btn-light bnt-active-light-success btn-sm">{{ __('view') }}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table wrapper-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                
                <livewire:invoice.add-invoice-modal></livewire:invoice.add-invoice-modal>

                <!--end:::Tab pane-->
                <!--begin:::Tab pane-->
                <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
                    <!--begin::Card-->
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Login Sessions</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-flex btn-light-primary" id="kt_modal_sign_out_sesions">
                                    <i class="ki-duotone ki-entrance-right fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Sign out all sessions</button>
                                <!--end::Filter-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-5">
                            <!--begin::Table wrapper-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-100px">Location</th>
                                            <th>Device</th>
                                            <th>IP Address</th>
                                            <th class="min-w-125px">Time</th>
                                            <th class="min-w-70px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                        <tr>
                                            <td>Australia</td>
                                            <td>Chome - Windows</td>
                                            <td>207.20.21.295</td>
                                            <td>23 seconds ago</td>
                                            <td>Current session</td>
                                        </tr>
                                        <tr>
                                            <td>Australia</td>
                                            <td>Safari - iOS</td>
                                            <td>207.15.21.72</td>
                                            <td>3 days ago</td>
                                            <td>
                                                <a href="#" data-kt-users-sign-out="single_user">Sign out</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Australia</td>
                                            <td>Chrome - Windows</td>
                                            <td>207.10.28.325</td>
                                            <td>last week</td>
                                            <td>Expired</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table wrapper-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                    <!--begin::Card-->
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Logs</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Button-->
                                <button type="button" class="btn btn-sm btn-light-primary">
                                    <i class="ki-duotone ki-cloud-download fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Download Report</button>
                                <!--end::Button-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-0">
                            <!--begin::Table wrapper-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-5" id="kt_table_users_logs">
                                    <tbody>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-danger">500 ERR</div>
                                            </td>
                                            <td>POST /v1/invoice/in_6877_1633/invalid</td>
                                            <td class="pe-0 text-end min-w-200px">22 Sep 2023, 6:05 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-danger">500 ERR</div>
                                            </td>
                                            <td>POST /v1/invoice/in_6877_1633/invalid</td>
                                            <td class="pe-0 text-end min-w-200px">25 Oct 2023, 11:30 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-success">200 OK</div>
                                            </td>
                                            <td>POST /v1/invoices/in_5648_7203/payment</td>
                                            <td class="pe-0 text-end min-w-200px">15 Apr 2023, 6:43 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-danger">500 ERR</div>
                                            </td>
                                            <td>POST /v1/invoice/in_6877_1633/invalid</td>
                                            <td class="pe-0 text-end min-w-200px">25 Oct 2023, 8:43 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-success">200 OK</div>
                                            </td>
                                            <td>POST /v1/invoices/in_1431_5657/payment</td>
                                            <td class="pe-0 text-end min-w-200px">21 Feb 2023, 11:05 am</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table wrapper-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                    <!--begin::Card-->
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Events</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Button-->
                                <button type="button" class="btn btn-sm btn-light-primary">
                                    <i class="ki-duotone ki-cloud-download fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Download Report</button>
                                <!--end::Button-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-semibold gy-5" id="kt_table_customers_events">
                                <tbody>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Melody Macy</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2023, 5:30 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">Invoice
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#SEP-45656</a>status has changed from
                                            <span class="badge badge-light-warning me-1">Pending</span>to
                                            <span class="badge badge-light-info">In Progress</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 5:30 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Max Smith</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#SDK-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2023, 11:30 am</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Brian Cox</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#OLP-45690</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 11:05 am</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Melody Macy</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">20 Jun 2023, 6:43 am</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">Invoice
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a>has been
                                            <span class="badge badge-light-danger">Declined</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">25 Jul 2023, 5:30 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">Invoice
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#SEP-45656</a>status has changed from
                                            <span class="badge badge-light-warning me-1">Pending</span>to
                                            <span class="badge badge-light-info">In Progress</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">21 Feb 2023, 8:43 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">Invoice
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>status has changed from
                                            <span class="badge badge-light-info me-1">In Progress</span>to
                                            <span class="badge badge-light-primary">In Transit</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">25 Jul 2023, 10:10 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Brian Cox</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#OLP-45690</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 9:23 pm</td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Melody Macy</a>has made payment to
                                            <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">25 Oct 2023, 11:30 am</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end:::Tab pane-->
            </div>

            <!--end:::Tab content-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Layout-->
    <!--begin::Modals-->
    <!--begin::Modal - Update user details-->
    {{-- @include('pages/taxpayers/modals/_update-details') --}}
    <!--end::Modal - Update user details-->
    <!--begin::Modal - Add schedule-->
    {{-- @include('pages/taxpayers/modals/_add-schedule') --}}
    <!--end::Modal - Add schedule-->
    <!--begin::Modal - Add one time password-->
    {{-- @include('pages/taxpayers/modals/_add-one-time-password') --}}
    <!--end::Modal - Add one time password-->
    <!--begin::Modal - Update email-->
    {{-- @include('pages/taxpayers/modals/_update-email') --}}
    <!--end::Modal - Update email-->
    <!--begin::Modal - Update password-->
    {{-- @include('pages/taxpayers/modals/_update-password') --}}
    <!--end::Modal - Update password-->
    <!--begin::Modal - Update role-->
    {{-- @include('pages/taxpayers/modals/_update-role') --}}
    <!--end::Modal - Update role-->
    <!--begin::Modal - Add auth app-->
    {{-- @include('pages/taxpayers/modals/_add-auth-app') --}}
    <!--end::Modal - Add auth app-->
    <!--begin::Modal - Add task-->
    {{-- @include('pages/taxpayers/modals/_add-task') --}}
    <!--end::Modal - Add task-->
    <!--end::Modals-->

    @push('scripts')
        <script>
            var map_render = L.map('location_map').setView([8.2, 1.1], 8); // Set initial coordinates and zoom level
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map_render);
		</script>
        <script type="text/javascript">
            var taxpayer = @json($taxpayer); // Convert Laravel object to JSON

            // Log the taxpayer data to the console
            console.log('Taxpayer data:', taxpayer);

            // Check if taxpayer has latitude and longitude properties
            if (taxpayer.latitude && taxpayer.longitude) {
                // Trim spaces and convert to numeric values
                var latitude = parseFloat(taxpayer.latitude.trim());
                var longitude = parseFloat(taxpayer.longitude.trim());

                // Create a custom popup content

                    // Gender: ${taxpayer.gender}<br>
                    // ID Type: ${taxpayer.id_type}<br>
                    // ID Number: ${taxpayer.id_number}<br>
                    // Telephone: ${taxpayer.telephone}<br>

                var popupContent = `
                    <strong>${taxpayer.name}</strong><br>
                    Mobile Phone: ${taxpayer.mobilephone}<br>
                    Canton: ${taxpayer.canton}<br>
                    Town: ${taxpayer.town}<br>
                    Erea: ${taxpayer.erea}<br>
                    Address: ${taxpayer.address}
                `;

                // Log a message when adding the marker
                console.log('Adding marker for taxpayer:', taxpayer.name);

                // Add the marker to the map with a custom popup
                L.marker([latitude, longitude]).addTo(map_render)
                    .bindPopup(popupContent);
                    // Display custom popup on click

                // Animate the map to the marker's position with a specific zoom level
                map_render.flyTo([latitude, longitude], 13, {
                    duration: 8, // Animation duration in seconds
                    easeLinearity: 0.5, // Animation easing factor (0.5 for a smooth effect)
                });
            } else {
                // Log a message when there is missing or invalid latitude or longitude
                console.log('Taxpayer does not have valid latitude or longitude:', taxpayer);
            }
        </script>

        <script>
            // document.addEventListener('livewire:init', function () {
            //     Livewire.on('success', function () {
            //         $('#kt_modal_add_taxpayer_taxable').modal('hide');
            //         window.LaravelDataTables['taxpayers-table'].ajax.reload();
            //     });
            // });
        </script>
        
        {{ $dataTable->scripts() }}
        {{-- $taxablesDataTable->scripts() --}}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_invoice').modal('hide');
                    window.LaravelDataTables['taxpayer_taxables-table'].ajax.reload();
                });
            });
        </script>
        <!-- <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_invoices-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_invoice').modal('hide');
                    window.LaravelDataTables['taxpayer_invoices-table'].ajax.reload();
                });
            });
        </script> -->
    @endpush
</x-default-layout>
