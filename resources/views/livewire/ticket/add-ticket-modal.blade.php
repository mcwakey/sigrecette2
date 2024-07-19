<div class="modal fade" id="kt_modal_add_ticket" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered" style="max-width:calc(1020px - 20px)!important;">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_ticket_header">
                <!--begin::Modal title-->
                @if (!$edit_mode)
                    <h2 class="fw-bold">{{ __('new ticket') }}</h2>
                @else
                    <h2 class="fw-bold">{{ __('Modifier Valeur Inactive') }}</h2>
                @endif
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-action="close_ticket_modal" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_ticket_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="ticket_id" name="ticket_id"/>
                    <input type="hidden" wire:model="modality" name="modality"  value="Quitance"/>

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_ticket_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_ticket_header" data-kt-scroll-wrappers="#kt_modal_add_ticket_scroll" data-kt-scroll-offset="300px">


                        <div class="row mb-7">
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('type') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="unit" name="unit" class="form-select"
                                    data-dropdown-parent="#kt_modal_add_ticket">
                                    <option>{{ __('select an option') }}</option>
                                    <!-- <option value="Jours">Jours</option> -->
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                    <option value="AUTRE">AUTRE</option>
                                </select>
                                <!--end::Input-->
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('ticket') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control mb-3 mb-lg-0" placeholder="{{ __('ticket') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('tariff') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tariff" name="tariff" class="form-control mb-3 mb-lg-0" placeholder="{{ __('tariff') }}"/>
                                <!--end::Input-->
                                @error('tariff')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-tickets-modal-action="submit">
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
