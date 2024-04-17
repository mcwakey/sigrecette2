@if ($invoice->status != 'REJECTED'&& $invoice->status != 'PENDING'&& $invoice->status != 'DRAFT')
    @if ($invoice->delivery_date == null && $invoice->order_no !== null)
        {{ __('NOT DELIVERED') }}
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
        @else
            {{ __('NOT DELIVERED') }}
        @endcan
    @elseif($invoice->delivery == 'DELIVERED')
        {{ date('Y-m-d', strtotime($invoice->delivery_date)) }}
    @endif
@else
-{{ __('NOT DELIVERED') }}
@endif
