<form id="kt_modal_add_delivery" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_delivery">
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->
        
        <label class="required fw-semibold fs-6 mb-2">{{ __('delivery date') }}</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="hidden" wire:model="invoice_id" name="invoice_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('invoice_id') }}" />

        

<div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest" data-td-target-toggle="nearest">
    <input wire:model="delivery_date" name="delivery_date" id="kt_td_picker_basic_input" type="text" class="form-control" data-td-target="#kt_td_picker_basic" placeholder="yyyy-MM-dd"/>
    <span class="input-group-text" data-td-target="#kt_td_picker_basic" data-td-toggle="datetimepicker">
        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
    </span>
</div>



        
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