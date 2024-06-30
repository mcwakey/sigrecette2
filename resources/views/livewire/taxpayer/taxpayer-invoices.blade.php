<div class="card pt-4 mb-6 mb-xl-9">
    <div class="card-header border-0">
        <div class="card-title flex-column">
            <h2>{{ __('taxpayers invoices') }}</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-taxpayer_invoices-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search Invoice" id="mySearchInput" />
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
                            <td>{{ $invoice->getCreatedDate() }}</td>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>
                                @if ( $invoice->can("submit_for_pending"))
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
                                        <livewire:invoice.add-orderno-form />
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
                                @if (
                                    $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED ||
                                        $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION)
                                    @if ($invoice->pay_status == App\Enums\InvoicePayStatusEnums::OWING)
                                        <span
                                            class="badge badge-light-info">{{ __($invoice->pay_status) }}</span>
                                    @elseif($invoice->pay_status == App\Enums\InvoicePayStatusEnums::PART_PAID)
                                        <span
                                            class="badge badge-light-warning">{{ __($invoice->pay_status) }}</span>
                                    @else
                                        <span
                                            class="badge badge-light-success">{{ __($invoice->pay_status) }}</span>
                                    @endif
                                @elseif(
                                    $invoice->status == App\Enums\InvoiceStatusEnums::CANCELED ||
                                        $invoice->status == App\Enums\InvoiceStatusEnums::REDUCED ||
                                        $invoice->status == App\Enums\InvoiceStatusEnums::REJECTED)
                                    <span
                                        class="badge badge-light-warning">{{ '----' }}</span>
                                @else
                                    <span
                                        class="badge badge-light-primary">{{ __('EN ATTENTE DE VALIDATION') }}</span>
                                @endif

                            </td>
                            <td>
                                @if ($invoice->delivery_date==null)
                                    <span
                                        class="badge badge-light-danger">{{ __('NOT DELIVERED') }}</span>
                                @else
                                    <span
                                        class="badge badge-light-success">{{ __('DELIVERED') }}</span>
                                @endif
                            </td>

                            <td>
                                @if ($invoice->can("submit_for_reduced"))
                                    @if ( $invoice->delivery_date == null && $invoice->order_no !== null)
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
                                            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
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
                                                <livewire:invoice.add-delivery-form />

                                                <!--end::Form-->
                                            </div>
                                            <!--end::Task menu-->
                                        @else
                                            {{ __('NOT DELIVERED') }}
                                        @endcan
                                    @elseif($invoice->delivery == App\Enums\InvoiceDeliveryEnums::DELIVERED)
                                        {{ date('Y-m-d', strtotime($invoice->delivery_date)) }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>


                            <td>
                                @if ($invoice->can("submit_for_approved"))
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
                                @elseif(
                                    $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED ||
                                        $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION)
                                    <span
                                        class="badge badge-light-success">{{ __(App\Enums\InvoiceStatusEnums::APPROVED) }}</span>
                                @elseif($invoice->status == App\Enums\InvoiceStatusEnums::REJECTED)
                                    <span
                                        class="badge badge-light-danger">{{ __('REJECTED') }}</span>
                                @elseif($invoice->can( "submit_for_accepted"))
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
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                     data-kt-menu="true" data-kt-menu-id="kt_modal_add_status">
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-gray-900 fw-bold">Metre a jour le
                                            status
                                        </div>
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <livewire:invoice.add-status-form />
                                </div>

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
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
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
                                    @if($invoice->canPrint())
                                        <div class="menu-item px-3">
                                            @php
                                                $data = [$invoice->uuid];
                                            @endphp

                                            <a href="{{ route('generatePdf', ['data' => json_encode($data)]) }}"
                                               class="menu-link px-3"
                                               target="_blank">{{ __('print') }}</a>
                                        </div>

                                    @endif
                                    @if( $invoice->can( "submit_for_reduced") ||  $invoice->can("submit_for_canceled") && $invoice->validity == 'VALID')
                                        @if ( $invoice->canGetPayment())
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
                                    @can('peut réduire un avis sur titre')
                                            <div class="menu-item px-3">
                                                <a href="#"
                                                   class="menu-link px-3"
                                                   data-kt-user-id="{{ $invoice->id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#kt_modal_add_invoice"
                                                   data-kt-action="update_invoice">
                                                    {{ __('reduction cancelation') }}
                                                </a>
                                            </div>
                                    @endcan
                                @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
