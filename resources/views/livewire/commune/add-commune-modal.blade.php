<div class="modal fade" id="kt_modal_add_commune" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-950px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_commune_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('create commune') }}</h2>
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
                <form id="kt_modal_add_commune_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <input type="hidden" wire:model="commune_id" name="commune_id" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_commune_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_commune_header" data-kt-scroll-wrappers="#kt_modal_add_commune_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->

                        <div class="row mb-7">

                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('commune_title') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="title" name="title" class="form-select mb-3 mb-lg-0" data-dropdown-parent="#kt_modal_add_commune">
                                    <option>Sélectionnez le préfixe :</option>
                                    <option value="de" selected >{{ __('Commune de') }}</option>
                                    <option value="d'">{{ __("Commune d'") }}</option>
                                </select>

                                <!--end::Input-->
                                @error('title')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('commune_name') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('commune_name') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('region_name') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="region_name" name="region_name" class="form-select" data-dropdown-parent="#kt_modal_add_commune">
                                    <option>{{ __('select an région') }}</option>
                                    <option value="REGION MARITIME">{{ __('Région Maritime') }}</option>
                                    <option value="REGION DES PLATEAUX">{{ __('Région des Plateaux') }}</option>
                                    <option value="REGION CENTRALE">{{ __('Région Centrale') }}</option>
                                    <option value="REGION DE KARA">{{ __('Région de Kara') }}</option>
                                    <option value="REGION DES SAVANES">{{ __('Région des Savanes') }}</option>
                                </select>

                                <!--end::Input-->
                                @error('region_name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('mayor_name') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="mayor_name" name="mayor_name" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('mayor_name') }}"/>
                                <!--end::Input-->
                                @error('mayor_name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('phone_number') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="phone_number" name="phone_number" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('phone_number') }}"/>
                                <!--end::Input-->
                                @error('phone_number')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('address') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="address" name="address" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('address') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('treasury_name') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="treasury_name" name="treasury_name" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('treasury_name') }}"/>
                                <!--end::Input-->
                                @error('treasury_name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('treasury_address') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="treasury_address" name="treasury_address" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('treasury_address') }}"/>
                                <!--end::Input-->
                                @error('treasury_address')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('treasury_rib') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="treasury_rib" name="treasury_rib" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('treasury_rib') }}"/>
                                <!--end::Input-->
                                @error('treasury_rib')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>



                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-commune-modal-action="submit">
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
