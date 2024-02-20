<form id="kt_modal_add_orderno" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_orderno">
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Input-->
        <input type="text" wire:model="orderno" name="orderno" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('orderno') }}" />
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
</form>

<form id="kt_modal_add_status" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_status">
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" wire:model="invoice_id" name="invoice_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('invoice_id') }}" />
        <select class="form-select form-select-solid" wire:model="orderno" name="orderno" data-placeholder="Select option" data-allow-clear="true">
            <option></option>
            @if($invoice->status=="PENDING")
            <option value="APROVED">APROVE</option>
            <option value="REJECTED">REJECT</option>
            <option value="REJECTED-EDIT">REJECT FOR EDIT</option>
            @elseif($invoice->status=="DRAFT")
            <option value="PENDING">APROVE</option>
            <option value="CANCELED">CANCEL</option>
            @elseif($invoice->status=="REJECTED-EDIT")
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