<div class="modal fade" id="kt_modal_add_erea" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-950px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_erea_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('ereas') }}</h2>
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
                <form id="kt_modal_add_erea_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <input type="hidden" wire:model="erea_id" name="erea_id" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_erea_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_erea_header" data-kt-scroll-wrappers="#kt_modal_add_erea_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <label class="fs-6 fw-semibold mb-2">{{ __('canton') }}</label>
                                <select  data-kt-action="load_drop" wire:model="canton_id" name="canton_id" class="form-select form-select-solid"
                                        data-dropdown-parent="#kt_modal_add_erea">
                                    <option>{{ __('select an canton') }}</option>
                                    @foreach($cantons as $canton)
                                    <option value="{{ $canton->id }}">{{ $canton->name }}</option>
                                    @endforeach
                                </select>
                                @error('gender')
                                <span class="text-danger">{{ $message }}</span>  @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('town') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="town_id" name="town_id" class="form-select form-select-solid"
                                        data-dropdown-parent="#kt_modal_add_erea">
                                    <option>{{ __('select an town') }}</option>
                                    @foreach($towns as $town)
                                    <option value="{{ $town->id}}">{{ $town->name }}</option>
                                    @endforeach
                                <!-- <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('gender')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('erea') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- form-control-solid -->

                                <input type="text" wire:model="name" name="name" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('erea') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('status') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="status" name="status" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="ACTIVE">{{ __('active') }}</option>
                                    <option value="INACTIVE">{{ __('inactive') }}</option>
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('status')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>




                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-ereas-modal-action="submit">
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
