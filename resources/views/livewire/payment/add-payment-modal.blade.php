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
                    <input type="hidden" wire:model="payment_id" name="payment_id" value="{{ $payment_id }}" />
                    <input type="hidden" wire:model="invoice_id" name="invoice_id" value="{{ $invoice_id }}" />
                    <input type="hidden" wire:model="taxpayer_id" name="taxpayer_id" value="{{ $taxpayer_id }}" />
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_payment_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_payment_header" data-kt-scroll-wrappers="#kt_modal_add_payment_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('fullname') }}" readonly/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('account id') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tnif" name="tnif" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('tnif') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="zone" name="zone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('zone') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-content mb-5">
                            <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('payment history') }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('invoice no') }} :" readonly />
                            </div>
                            <div class="col-md-2">
                                <input wire:model="invoice_no" name="invoice_no" class="form-control form-control-flush mb-2" type="text" readonly />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('order no') }} :" readonly />
                            </div>
                            <div class="col-md-2">
                                <input wire:model="order_no" name="order_no" class="form-control form-control-flush mb-2" type="text" readonly />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('nic') }} :" readonly />
                            </div>
                            <div class="col-md-2">
                                <input wire:model="nic" name="nic" class="form-control form-control-flush mb-2" type="text" readonly />
                            </div>
                        </div>
                            <!--begin::Icon-->
                            <!--end::Icon-->
                            <!--begin::Wrapper-->

                            <!--end::Wrapper-->

                        <div class="row">
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-1 p-2">

                            <div class="col-md-3">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Nombre de taxation') }} :" readonly />
                            </div>
                            <div class="col-md-2">
                                <input wire:model="qty" name="qty" class="form-control form-control-flush mb-2" type="text" readonly />
                            </div>
                            <div class="col-md-2">
                                <input type="text" wire:model="periodicity" name="periodicity" class="required form-control form-control-flush" placeholder="{{ __('amount') }}" readonly />
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('amount') }}" readonly />
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2">
                                    <input wire:model="bill" name="bill" class="form-control form-control-flush text-end" type="text" readonly />
                                    <span class="input-group-text" id="basic-addon1">{{ __('currency') }}</span>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-5 p-2 py-3">

                                <div class="col-md-3">
                                    <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('amount paid') }} :" readonly />
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group mb-2">
                                        <input wire:model="paid" name="paid" class="form-control form-control-flush" type="text" readonly />
                                        <span class="input-group-text" id="basic-addon1">{{ __('currency') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('balance') }} :" readonly />
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group mb-2">
                                    <input wire:model="balance" name="balance" class="form-control form-control-flush text-end" type="text" readonly />
                                        <span class="input-group-text" id="basic-addon1">{{ __('currency') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator separator-content mb-5">
                            <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('payment info') }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="required fw-semibold fs-6 mb-2">{{ __('amount paid') }}</label>
                                <input wire:model="amount" name="amount" class="form-control mb-2 text-end" type="text" />
                                @error('amount')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="fw-semibold fs-6 mb-2">{{ __('.') }}</label>
                                <input class="form-control form-control-flush mb-2" type="text" placeholder="FCFA" readonly />
                            </div>
                            <!--TODO CHEQUE implementation-->
                            <div class="col-md-3">
                                <label class="required fw-semibold fs-6 mb-2">{{ __('payment type') }}</label>
                                <select wire:model="payment_type" name="payment_type" class="form-select" data-dropdown-parent="#kt_modal_add_payment">
                                    <option></option>
                                    <option value="CASH">CASH</option>
                                    <option value="CHEQUE">CHEQUE</option>
                                    <option value="DIGI">DIGI</option>
                                </select>
                                @error('payment_type')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="required fw-semibold fs-6 mb-2">{{ __('reference no') }}</label>
                                <input wire:model="reference" name="reference" class="form-control mb-2 text-end" type="text" />
                                @error('reference')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
                            <textarea  wire:model="description" name="description"  class="form-control" rows="2" placeholder=""></textarea>
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
