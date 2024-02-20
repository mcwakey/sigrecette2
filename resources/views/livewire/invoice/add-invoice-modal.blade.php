<div class="modal fade" id="kt_modal_add_invoice" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-950px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_invoice_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('create invoice') }}</h2>
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
                <form id="kt_modal_add_invoice_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="text" wire:model="invoice_id" name="invoice_id" value="{{ $invoice_id }}"/>
                    <input type="text" wire:model="taxpayer_id" name="taxpayer_id" value="{{ $taxpayer_id }}"/>
                    <!--begin::Scroll-->
                    
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_invoice_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_invoice_header" data-kt-scroll-wrappers="#kt_modal_add_invoice_scroll" data-kt-scroll-offset="300px">
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
                                <label class="required fw-semibold fs-6 mb-2">{{ __('tnif') }}</label>
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

                        
                        <div class="table-responsive mb-10">
                            <!--begin::Table-->
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-300px w-400px">Item</th>
                                        <th class="min-w-100px w-100px">QTY</th>
                                        <th class="min-w-100px w-100px">Unit</th>
                                        <th class="min-w-100px w-100px">Price</th>
                                        <th class="min-w-100px w-100px">period</th>
                                        <th class="min-w-100px w-100px text-end">Total</th>
                                        <th class="min-w-50px w-50px text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                @foreach($taxpayer_taxables as $taxpayer_taxable)
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7">
                                        <select wire:model="taxpayer_taxable_id" name="taxpayer_taxable_id[]" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_invoice">
                                            <option value="{{ $taxpayer_taxable->id }}">{{ $taxpayer_taxable->name }}</option>
                                            @foreach($taxpayer_taxables as $taxpayer_taxable_kid)
                                            <option value="{{ $taxpayer_taxable_kid->id }}">{{ $taxpayer_taxable_kid->name }}</option>
                                            @endforeach
                                        </select>
                                        </td>
                                        <!-- <td class="pe-7">
                                            <input type="number" class="form-control form-control-solid mb-2" name="quantity" placeholder="1" value="1" data-kt-element="quantity"/>
                                            <input type="text" class="form-control form-control-solid" name="mesure" placeholder="m3" value="m3"/>
                                        </td> -->
                                        <td class="ps-0">
                                            <input wire:model="qty.{{ loop.index }}" name="qty[]" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" value="{{ $taxpayer_taxable->seize }}" />
                                        </td>
                                        <td>
                                            <input class="form-control form-control-solid" type="text" name="mesure" placeholder="Unité d’assiette" value="{{ $taxpayer_taxable->taxable->unit }}"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-solid mb-2 text-end" name="price" placeholder="0.00" value="{{ $taxpayer_taxable->taxable->tariff }}" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-solid text-end" name="taxation" placeholder="Taxation/an" value="2"/>
                                        </td>
                                        <td class="pt-8 text-end text-nowrap">FCFA
                                        <span data-kt-element="total">0.00</span>
                                        <input  wire:model="s_amount" name="s_amount[]" type="text" class="form-control form-control-solid text-end" placeholder="s_amount" value=""/>
                                        </td>


                                        <td class="pt-5 text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                        <th class="text-primary">
                                            <!-- <button class="btn btn-link py-1" data-kt-element="add-item">Add item</button> -->
                                        </th>
                                        <th colspan="2" class="border-bottom border-bottom-dashed ps-0">
                                            <div class="d-flex flex-column align-items-start">
                                                <div class="fs-5">Subtotal</div>
                                            </div>
                                        </th>
                                        <th colspan="2" class="border-bottom border-bottom-dashed text-end">FCFA
                                        <span data-kt-element="sub-total">0.00</span></th>
                                    </tr>
                                    <tr class="align-top fw-bolder text-gray-700">
                                        <th></th>
                                        <th colspan="2" class="fs-4 ps-0">Total</th>
                                        <th colspan="2" class="text-end fs-4 text-nowrap">FCFA
                                        <span data-kt-element="grand-total">0.00</span>
                                        <input type="text" class="form-control form-control-solid text-end" wire:model="amount" name="amount" placeholder="Total" value=""/>
                                    </th>
                        
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                            <!-- <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                            <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                <td class="pe-7">
                                    <input type="text" class="form-control form-control-solid mb-2" name="name[]" placeholder="Item name" />
                                    <input type="text" class="form-control form-control-solid" name="description[]" placeholder="Description" />
                                </td>
                                <td class="ps-0">
                                    <input class="form-control form-control-solid" type="number" min="1" name="quantity[]" placeholder="1" data-kt-element="quantity" />
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-solid text-end" name="price[]" placeholder="0.00" data-kt-element="price" />
                                </td>
                                <td class="pt-8 text-end">FCFA
                                <span data-kt-element="total">0.00</span></td>
                                <td class="pt-5 text-end">
                                    <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                                            </svg>
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        </table>
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                            <tr data-kt-element="empty">
                                <th colspan="5" class="text-muted text-center py-10">No items</th>
                            </tr>
                        </table> -->
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
                        <button type="submit" class="btn btn-success" data-kt-invoices-modal-action="submit">
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