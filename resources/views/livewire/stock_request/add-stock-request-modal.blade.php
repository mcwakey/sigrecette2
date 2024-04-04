<div class="modal fade" id="kt_modal_add_stock_request" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_stock_request_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('stock requests') }}</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-2">
                <!--begin::Form-->
                <form id="kt_modal_add_stock_request_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="stock_request_id" name="stock_request_id"  value=""/>
                    <input type="hidden" wire:model="user_id" name="user_id" value=""/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_stock_request_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_stock_request_header" data-kt-scroll-wrappers="#kt_modal_add_stock_request_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('type') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    @if ($edit_mode == 'true')
                                    <input type="text" wire:model="taxlabel_name" name="taxlabel_name" class="form-control form-control- mb-3 mb-lg-0" readonly/>
                                    @else                     
                                <select data-kt-action="load_drop" wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_request">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select>
                                    @endif
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('batch no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input data-kt-action="load_drop" type="text" wire:model="req_no" name="req_no" class="form-control mb-3 mb-lg-0" placeholder="{{ __('req no') }}"/>
                                <!--end::Input-->
                                @error('req_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator saperator-dashed my-3"></div>

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('tickets') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    @if ($edit_mode == 'true')
                                    <input type="text" wire:model="taxable_name" name="taxable_name" class="form-control form-control- mb-3 mb-lg-0" readonly/>
                                    <input type="text" wire:model="taxable_idd" name="taxable_idd" class="form-control form-control- mb-3 mb-lg-0" readonly/>
                                    @else
                                    <select data-kt-action="load_drop" wire:model="taxable_id" name="taxable_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_request">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxables as $taxable)
                                    <option value="{{ $taxable->id}}">{{ $taxable->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                    @endif
                                <!--end::Input-->
                                @error('taxable_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('req no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input data-kt-action="load_drop" type="text" wire:model="req_no" name="req_no" class="form-control mb-3 mb-lg-0" placeholder="{{ __('req no') }}"/>
                                <!--end::Input-->
                                @error('req_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="separator saperator-dashed my-3"></div>

                        <div class="row mb-7">
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('start no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input data-kt-action="load_drop" -->
                                <input type="text" wire:model="start_no" name="start_no" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('start no') }}" data-kt-action="change_qty" />
                                <!--end::Input-->
                                @error('start_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('end no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="end_no" name="end_no" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('end no') }}" data-kt-action="change_qty" />
                                <!--end::Input-->
                                @error('end_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('qty') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="qty" name="qty" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('auth reference') }}" />
                                <!--end::Input-->
                                @error('qty')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <!-- <label class="fw-semibold fs-6 mb-2">{{ __('empty') }}.</label> -->
                                <!--end::Label-->
                                <!--begin::Input-->
                                <button type="submit" class="btn btn-success mt-8" data-kt-taxpayer-taxables-modal-action="submit">
                                    <span class="indicator-label" wire:loading.remove>{{ __('add') }}</span>
                                    <span class="indicator-progress" wire:loading wire:target="submit">
                                    {{ __('chargenment ...') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                
                                <!--end::Input-->
                            </div>
                        </div>
                        @if ($edit_mode != 'true')
                        <div class="separator separator-content separator-dashed my-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('request summary') }}</span>
                        </div>

                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-50px">{{ __('taxable') }}</th>
                                                <th class="min-w-50px">{{ __('tariff') }}</th>
                                                <th class="min-w-50px">{{ __('qty') }}</th>
                                                <th class="min-w-50px">{{ __('amount') }}</th>
                                                <th class="min-w-50px">{{ __('num') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                        @foreach($stock_requests as $stock_request)
                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td>
                                                {{ $stock_request->taxable->name }}
                                            </td>
                                            <td class="ps-0">
                                                {{ $stock_request->taxable->tariff }}
                                            </td>
                                            <td>
                                                {{ $stock_request->qty }} 
                                            </td>
                                            <td>
                                                {{ $stock_request->qty*$stock_request->taxable->tariff }}
                                            </td>
                                            <td>
                                                {{ $stock_request->start_no." - ".$stock_request->end_no }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                            </table>

                          @endif  


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