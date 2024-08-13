
@if(!$payment->reference)
        <button type="button"
                class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto pulse pulse-warning"
                data-kt-user-id="{{ $payment->id }}"
                data-bs-target="#kt_modal_add_refno"
                data-kt-menu-trigger="click"
                data-kt-menu-placement="bottom-end"
                data-kt-action="update_invoice">

            <i class="ki-duotone ki-pencil fs-3">
                <span class="path1"></span>
                <span class="path2"></span>

            </i>
            <span class="pulse-ring"></span>

        </button>
@else
{{ $payment->reference }}

@endif

    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
        data-kt-menu="true"
        data-kt-menu-id="kt_modal_add_refno" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="px-7 py-5">
            <div class="fs-5 text-gray-900 fw-bold">
                Metre a
                jour le No de quittance
            </div>
        </div>
        <div class="separator border-gray-200"></div>
        <livewire:payment.add-refno-form />
    </div>
