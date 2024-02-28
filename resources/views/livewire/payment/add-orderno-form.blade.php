<div class="modal fade" id="kt_modal_add_payment" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_payment_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('payments') }}</h2>
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
                <form id="kt_modal_add_payment_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="text" wire:model="payment_id" name="payment_id" value="{{ $payment_id }}"/>
                    <input type="text" wire:model="invoice_id" name="invoice_id" value="{{ $invoice_id }}"/>
                    <input type="text" wire:model="taxpayer_id" name="taxpayer_id" value="{{ $taxpayer_id }}"/>
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_payment_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_payment_header" data-kt-scroll-wrappers="#kt_modal_add_payment_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('fullname') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('account no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tnif" name="tnif" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('tnif') }}"/>

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="zone" name="zone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('zone') }}"/>

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        <div class="row mb-7">
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('invoice no') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="invoice_no" name="invoice_no" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" data-kt-user-id="{{ $taxpayer_id }}" data-kt-action="load_payment"/>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('order no') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="order_no" name="order_no" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" data-kt-user-id="{{ $taxpayer_id }}" data-kt-action="load_payment"/>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('nic') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="nic" name="nic" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" data-kt-user-id="{{ $taxpayer_id }}" data-kt-action="load_payment"/>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        <div class="row mb-7">
                            <div class="col-md-2">
                            <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Duree du contrat') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="qty" name="qty" class="form-control form-control-solid mb-2" type="text"/>
                            </div>
                            <div class="col-md-2">
                            <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('montant a payer') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="amount" name="amount" class="form-control form-control-solid mb-2" type="text" />
                            </div>
                            <div class="col-md-2">
                            <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('A compter de') }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <select wire:model="start_month" name="taxpayer_taxable_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_payment">
                                    <option></option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Fevrier</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Aout</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Decembre</option>
                                </select> 
                            </div>

                            <div class="separator separator-dashed my-2"></div>

                            <div class="mb-0">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
                                <textarea name="notes" class="form-control form-control-solid" rows="3" placeholder="Thanks for your business"></textarea>
                            </div>

                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-payments-modal-action="submit">
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