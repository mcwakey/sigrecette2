<div class="modal fade" id="kt_modal_add_accountant_deposit" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_accountant_deposit_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('versement du regisseur title') }}</h2>
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
                <form id="kt_modal_add_accountant_deposit_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="accountant_deposit_id" name="accountant_deposit_id"  value=""/>
                    <input type="hidden" wire:model="user_id" name="user_id" value=""/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_accountant_deposit_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_accountant_deposit_header" data-kt-scroll-wrappers="#kt_modal_add_accountant_deposit_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <input type="text" class="form-control form-control-flush mb-3 mb-lg-0" placeholder="{{ __('Montant a verser') }}" readonly/>

                                <!--end::Input-->
                                @error('collector_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <!--end::Label-->
                                <!--begin::Input-->

                                <input type="text" wire:model="to_be_paid" name="to_be_paid" class="form-control mb-3 mb-lg-0" placeholder="{{ __('Montant a verser') }}" readonly/>

                                <!-- <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_accountant_deposit">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select> -->
                                <!--end::Input-->
                                @error('to_be_paid')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <!--  -->
                        </div>

                        <div class="separator saperator-dashed my-3"></div>

                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('Montant verser') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <input type="text" wire:model="taxlabel_id" name="taxlabel_id" class="form-control mb-3 mb-lg-0" placeholder="{{ __('reference no') }}"/>
                                <!--end::Input-->
                                @error('collector_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('Type de payment') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                                    
                                <select data-kt-action="load_drop" wire:model="collector_id" name="collector_id" class="form-select" data-dropdown-parent="#kt_modal_add_accountant_deposit">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="CASH">CASH</option>
                                    <option value="CHEQUE">CHEQUE</option>
                                </select>

                                <!-- <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_accountant_deposit">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select> -->
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('reference no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <input type="text" wire:model="taxlabel_id" name="taxlabel_id" class="form-control mb-3 mb-lg-0" placeholder="{{ __('reference no') }}"/>

                                <!-- <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_accountant_deposit">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select> -->
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

<div class="text-center mb-7">
    <button type="submit" class="btn btn-danger mt-5" data-kt-taxpayer-taxables-modal-action="submit">
        <span class="indicator-label" wire:loading.remove>{{ __('Finaliser le versement') }}</span>
        <span class="indicator-progress" wire:loading wire:target="submit">
        {{ __('chargenment ...') }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>

                        
                        <div class="separator separator-content separator-dashed my-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('request summary') }}</span>
                        </div>

                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-50px">{{ __('date') }}</th>
                                                <th class="min-w-50px">{{ __('invoice no') }}</th>
                                                <th class="min-w-50px">{{ __('order no') }}</th>
                                                <th class="min-w-50px">{{ __('amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                        @foreach($stock_transfers as $accountant_deposit)
                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td>
                                                {{ $accountant_deposit->taxable->name }}
                                            </td>
                                            <td class="ps-0">
                                                {{ $accountant_deposit->taxable->tariff }}
                                            </td>
                                            <td>
                                                {{ $accountant_deposit->qty }} 
                                            </td>
                                            <td>
                                                {{ $accountant_deposit->qty*$accountant_deposit->taxable->tariff }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                            </table>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('close') }}</button>
                     
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