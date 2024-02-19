<div class="modal fade" id="kt_modal_add_taxpayer_taxable" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_taxpayer_taxable_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('assets') }}</h2>
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
                <form id="kt_modal_add_taxpayer_taxable_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="taxpayer_taxable_id" name="taxpayer_taxable_id"  value="{{ $taxpayer_taxable_id }}"/>
                    <input type="hidden" wire:model="taxpayer_id" name="taxpayer_id" value="{{ $taxpayer_id }}"/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxpayer_taxable_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxpayer_taxable_header" data-kt-scroll-wrappers="#kt_modal_add_taxpayer_taxable_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('taxlabels') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
       
                                <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_taxpayer_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxlabels as $taxlabel)
                                    <option data-kt-action="load_taxables" value="{{ $taxlabel->id}}">{{ $taxlabel->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="separator saperator-dashed my-5"></div>

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('taxables') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="taxable_id" name="taxable_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_taxpayer_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxables as $taxable)
                                    <option value="{{ $taxable->id}}">{{ $taxable->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('taxable_id')
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
                                <label class="required fw-semibold fs-6 mb-2">{{ __('seize') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="seize" name="seize" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('seize') }}"/>
                                <!--end::Input-->
                                @error('seize')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('address') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="location" name="location" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('location') }}"/>
                                <!--end::Input-->
                                @error('location')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('longitude') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="longitude" name="longitude" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('longitude') }}"/>
                                <!--end::Input-->
                                @error('longitude')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('latitude') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="latitude" name="latitude" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('latitude') }}"/>
                                <!--end::Input-->
                                @error('latitude')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-taxpayer-taxables-modal-action="submit">
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