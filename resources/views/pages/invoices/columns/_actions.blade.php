<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>

<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
    data-kt-menu="true" data-kt-menu-id="#kt-users-actions">
    <!--begin::Menu item-->

    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-user-id="{{ $invoice->id }}" data-bs-toggle="modal"
            data-bs-target="#kt_modal_add_invoice" data-kt-action="view_invoice">
            {{ __('view') }}
        </a>
    </div>


    @if($invoice->status == 'DRAFT')
        {{-- Anything here --}}
    @elseif($invoice->status == 'APROVED' || $invoice->status == 'CANCELED')
        @can('print invoice')
            @php

                $invoiceItems = [];
                foreach (
                    $invoice->invoiceitems
                    as $invoiceitem
                ) {
                    $invoiceItems[] = [
                        $invoiceitem->taxpayer_taxable->taxable
                            ->tax_label->name,
                        $invoiceitem->taxpayer_taxable->taxable
                            ->tax_label->code,
                        $invoiceitem->taxpayer_taxable->taxable
                            ->name,
                        $invoiceitem->ii_seize,
                        $invoiceitem->taxpayer_taxable->taxable
                            ->unit,
                        $invoiceitem->ii_tariff,
                        $invoiceitem->amount,
                        $invoiceitem->qty,
                        $invoiceitem->taxpayer_taxable->name,
                    ];
                }
                $data = [
                    $invoice->from_date,
                    $invoice->invoice_no,
                    $invoice->nic,
                    $invoice->amount,
                    $invoice->taxpayer ? $invoice->taxpayer->name : '---',
                    $invoice->taxpayer ? $invoice->taxpayer->mobilephone : '---',
                    $invoice->taxpayer && $invoice->taxpayer->town->canton ? $invoice->taxpayer->town->canton->name : '---',
                    $invoice->taxpayer && $invoice->taxpayer->town ? $invoice->taxpayer->town->name : '---',
                    $invoice->taxpayer ? $invoice->taxpayer->address : '---',
                     $invoice->taxpayer && $invoice->taxpayer->zone ? $invoice->taxpayer->zone->name : '---',
                    $invoice->taxpayer ? $invoice->taxpayer->longitude : '---',
                     $invoice->taxpayer ? $invoice->taxpayer->latitude : '---',
                    $invoiceItems,
                    $invoice->id,
                ];
            @endphp

            <div class="menu-item px-3">
                <a href="{{route('generatePdf', ['data' => json_encode($data)]) }}" class="menu-link px-3" target="_blank">{{ __('print') }}</a>
            </div>
        @endcan
        @if ($invoice->status != 'REDUCED')
                @if ($invoice->status != 'CANCELED' && $invoice->pay_status != 'PAID')
                    @if ( $invoice->status == 'APROVED')
                        @can('create invoice payment')
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-user-id="{{ $invoice->invoice_no }}"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_add_payment" data-kt-action="update_payment">
                                    {{ __('create payment') }}
                                </a>
                            </div>
                        @endcan
                    @endif
                    @if ($invoice->validity == 'VALID')
                            @can('reduce invoice amount')
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{ $invoice->id }}"
                                       data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice" data-kt-action="update_invoice">
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
    <!--end::Menu-->
</div>
<!--end::Menu-->
