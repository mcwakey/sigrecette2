<div class="modal fade" id="kt_modal_auto_invoice" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_auto_invoice_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('automatic invoice genaration') }}</h2>
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
                <form id="kt_modal_auto_invoice_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="invoice_id" name="invoice_id" />
                    <input type="hidden" wire:model="taxpayer_id" name="taxpayer_id" />
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_auto_invoice_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_auto_invoice_header" data-kt-scroll-wrappers="#kt_modal_auto_invoice_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('taxlabel') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="taxlabel" name="taxlabel" class="form-select">
                                    <option value=""></option>
                                    @foreach($tax_labels as $tax_label)
                                    <option value="{{ $tax_label->id}}">{{ $tax_label->code }} -- {{ $tax_label->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                @error('taxlabel')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="zone" name="zone" class="form-select">
                                    <option value=""></option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id}}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                @error('zone')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Duree du contrat') }}" readonly />
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative" data-kt-dialer="true" data-kt-dialer-default="12" data-kt-dialer-min="1" data-kt-dialer-max="12" data-kt-dialer-step="1">
                                    <!--begin::Decrease control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                        <i class="ki-outline ki-minus-circle fs-1"></i>
                                    </button>
                                    <!--end::Decrease control-->
                                    <!--begin::Input control-->
                                    <input wire:model="qty" name="qty" type="text" class="form-control border-0 ps-12" data-kt-dialer-control="input" placeholder="1" readonly="readonly" data-kt-action="auto_invoice" />
                                    <!--end::Input control-->
                                    @error('qty')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                    <!--begin::Increase control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                        <i class="ki-outline ki-plus-circle fs-1"></i>
                                    </button>
                                    <!--end::Increase control-->
                                </div>

                            </div>
                            <div class="col-md-2">
                                <input type="text" value="{{ __('month') }}" class="required form-control form-control-flush" readonly />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('A compter de') }}" readonly />
                            </div>
                            <div class="col-md-3">
                            <div class="input-group mb-2">
                                <select wire:model="start_month" name="start_month" class="form-select form-control-select" data-dropdown-parent="#kt_modal_auto_invoice">
                                    <option value="01">Janvier</option>
                                    <option value="02">Fevrier</option>
                                    <option value="03">Mars</option>
                                    <option value="04">Avril</option>
                                    <option value="05">Mai</option>
                                    <option value="06">Juin</option>
                                    <option value="07">Juillet</option>
                                    <option value="08">Aout</option>
                                    <option value="09">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Decembre</option>
                                </select>
                                <span class="input-group-text" id="basic-addon1">2024</span>
                                        </div>

                            </div>

                        </div>


                        <div class="separator separator-dashed my-4"></div>

                        <div class="row mb-2">
                            <div class="notice d-flex align-items-center rounded py-5 px-5 bg-light-danger border-danger border border-dashed">
                                <i class="ki-duotone ki-information-5 fs-3x text-danger me-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <!--begin::Description-->
                                <div class="text-gray-700 fw-bold fs-6">
                                {{ __('are you sure') }}
                                </div>
                                <!--end::Description-->
                            </div>
                        </div>


                        <div class="separator separator-dashed my-2"></div>

                        <div class="text-center pt-5">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                            <button type="submit" class="btn btn-success" data-kt-invoices-modal-action="submit">
                                <span class="indicator-label" wire:loading.remove>{{ __('generate') }}</span>
                                <span class="indicator-progress" wire:loading wire:target="submit">
                                    {{ __('please wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                        </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
