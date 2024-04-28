@php
    $status = isset($status) ? $status : (isset($payment) ? $payment->status : null);
@endphp

<form id="kt_payment_modal_add_status" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_payment_modal_add_status">
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->

        <label class="required fw-semibold fs-6 mb-2">{{ __('status') }}</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="hidden" wire:model="payment_id" name="payment_id" class="form-control form-control-solid mb-3 mb-lg-0"
               placeholder="{{ __('payment_id') }}" />
            @if ($status == App\Enums\PaymentStatusEnums::PENDING)
                <select class="form-select form-select-solid" wire:model="status" name="status"
                        data-placeholder="Select option" data-allow-clear="true">
                    <option></option>
                    <option value="{{App\Enums\PaymentStatusEnums::ACCOUNTED}}">{{ __('ACCOUNTED') }}</option>
                </select>
            @endif


    <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-sm btn-success">
            <span wire:loading.remove>{{ __('apply') }}</span>
            <span wire:loading>{{ __('loading') }}</span>
        </button>
    </div>
    <!--end::Actions-->
</form>
