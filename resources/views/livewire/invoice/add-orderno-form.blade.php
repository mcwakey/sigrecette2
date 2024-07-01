<form id="kt_modal_add_orderno" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_orderno" data-bs-backdrop='static'>
    <!--begin::Input group-->
    <div class="fv-row mb-5">

    <label class="required fw-semibold fs-6 mb-2">{{ __('order no') }}</label>
        <!--begin::Input-->
        <input type="hidden" wire:model="invoice_id" name="invoice_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('invoice_id') }}" />
        <input type="text" wire:model="orderno" name="orderno" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('order no') }}" />
        @error('orderno')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-sm btn-success">
            <span wire:loading.remove>{{ __('apply') }}</span>
            <span wire:loading>{{ __('loading') }}</span>
        </button>
    </div>
</form>
