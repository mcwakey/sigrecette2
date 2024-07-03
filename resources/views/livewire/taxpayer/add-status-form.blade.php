
<form id="kt_taxpayer_modal_add_status" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_taxpayer_modal_add_status" data-bs-backdrop='static'>
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->

        <label class="required fw-semibold fs-6 mb-2">{{ __('status') }}</label>
        @if ($status == App\Enums\TaxpayerStateEnums::PENDING)
            <select class="form-select form-select-solid" wire:model="status" name="status"
                    data-placeholder="Select option" data-allow-clear="true">
                <option value="{{App\Enums\TaxpayerStateEnums::APPROVED}}">{{ __('valider') }}</option>
                <option value="{{App\Enums\TaxpayerStateEnums::REJECTED}}">{{ __('rejeter') }}</option>
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

