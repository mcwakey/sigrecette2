@php
    $actions = App\Helpers\Constants::getInvoiceActionsBasedOnRouteNameAndStatut();
@endphp
@if (in_array(App\Enums\InvoiceActionsEnums::ADDORNO,$actions))
    @if($invoice->type==App\Helpers\Constants::INVOICE_TYPE_COMPTANT || $invoice->can("submit_for_pending"))
        @if($invoice->order_no==null)
        @can('peut ajouter le numéro d\'ordre de recette d\'un avis')
            <button type="button"
                    class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto pulse pulse-warning"
                    data-kt-user-id="{{ $invoice->id }}"
                    data-bs-target="#kt_modal_add_orderno"
                    data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end"
                    data-kt-action="update_invoice">

                <i class="ki-duotone ki-pencil fs-3">
                    <span class="path1"></span>
                    <span class="path2"></span>

                </i>
                <span class="pulse-ring"></span>

            </button>
        @endcan
        @endif
    @endif


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
@endif
@if($invoice->order_no)
    {{ $invoice->order_no }}
@endif
