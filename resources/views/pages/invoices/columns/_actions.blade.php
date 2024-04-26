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


    @if($invoice->status == App\Enums\InvoiceStatusEnums::DRAFT)
        {{-- Anything here --}}
    @elseif(
    $invoice->status !=  App\Enums\InvoiceStatusEnums::REJECTED &&
    $invoice->status != App\Enums\InvoiceStatusEnums::DRAFT)
            @php
                $data = [$invoice->uuid];
            @endphp

            <div class="menu-item px-3">
                <a href="{{route('generatePdf', ['data' => json_encode($data)]) }}" class="menu-link px-3" target="_blank">{{ __('print') }}</a>
            </div>
        @if ($invoice->status !=  App\Enums\InvoiceStatusEnums::REDUCED )
            @if ($invoice->status !==  App\Enums\InvoiceStatusEnums::CANCELED && $invoice->pay_status !=  App\Enums\PaymentStatusEnums::PAID)
                @if ($invoice->delivery_date!=null &&( $invoice->status ==  App\Enums\InvoiceStatusEnums::APPROVED || $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION) )
                        @can('peut ajouter un paiement')
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-user-id="{{ $invoice->invoice_no }}"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_add_payment" data-kt-action="update_payment">
                                    {{ __('create payment') }}
                                </a>
                            </div>
                        @endcan
                    @endif
                    @if($invoice->delivery_date!=null &&   ($invoice->validity == 'VALID' && ($invoice->status ==  App\Enums\InvoiceStatusEnums::APPROVED || $invoice->status == App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION)) )

                    @can('peut réduire ou annuler un avis')
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
