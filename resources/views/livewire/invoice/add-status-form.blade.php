<form id="kt_modal_add_status" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_status">
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->
        
        <label class="required fw-semibold fs-6 mb-2">{{ __('status') }}</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="hidden" wire:model="invoice_id" name="invoice_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('invoice_id') }}" />
        <select class="form-select form-select-solid" wire:model="status" name="status" data-placeholder="Select option" data-allow-clear="true">
            <option></option>
            @if($status=="PENDING")
            <option value="APROVED">APROVE</option>
            <option value="REJECTED">REJECT</option>
            <option value="REJECTED-EDIT">REJECT FOR EDIT</option>
            @elseif($status=="DRAFT")
            <option value="PENDING">APROVE</option>
            <option value="CANCELED">CANCEL</option>
            @elseif($status=="REJECTED-EDIT")
            <option value="PENDING">APROVE</option>
            @endif
        </select>
        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-sm btn-success">
            <span wire:loading.remove>Apply</span>
            <span wire:loading>Loading...</span>
        </button>
    </div>
    <!--end::Actions-->
</form>