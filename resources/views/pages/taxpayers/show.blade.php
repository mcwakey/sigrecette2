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
                            @if ($taxpayer->profile_photo_url)
                                <img src="{{ $taxpayer->profile_photo_url }}" alt="image"/>
                            @else
                                <div
                                    class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxpayer->name) }}">
                                    {{ substr($taxpayer->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#"
                           class="fs-3 text-gray-800 text-hover-success fw-bold mb-3 text-uppercase ">{{ $taxpayer->name }}</a>
                        <!--end::Name-->
                        <!--begin::Position-->
                        <div class="mb-9">
                            <!--begin::Badge-->
                            <div class="badge badge-lg badge-light-danger d-inline">{{ $taxpayer->gender }}</div>
                            <!--begin::Badge-->
                        </div>
                    </div>
                    <!--end::User Info-->
                    <!--end::Summary-->
                    <!--begin::Details toggle-->
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
                             role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                            {{ __('details') }}
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>

                            @can('peut modifier un contribuable')
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                                      title="{{ __('edit Taxpayers details') }}">
                                    <a href="#" class="btn btn-sm btn-light-success"
                                       data-kt-user-id="{{ $taxpayer->id }}"
                                       data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer"
                                       data-kt-action="update_taxpayer">{{ __('edit') }}
                                    </a>
                                </span>
                            @endcan

                    </div>

                    <!--end::Details toggle-->
                    <div class="separator"></div>
                    <!--begin::Details content-->
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('account id') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->id }}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('mobilephone') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->mobilephone }} / {{ $taxpayer->telephone }}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('email') }}</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary">{{ $taxpayer->email }}</a>
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('canton') }}</div>
                            <div class="text-gray-600">
                                @if ($taxpayer->town)
                                    {{ $taxpayer->town->canton->name }}
                                @else
                                    {{ __('not filled') }}
                                @endif
                            </div>
                            <div class="fw-bold mt-5">{{ __('town') }}</div>
                            <div class="text-gray-600">
                                @if ($taxpayer->town)
                                    {{ $taxpayer->town->name }}
                                @else
                                    {{ __('not filled') }}
                                @endif
                            </div>
                            <div class="fw-bold mt-5">{{ __('erea') }}</div>
                            <div class="text-gray-600">
                                @if ($taxpayer->erea)
                                    {{ $taxpayer->erea->name }}
                                @else
                                    {{ __('not filled') }}
                                @endif
                            </div>
                            <div class="fw-bold mt-5">{{ __('address') }}</div>
                            <div class="text-gray-600">{{ $taxpayer->address }}
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('zone') }}</div>
                            <div class="text-gray-600">
                                @if ($taxpayer->zone)
                                    <span class="badge badge-light-info">{{$taxpayer->zone->name }}</span>
                                @endif
                            </div>
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
                    <a class="nav-link text-active-success pb-4 active" data-bs-toggle="tab"
                       href="#kt_user_view_overview_tab">{{ __('overview') }}</a>
                </li>
                <!--end:::Tab item-->

                <li class="nav-item">
                    <a class="nav-link text-active-success pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                       href="#kt_user_view_overview_security">{{ __('invoices payments') }}</a>
                </li>

                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                @can('view log')
                    <li class="nav-item">
                        <a class="nav-link text-active-success pb-4" data-bs-toggle="tab"
                           href="#kt_user_view_overview_events_and_logs_tab">{{ __('events logs') }}</a>
                    </li>
                @endcan
            <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item ms-auto">
                    <!--begin::Action menu-->
                    <a href="#" class="btn btn-success ps-7" data-kt-menu-trigger="click"
                       data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">{{ __('more here') }}
                        <i class="ki-duotone ki-down fs-2 me-0"></i></a>
                    <!--begin::Menu-->
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6"
                        data-kt-menu="true">
                        <div class="menu-item px-5">
                            <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">{{ __('account') }}
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--TODO Fiche du contribuable-->
                        <div class="menu-item px-5">
                            <a href="{{ route('generatePdf', ['data' => json_encode([$taxpayer->id]),'type' => '11']) }}" class="menu-link px-5">{{ __('Fiche du contribuable') }}</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('reports et stats') }}</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('statement of account') }}</a>
                        </div>
                        <!--end::Menu item-->

                        <div class="separator my-3"></div>

                        @can('peut supprimer un contribuable')
                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link text-danger px-5">{{ __('delete taxpayer') }}</a>
                            </div>
                            <!--end::Menu item-->
                        @endcan
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
                            <!-- <div class="fs-6 fw-semibold text-muted">{{ __('registered assets') }}</div> -->
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->

                            <div class="card-toolbar">
                                @can('peut créer une taxation')
                                    <button type="button" class="btn btn-light-success ms-auto me-5"
                                            data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_taxpayer_taxable"
                                            data-kt-action="add_taxpayer_taxable">
                                        <i class="ki-duotone ki-add-files fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>{{ __('create asset') }}
                                    </button>
                                @endcan

                                @can('peut émettre un avis')
                                    <button type="button" class="btn btn-light-danger ms-auto"
                                            data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_invoice" data-kt-action="add_invoice">
                                        <i class="ki-duotone ki-add-files fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>{{ __('create invoice') }}
                                    </button>
                                @endcan
                            </div>

                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card">

                            <div class="card-header border-0 pt-6">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative my-1">
                                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                        <input type="text" data-kt-taxpayer_invoices-table-filter="search"
                                               class="form-control w-250px ps-13" placeholder="{{ __('search') }}"
                                               id="mySearchInput"/>
                                    </div>
                                    <div class="d-flex align-items-center ms-5">
                                        <a href="#" id="kt_horizontal_search_advanced_link"
                                           data-kt-rotate="true"
                                           class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                                           data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                            {{ __('advanced search') }} <i
                                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    </div>
                                    <!--end::Search-->
                                </div>


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
                                                <div class="col-xxl-3">
                                                    <label
                                                        class="fs-6 form-label fw-bold text-dark">{{ __('asset name') }}</label>
                                                    <input type="text" class="form-control" name="tags"
                                                           id="mySearchOne"/>
                                                </div>
                                                <!--begin::Col-->
                                                <div class="col-xxl-3">
                                                    <label
                                                        class="fs-6 form-label fw-bold text-dark">{{ __('taxlabel') }}</label>
                                                    <input type="text" class="form-control" name="tags"
                                                           id="mySearchTwo"/>
                                                </div>
                                                <!--begin::Col-->
                                                <div class="col-xxl-3">
                                                    <label
                                                        class="fs-6 form-label fw-bold text-dark">{{ __('taxable') }}</label>
                                                    <input type="text" class="form-control" name="tags"
                                                           id="mySearchThree"/>
                                                </div>


                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col-xxl-3">
                                                    <label
                                                        class="fs-6 form-label fw-bold text-dark">{{ __('status') }}</label>
                                                    <!-- <input type="text" class="form-control" name="tags" /> -->
                                                    <!--begin::Select-->
                                                    <select class="form-select" id="mySearchFour">
                                                        <option value=""></option>
                                                        <option value="BILLED">{{ __('BILLED') }}</option>
                                                        <option value="NOT BILLED">{{ __('NOT BILLED') }}</option>
                                                    </select>
                                                    <!--end::Select-->
                                                </div>
                                                <!-- </div> -->
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->

                                            <div class="separator separator-dashed mt-5 mb-5"></div>
                                        </div>

                                    </form>

                                </div>
                                <!--begin::Card title-->
                            </div>

                            <div class="card-body py-4">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>

                    <!--end::Card-->
                    <!--begin::Tasks-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">{{ __('taxpayers geolocation') }}</h2>
                                <div class="fs-6 fw-semibold text-muted">Long: {{ $taxpayer->longitude }} Lat:
                                    {{ $taxpayer->latitude }}</div>
                            </div>
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
                                        <input type="text" data-kt-taxpayer_invoices-table-filter="search"
                                               class="form-control form-control-solid w-250px ps-13"
                                               placeholder="Search Invoice" id="mySearchInput"/>
                                    </div>
                                    <!--end::Search-->
                                </div>

                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->

                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                           id="kt_table_taxpayer_invoices">
                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-50px">{{ __('invoice date') }}</th>
                                            <th class="min-w-50px">{{ __('invoice no') }}</th>
                                            <th class="min-w-50px">{{ __('order no') }}</th>
                                            <th class="min-w-50px">{{ __('nic') }}</th>
                                            <th class="min-w-50px">{{ __('amount') }}</th>
                                            <th class="min-w-50px">{{ __('status') }}</th>
                                            <th class="min-w-50px">{{ __('delivery') }}</th>
                                            <th class="min-w-50px">{{ __('delivery date') }}</th>
                                            <th class="min-w-50px">{{ __('aproval') }}</th>
                                            <th class="min-w-50px">{{ __('actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                        @foreach ($taxpayer->invoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $invoice->invoice_no }}</td>
                                                <td>
                                                    @if ( $invoice->order_no == null && $invoice->delivery == 'NOT DELIVERED' &&  $invoice->status!="DRAFT" )
                                                        @can('peut ajouter le numéro d\'ordre de recette d\'un avis')
                                                        <button type="button"
                                                                class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                                data-kt-user-id="{{ $invoice->id }}"
                                                                data-bs-target="#kt_modal_add_orderno"
                                                                data-kt-menu-trigger="click"
                                                                data-kt-menu-placement="bottom-end"
                                                                data-kt-action="update_invoice">

                                                            <i class="ki-duotone ki-pencil fs-3">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                        @endcan

                                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                                             data-kt-menu="true"
                                                             data-kt-menu-id="kt_modal_add_orderno" tabindex="-1"
                                                             aria-hidden="true" wire:ignore.self>
                                                            <div class="px-7 py-5">
                                                                <div class="fs-5 text-gray-900 fw-bold">
                                                                    Metre a
                                                                    jour le No d'ordre
                                                                </div>
                                                            </div>
                                                            <div class="separator border-gray-200"></div>
                                                            <livewire:invoice.add-orderno-form/>
                                                        </div>
                                                    @else
                                                        {{ $invoice->order_no }}

                                                    @endif


                                                </td>
                                                <td>{{ $invoice->nic }}</td>
                                                <td>
                                                    @if ($invoice->reduce_amount != '')
                                                        {{ '-' . $invoice->reduce_amount }}
                                                    @else
                                                        {{ $invoice->amount }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($invoice->status == 'APROVED' || $invoice->status =='APROVED-CANCELLATION')
                                                        @if ($invoice->pay_status == 'OWING')
                                                            <span
                                                                class="badge badge-light-danger">{{ __($invoice->pay_status) }}</span>
                                                        @elseif($invoice->pay_status == 'PART PAID')
                                                            <span
                                                                class="badge badge-light-warning">{{ __($invoice->pay_status) }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-light-success">{{ __($invoice->pay_status) }}</span>
                                                        @endif
                                                    @elseif($invoice->status == 'CANCELED' || $invoice->status =='REDUCED')
                                                        <span
                                                            class="badge badge-light-warning">{{ "----" }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-primary">{{ __('EN ATTENTE DE VALIDATION') }}</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($invoice->delivery == 'NOT DELIVERED')
                                                        <span
                                                            class="badge badge-light-danger">{{ __('NOT DELIVERED') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-success">{{ __('DELIVERED') }}</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if( $invoice->status != 'REJECTED' && $invoice->status != 'PENDING'&& $invoice->status != 'DRAFT')
                                                        @if ($invoice->delivery == 'NOT DELIVERED'&& $invoice->order_no !== null)
                                                            @can('peut ajouter la date de livraison d\'un avis')
                                                                <button type="button"
                                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                                        data-kt-user-id="{{ $invoice->id }}"
                                                                        data-kt-menu-target="#kt_modal_add_delivery"
                                                                        data-kt-menu-trigger="click"
                                                                        data-kt-menu-placement="bottom-end"
                                                                        data-kt-action="update_status">
                                                                    <i class="ki-duotone ki-setting-3 fs-3">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                        <span class="path4"></span>
                                                                        <span class="path5"></span>
                                                                    </i>
                                                                </button>
                                                                <div
                                                                    class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                                                    data-kt-menu="true"
                                                                    data-kt-menu-id="kt_modal_add_delivery">
                                                                    <!--begin::Header-->
                                                                    <div class="px-7 py-5">
                                                                        <div class="fs-5 text-gray-900 fw-bold">
                                                                            Mettre a jour la livraison
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Header-->
                                                                    <!--begin::Menu separator-->
                                                                    <div class="separator border-gray-200"></div>
                                                                    <!--end::Menu separator-->
                                                                    <!--begin::Form-->
                                                                    <livewire:invoice.add-delivery-form/>

                                                                    <!--end::Form-->
                                                                </div>
                                                                <!--end::Task menu-->
                                                            @else
                                                                {{ __('NOT DELIVERED') }}
                                                            @endcan
                                                        @elseif($invoice->delivery == 'DELIVERED')
                                                            {{ date('Y-m-d', strtotime($invoice->delivery_date)) }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>


                                                <td>
                                                    @if ($invoice->status == 'PENDING' && $invoice->order_no !== null)
                                                        <span
                                                            class="badge badge-light-primary">{{ __($invoice->status) }}</span>
                                                        @can('peut prendre en charge un avis')
                                                            <button type="button"
                                                                    class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                                    data-kt-user-id="{{ $invoice->id }}"
                                                                    data-kt-menu-target="#kt_modal_add_status"
                                                                    data-kt-menu-trigger="click"
                                                                    data-kt-menu-placement="bottom-end"
                                                                    data-kt-action="update_status">
                                                                <i class="ki-duotone ki-setting-3 fs-3">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        @endcan
                                                    @elseif($invoice->status == 'APROVED' || $invoice->status == 'APROVED-CANCELLATION')
                                                        <span
                                                            class="badge badge-light-success">{{ __('APROVED') }}</span>
                                                    @elseif($invoice->status == 'REJECTED')
                                                        <span
                                                            class="badge badge-light-danger">{{ __('REJECTED') }}</span>
                                                    @elseif($invoice->status == 'DRAFT')
                                                        <span
                                                            class="badge badge-light-secondary">{{ __('DRAFT') }}</span>

                                                        @can('peut accepter un avis')
                                                            <button type="button"
                                                                    class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                                    data-kt-user-id="{{ $invoice->id }}"
                                                                    data-kt-menu-target="#kt_modal_add_status"
                                                                    data-kt-menu-trigger="click"
                                                                    data-kt-menu-placement="bottom-end"
                                                                    data-kt-action="update_status">
                                                                <!-- <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-target="#kt-users-tasks" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"> -->
                                                                <i class="ki-duotone ki-setting-3 fs-3">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        @endcan
                                                    @else
                                                        <span
                                                            class="badge badge-light-info">{{ __($invoice->status) }}</span>
                                                @endif


                                                <!--begin::Task menu-->
                                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                                         data-kt-menu="true" data-kt-menu-id="kt_modal_add_status">
                                                        <!--begin::Header-->
                                                        <div class="px-7 py-5">
                                                            <div class="fs-5 text-gray-900 fw-bold">Metre a jour le
                                                                status
                                                            </div>
                                                        </div>
                                                        <!--end::Header-->
                                                        <!--begin::Menu separator-->
                                                        <div class="separator border-gray-200"></div>
                                                        <!--end::Menu separator-->
                                                        <!--begin::Form-->
                                                        <livewire:invoice.add-status-form/>

                                                        <!--end::Form-->
                                                    </div>
                                                    <!--end::Task menu-->

                                                </td>
                                                <!--end::Menu-->

                                                <td>

                                                    <a href="#"
                                                       class="btn btn-light btn-active-light-success btn-flex btn-center btn-sm"
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
                                                            <a href="#" class="menu-link px-3"
                                                               data-kt-user-id="{{ $invoice->id }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#kt_modal_add_invoice"
                                                               data-kt-action="view_invoice">
                                                                {{ __('view') }}
                                                            </a>
                                                        </div>


                                                        @if ($invoice->status == 'DRAFT')

                                                            {{-- Nothing here --}}
                                                        @elseif(
                                                            $invoice->status == 'APROVED' ||
                                                             $invoice->status == 'APROVED-CANCELLATION' ||
                                                                $invoice->status == 'PENDING' ||
                                                                $invoice->status == 'REDUCED' ||
                                                                $invoice->status == 'CANCELED')
                                                            <div class="menu-item px-3">


                                                                @php
                                                                    $data = [$invoice->uuid];
                                                                @endphp

                                                                    <a href="{{ route('generatePdf', ['data' => json_encode($data)]) }}"
                                                                       class="menu-link px-3"
                                                                       target="_blank">{{ __('print') }}</a>

                                                            </div>
                                                            @if ($invoice->status != 'REDUCED' )
                                                                @if ($invoice->status !== 'CANCELED' && $invoice->pay_status != 'PAID'&& $invoice->delivery_date!=null)
                                                                    @if ( $invoice->status == 'APROVED' || $invoice->status =='APROVED-CANCELLATION')
                                                                        @can('peut ajouter un paiement')
                                                                            <div class="menu-item px-3">
                                                                                <a href="#"
                                                                                   class="menu-link px-3"
                                                                                   data-kt-user-id="{{ $invoice->invoice_no }}"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#kt_modal_add_payment"
                                                                                   data-kt-action="update_payment">
                                                                                    {{ __('create payment') }}
                                                                                </a>
                                                                            </div>
                                                                    @endcan
                                                                @endif
                                                                @if ($invoice->validity == 'VALID' && ($invoice->status == 'APROVED' || $invoice->status =='APROVED-CANCELLATION') )

                                                                    @can('peut réduire ou annuler un avis')
                                                                        <!--begin::Menu item-->
                                                                            <div class="menu-item px-3">
                                                                                <a href="#" class="menu-link px-3"
                                                                                   data-kt-user-id="{{ $invoice->id }}"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#kt_modal_add_invoice"
                                                                                   data-kt-action="update_invoice">
                                                                                    {{ __('reduction cancelation') }}
                                                                                </a>
                                                                            </div>
                                                                    @endcan
                                                                @endif
                                                            @endif
                                                        @endif

                                                    @endif
                                                    <!--end::Menu item-->

                                                        <!--begin::Menu item-->
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu item-->
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>

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
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">

                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-5">
                            <!--begin::Table wrapper-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed gy-5" id="payment-table">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                    <tr class="text-start text-muted text-uppercase gs-0">
                                        <th class="min-w-50px">{{ __('payment date') }}</th>
                                        <th class="min-w-50px">{{ __('invoice no') }}</th>
                                        <th class="min-w-50px">{{ __('reference no') }}</th>
                                        <th class="min-w-50px">{{ __("code d'imputation") }}</th>
                                        <th class="min-w-50px">{{ __('amount') }}</th>
                                        <th class="min-w-50px">{{ __('type') }}</th>
                                        <th class="min-w-50px">{{ __('description') }}</th>
                                        <th class="min-w-50px">{{ __('aproval') }}</th>
                                        <th class="min-w-50px">{{ __('actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                    @foreach ($taxpayer->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $payment->invoice->invoice_no }}</td>
                                            <td>{{ $payment->reference }}</td>
                                            <td>{{ $payment->code }}</td>
                                            <td>


                                                {{ $payment->amount }}
                                            </td>

                                            <td><span
                                                    class="badge badge-light-secondary">{{ $payment->payment_type }}</span>
                                            </td>


                                            <td>{{ $payment->description }}</td>
                                            <td>
                                                @if ($payment->status == 'PENDING' )
                                                    <span
                                                        class="badge badge-light-primary">{{ __($payment->status) }}</span>
                                                    @can('peut prendre en charge un paiement')
                                                        <button type="button"
                                                                class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                                data-kt-user-id="{{ $payment->id }}"
                                                                data-kt-menu-target="#kt_payment_modal_add_status"
                                                                data-kt-menu-trigger="click"
                                                                data-kt-menu-placement="bottom-end"
                                                                data-kt-action="update_payment_status">
                                                            <i class="ki-duotone ki-setting-3 fs-3">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                                <span class="path4"></span>
                                                                <span class="path5"></span>
                                                            </i>
                                                        </button>
                                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                                             data-kt-menu="true"
                                                             data-kt-menu-id="#kt_payment_modal_add_status">
                                                            <div class="px-7 py-5">
                                                                <div class="fs-5 text-gray-900 fw-bold">Metre à jour le
                                                                    status
                                                                </div>
                                                            </div>
                                                            <!--end::Header-->
                                                            <!--begin::Menu separator-->
                                                            <div class="separator border-gray-200"></div>
                                                            <!--end::Menu separator-->
                                                            <!--begin::Form-->
                                                            <livewire:payment.add-status-form/>

                                                            <!--end::Form-->
                                                        </div>
                                                    @endcan
                                                @elseif($payment->status == 'APROVED')
                                                    <span
                                                        class="badge badge-light-success">{{ __('APROVED') }}</span>
                                            @endif


                                            <!--begin::Task menu-->

                                                <!--end::Task menu-->

                                            </td>
                                            <td><a href="#"
                                                   class="btn btn-light bnt-active-light-success btn-sm">{{ __('view') }}</a>
                                            </td>
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
                                <button type="button" class="btn btn-sm btn-flex btn-light-primary"
                                        id="kt_modal_sign_out_sesions">
                                    <i class="ki-duotone ki-entrance-right fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Sign out all sessions
                                </button>
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
                                <table class="table align-middle table-row-dashed gy-5"
                                       id="kt_table_users_login_session">
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
                                    </i>Download Report
                                </button>
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
                                <table class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-5"
                                       id="kt_table_users_logs">
                                    <tbody>
                                    @foreach ($taxpayerActionLog as $action)
                                        <tr>
                                            <td class="min-w-70px">
                                                <div
                                                    class="badge {{ (int) json_decode($action->response)->status <= 300 ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ json_decode($action->response)->status }}
                                                    {{ json_decode($action->response)->status_text }}
                                                    {{ ' : ' . $action->user->name }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ json_decode($action->request)->method }}
                                                {{ json_decode($action->request)->path_info }}
                                                {{ $action->taxpayer ? ' : ' . $action->taxpayer->name : '' }}
                                            </td>
                                            <td class="pe-0 text-end min-w-200px">
                                                {{ $action->created_at }}
                                            </td>
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
                                    </i>Download Report
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-semibold gy-5"
                                   id="kt_table_customers_events">
                                <tbody>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Melody
                                            Macy</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2023, 5:30 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">Invoice
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary me-1">#SEP-45656</a>status
                                        has changed from
                                        <span class="badge badge-light-warning me-1">Pending</span>to
                                        <span class="badge badge-light-info">In Progress</span>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 5:30 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Max
                                            Smith</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#SDK-45670</a>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2023, 11:30 am</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Brian
                                            Cox</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#OLP-45690</a>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 11:05 am</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Melody
                                            Macy</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">20 Jun 2023, 6:43 am</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">Invoice
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a>has
                                        been
                                        <span class="badge badge-light-danger">Declined</span>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">25 Jul 2023, 5:30 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">Invoice
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary me-1">#SEP-45656</a>status
                                        has changed from
                                        <span class="badge badge-light-warning me-1">Pending</span>to
                                        <span class="badge badge-light-info">In Progress</span>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">21 Feb 2023, 8:43 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">Invoice
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>status
                                        has changed from
                                        <span class="badge badge-light-info me-1">In Progress</span>to
                                        <span class="badge badge-light-primary">In Transit</span>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">25 Jul 2023, 10:10 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Brian
                                            Cox</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#OLP-45690</a>
                                    </td>
                                    <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2023, 9:23 pm</td>
                                </tr>
                                <tr>
                                    <td class="min-w-400px">
                                        <a href="#" class="text-gray-600 text-hover-primary me-1">Melody
                                            Macy</a>has made payment to
                                        <a href="#"
                                           class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
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

        <style>
            .legend {
                width: 320px;
                background: white;
                padding: 10px 12px;
                border-radius: 6px;
            }

            .legend .title {
                font-size: 18px;
                font-weight: 500;
                display: block;
                margin-bottom: -16px;
            }

            .legend .detail {
                margin-left: 5px;
                margin-bottom: -10px;
                display: flex;
                align-items: center;
            }

            .legend .detail:last-child {
                margin-bottom: 0px;
            }

            .legend .text {
                font-size: 16px;
                font-weight: 500;
            }

            .legend .img {
                margin-right: 4px;
                min-width: 20px;
            }
        </style>

        <!--end::Content-->
    </div>

    <livewire:taxpayer_taxable.add-taxpayer-taxable-modal/>

    <!--begin::Modal-->
    <livewire:payment.add-payment-modal/>
    <!--end::Modal-->

    <!--begin::Modal-->
    <livewire:taxpayer.add-taxpayer-modal/>
    <!--end::Modal-->

    <!--begin::Modal-->
    <livewire:invoice.add-invoice-modal/>
    <!--end::Modal-->

    <!--begin::Modal-->
    <!--end::Modal-->

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


            let legend = L.control({
                position: 'bottomright'
            });

        </script>

        <script type="text/javascript">
         const getTaxpayerIconUrl = (icon) => `http://127.0.0.1:8000/assets/media/icons/${icon}`;

        let taxpayerGreen = L.icon({
            iconUrl: getTaxpayerIconUrl('taxpayer-green.svg'),
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let taxpayerOrange = L.icon({
            iconUrl: getTaxpayerIconUrl('taxpayer-orange.svg'),
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let taxpayerBlue = L.icon({
            iconUrl: getTaxpayerIconUrl('taxpayer-blue.svg'),
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let taxpayerRed = L.icon({
            iconUrl: getTaxpayerIconUrl('taxpayer-red.svg'),
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

            let taxpayer = @json($taxpayer); // Convert Laravel object to JSON

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
                <div style="width:480px;min-height:200px;border-radius:8px;">
                                <div style="padding:10px;text-align:center;display:flex;align-items:flex-start;flex-direction:column;">

                                    <div style="margin-bottom:6px;display:flex;justify-content:space-between;width:100%;align-items:center;">
                                        <h2 class="text-dark">Informations du contribuable</h2 class="text-dark">
                                        <a class="badge pt-2 pb-2 bg-secondary" href="/taxpayers/${taxpayer.id}" class="">Afficher</a>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Nom complet </span>
                                        <span style="font-size:15px"> : ${taxpayer.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Téléphone </span>
                                        <span style="font-size:15px"> : ${taxpayer.mobilephone}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Adresse </span>
                                        <span style="font-size:15px"> : ${taxpayer.address}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Ville</span>
                                        <span style="font-size:15px">: ${taxpayer.town.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Canton</span>
                                        <span style="font-size:15px">: ${taxpayer.town.canton.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Zone</span>
                                        <span style="font-size:15px"> : ${taxpayer.zone.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Quartié</span>
                                        <span style="font-size:15px"> : ${taxpayer.erea ? taxpayer.erea.name : '---'}</span>
                                    </div>
                                </div>
                            </div>`;


                if (taxpayer.invoices.length) {
                    let {
                        invoices
                    } = taxpayer;
                    let icon = null;

                    invoices.forEach(invoice => {
                        if (invoice.pay_status == 'OWING') {
                            icon = taxpayerRed;
                            return;
                        } else if (invoice.pay_status == 'PART PAID') {
                            icon = taxpayerOrange;
                        } else {
                            icon = taxpayerGreen;
                        }

                    });

                    L.marker([latitude, longitude], {
                        icon: icon
                    }).addTo(map_render)
                        .bindPopup(popupContent);
                } else {
                    L.marker([latitude, longitude], {
                        icon: taxpayerBlue
                    }).addTo(map_render)
                        .bindPopup(popupContent);
                }

// Animate the map to the marker's position with a specific zoom level
                map_render.flyTo([latitude, longitude], 13, {
                    duration: 8, // Animation duration in seconds
                    easeLinearity: 0.5, // Animation easing factor (0.5 for a smooth effect)
                });
            } else {

            }


            legend.onAdd = function(map) {
                let div = L.DomUtil.create('div', 'info legend');
                let labels = [
                    '<div class="legend"><strong class="title">Légende : contribuable</strong><div class="hr"></div></div>'
                ];
                let status = ['OWING', 'PART PAID', 'PAID', null];


                for (let i = 0; i < status.length; i++) {
                    if (status[i] == 'OWING') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-red.svg')}"/> <span class="text">Facturé et Non payé</span></div>`
                        );
                    } else if (status[i] == 'PART PAID') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-orange.svg')}"/> <span class="text">Facturé et Partiellement payé</span></div>`
                        );
                    } else if (status[i] == 'PAID') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-green.svg')}"/> <span class="text">Facturé et Payé</span></div>`
                        );
                    } else if (status[i] == null) {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-blue.svg')}"/> <span class="text">Non Facturé</span></div>`
                        );
                    }
                }

                div.innerHTML = labels.join('<br>');
                return div;

            };

            legend.addTo(map_render);
        </script>
        <script type="text/javascript">
            document.querySelectorAll('[data-kt-action="update_payment_status"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Livewire.dispatch('update_payment_status', [this.getAttribute('data-kt-user-id')]);
                });
            });
            var taxpayer_taxables = @json($taxpayer->taxpayer_taxables); // Convert Laravel collection to JSON
            // Check if taxpayer_taxables is not empty
            if (taxpayer_taxables.length > 0) {
// Loop through each taxpayer_taxable item
                taxpayer_taxables.forEach(function (taxpayer_taxable) {
// Check if taxpayer_taxable has latitude and longitude properties
                    if (taxpayer_taxable.latitude && taxpayer_taxable.longitude) {
// Trim spaces and convert to numeric values
                        var latitude = parseFloat(taxpayer_taxable.latitude.trim());
                        var longitude = parseFloat(taxpayer_taxable.longitude.trim());

// Create a custom popup content
                        var popupContent = `
<strong>${taxpayer_taxable.name}</strong><br>
Mobile Phone: ${taxpayer_taxable.seize}<br>
Address: ${taxpayer_taxable.location}
`;

// Log a message when adding the marker
                        console.log('Adding marker for taxpayer_taxable:', taxpayer_taxable.name);

// Add the marker to the map with a custom red icon and popup
                        if (taxpayer_taxable.invoice_id == null) {
                            L.marker([latitude, longitude], {
                                icon: redIcon
                            }).addTo(map_render)
                                .bindPopup(popupContent);
                        } else {
                            L.marker([latitude, longitude], {
                                icon: greenIcon
                            }).addTo(map_render)
                                .bindPopup(popupContent);
                        }

                    } else {
// Log a message when there is missing or invalid latitude or longitude
                        console.log('taxpayer_taxable does not have valid latitude or longitude:', taxpayer_taxable);
                    }
                });
            } else {
// Log a message when taxpayer_taxables is empty
                console.log('Taxpayer Taxables data is empty.');
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

        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].search(this.value).draw();
            });

            // document.getElementById('mySearchZero').addEventListener('keyup', function() {
            //     window.LaravelDataTables['taxpayer_taxables-table'].column(0).search(this.value).draw();
            // });

            document.getElementById('mySearchOne').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].column(1).search(this.value).draw();
            });

            document.getElementById('mySearchTwo').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].column(2).search(this.value).draw();
            });

            document.getElementById('mySearchThree').addEventListener('keyup', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].column(3).search(this.value).draw();
            });

            document.getElementById('mySearchFour').addEventListener('change', function () {
                window.LaravelDataTables['taxpayer_taxables-table'].column(5).search(this.value).draw();
            });


            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_invoice').modal('hide');
                    $('#kt_modal_add_taxpayer_taxable').modal('hide');
                    $('#kt_modal_add_payment').modal('hide');
// $('#kt_modal_add_delivery').menu('hide');
// $('#kt_modal_add_orderno').menu('hide');


                    window.LaravelDataTables['taxpayer_taxables-table'].ajax.reload();
// window.getElementById('#payment-table').ajax.reload();
                    window.location.reload();
                });
            });
            // document.addEventListener('livewire:init', function() {
            //     Livewire.on('success', function() {
            //         window.LaravelDataTables['taxpayer_taxables-table'].ajax.reload();
            //     });
            // });

        </script>

        <!-- <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
        window.LaravelDataTables['taxpayer_invoices-table'].search(this.value).draw();
        });
        document.addEventListener('livewire:init', function() {
        Livewire.on('success', function() {
        $('#kt_modal_add_invoice').modal('hide');
        window.LaravelDataTables['taxpayer_invoices-table'].ajax.reload();
        });
        });
        </script> -->
    @endpush
</x-default-layout>
