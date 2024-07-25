<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>

<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
    data-kt-menu="true" data-kt-menu-id="#kt-users-actions">

    @php
    $actions = App\Helpers\Constants::getInvoiceActionsBasedOnRouteNameAndStatut();
    @endphp
@if(in_array(App\Enums\InvoiceActionsEnums::VIEW,$actions))
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $invoice->id }}" data-bs-toggle="modal"
               data-bs-target="#kt_modal_add_invoice" data-kt-action="view_invoice">
                {{ __('view') }}
            </a>
        </div>
    @endif
    @if(in_array(App\Enums\InvoiceActionsEnums::PRINT,$actions))
        @if($invoice->canPrint())
            @php
                $data = [$invoice->uuid];
            @endphp

            <div class="menu-item px-3">
                <a href="{{route('generatePdf', ['data' => json_encode($data)]) }}" class="menu-link px-3" target="_blank">{{ __('print') }}</a>
            </div>
        @endif
    @endif


    @if(in_array(App\Enums\InvoiceActionsEnums::REDUCE,$actions))
        @if( $invoice->can( "submit_for_reduced") ||  $invoice->can("submit_for_canceled") && $invoice->validity == 'VALID')
            @if($invoice->type ==  App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY)
                @can('peut réduire un avis au comptant')
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{ $invoice->id }}"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice" data-kt-action="update_invoice">
                            {{ __('reduction cancelation') }}
                        </a>
                    </div>
                @endcan
            @else
                @can('peut réduire un avis sur titre')
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
    @if( in_array(App\Enums\InvoiceActionsEnums::PAYMENT,$actions))
        @if( $invoice->can( "submit_for_reduced") ||  $invoice->can("submit_for_canceled") && $invoice->validity == 'VALID')
            @if( $invoice->canGetPayment())

                @can('peut ajouter un paiement')
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-user-id="{{ $invoice->invoice_no }}"
                           data-bs-toggle="modal" data-bs-target="#kt_modal_add_payment" data-kt-action="update_payment">
                            {{ __('create payment') }}
                        </a>
                    </div>
                @endcan
            @endif

        @endif

    @endif
</div>
