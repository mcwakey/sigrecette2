<div class="modal fade" id="kt_modal_add_taxable" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_taxable_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('new taxable') }}</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_taxable_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="taxable_id" name="taxable_id" value="{{ $taxable_id }}"/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxable_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxable_header" data-kt-scroll-wrappers="#kt_modal_add_taxable_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('label') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="tax_label_id" name="tax_label_id" class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select gender') }}</option>
                                    @foreach($tax_labels as $tax_label)
                                    <option value="{{ $tax_label->id}}">{{ $tax_label->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('gender')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="separator saperator-dashed my-5"></div>

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('name') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('name') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('tariff') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tariff" name="tariff" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('tariff') }}"/>
                                <!--end::Input-->
                                @error('tariff')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('unit') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <select aria-label="Select an ID Type" data-control="select2" data-placeholder="Select an ID Type..." class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select> -->

                                <select wire:model="unit" name="unit" class="form-select form-select-solid" name="task_status" data-kt-select2="false" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                    <option>{{ __('select id type') }}</option>
                                    <option value="m2">m2</option>
                                    <!-- <option value="1">Approved</option>
                                    <option value="2">Pending</option>
                                    <option value="3">In Process</option>
                                    <option value="4">Rejected</option> -->
                                </select>
                                <!--end::Input-->
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('modality') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="modality" name="modality" class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select gender') }}</option>
                                    <option value="Ticket">Ticket</option>
                                    <option value="Quitance">Quitance</option>
                                    <option value="Timbre">Timbre</option>
                                </select>
                                <!--end::Input-->
                                @error('modality')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('periodicity') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="periodicity" name="periodicity" class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select gender') }}</option>
                                    <option value="Mois">Mois</option>
                                    <option value="Forfait">Forfait</option>
                                </select>
                                <!--end::Input-->
                                @error('periodicity')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-taxables-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>{{ __('submit') }}</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                            {{ __('please wait') }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>