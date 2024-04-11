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
    @endcan
@elseif($payment->status == 'APROVED')
    <span
        class="badge badge-light-success">{{ __('APROVED') }}</span>
@endif


<!--begin::Task menu-->
<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
        data-kt-menu="true" data-kt-menu-id="kt_payment_modal_add_status">
    <!--begin::Header-->
    <div class="px-7 py-5">
        <div class="fs-5 text-gray-900 fw-bold">Metre à jour le status</div>
    </div>
    <!--end::Header-->
    <!--begin::Menu separator-->
    <div class="separator border-gray-200"></div>
    <!--end::Menu separator-->
    <!--begin::Form-->
    <livewire:payment.add-status-form />

    <!--end::Form-->
</div>
<!--end::Task menu-->
