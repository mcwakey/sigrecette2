<div class="modal fade" id="kt_modal_add_invoice_no_taxpayer" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_invoice_no_taxpayer_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('invoices') }}</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-5">
                <!--begin::Form-->
                <form id="kt_modal_add_invoice_no_taxpayer_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="text" wire:model="invoice_id" name="invoice_id" />
                    <input type="text" wire:model="taxpayer_id" name="taxpayer_id" />
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_invoice_no_taxpayer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_invoice_no_taxpayer_header" data-kt-scroll-wrappers="#kt_modal_add_invoice_no_taxpayer_scroll" data-kt-scroll-offset="300px">
                        <!--(begin::Input group-->

                     <div class="row">

                        <div class="text-center">
                            <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-success btn-active-light-success me-5 rotate" data-bs-toggle="collapse" data-bs-target="#kt_add_taxpayer_form">
                            {{ __('add taxpayer infos') }} <i class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span class="path1"></span><span class="path2"></span></i></a>
                
                        </div>
                    </div>

                    
        <!--begin::Card body-->
        <div class="card-body py-4"> 

    <div class="collapse" id="kt_add_taxpayer_form">
        <!--begin::Separator-->
        <div class="separator separator-dashed mt-5 mb-5"></div>
        <!--end::Separator-->
        <!--begin::Row-->

                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control mb-3 mb-lg-0" placeholder="{{ __('fullname') }}" />
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('account id') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tnif" name="tnif" class="form-control mb-3 mb-lg-0" placeholder="{{ __('tnif') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="zone" name="zone" class="form-control mb-3 mb-lg-0" placeholder="{{ __('zone') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-1">
                                <!--begin::Label-->
                                <label class=" fw-semibold fs-6 mb-2">{{ __('empty') }}.</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <button type="button" class="btn btn-light-success ms-auto me-5" data-kt-user-id="{{-- $taxpayer->id --}}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer_taxable" data-kt-action="add_taxable">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                                
                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control mb-3 mb-lg-0" placeholder="{{ __('fullname') }}" />
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('account id') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tnif" name="tnif" class="form-control mb-3 mb-lg-0" placeholder="{{ __('tnif') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="zone" name="zone" class="form-control mb-3 mb-lg-0" placeholder="{{ __('zone') }}" />

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-1">
                                <!--begin::Label-->
                                <label class=" fw-semibold fs-6 mb-2">{{ __('empty') }}.</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <button type="button" class="btn btn-light-success ms-auto me-5" data-kt-user-id="{{-- $taxpayer->id --}}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer_taxable" data-kt-action="add_taxable">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                                
                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
        <!--end::Row-->

        <div class="separator separator-dashed mt-5 mb-5"></div>
    </div>

<!--begin::Table-->
<!--end::Table-->
</div>
<!--end::Card body-->
                        

                        <!-- <div class="row mb-5">

                            <div class="text-center">
                                <button type="button" class="btn btn-light-success ms-auto me-5" data-kt-user-id="{{-- $taxpayer->id --}}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer_taxable" data-kt-action="add_taxable">
                                    <i class="ki-duotone ki-add-files fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>{{ __('add taxpayer infos') }}</button>
                            </div>
                        </div> -->


                        <div class="separator separator-content my-5">
                            <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('taxable info') }}</span>
                        </div>
        
                        <div class="row mb-5">
                            <div class="col-md-5">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxlabels') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
       
                                <select data-kt-action="load_drop" wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_invoice_no_taxpayer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxlabels as $taxlabel)
                                    <option value="{{ $taxlabel->id}}">{{ $taxlabel->code}} -- {{ $taxlabel->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-5">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxables') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select data-kt-action="load_drop" wire:model="taxable_id" name="taxable_id" class="form-select" data-dropdown-parent="#kt_modal_add_invoice_no_taxpayer">
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
                            <div class="col-md-2">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('tariff') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tariff" name="tariff" class="form-control mb-3 mb-lg-0" placeholder="{{ __('tariff') }}" data-kt-action="change_tarrif"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('description') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control mb-3 mb-lg-0" placeholder="{{ __('description') }}" data-kt-action="add_taxable"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('seize') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="seize" name="seize" class="form-control mb-3 mb-lg-0" placeholder="{{ __('seize') }}" data-kt-action="add_taxable"/>
                                <!--end::Input-->
                                @error('sieze')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        

                        <div class="separator separator-content mb-3">
                            <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('invoice info') }}</span>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Duree du contrat') }}" readonly />
                                    @error('qty')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="0" data-kt-dialer-max="12" data-kt-dialer-step="1">
                                    <!--begin::Decrease control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                        <i class="ki-outline ki-minus-circle fs-1"></i>
                                    </button>
                                    <!--end::Decrease control-->
                                    <!--begin::Input control-->
                                    <input wire:model="qty" name="qty" type="text" class="form-control border-0 ps-12" data-kt-dialer-control="input" placeholder="0" readonly="readonly" data-kt-action="load_invoice" />
                                    <!--end::Input control-->
                                    <!--begin::Increase control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                        <i class="ki-outline ki-plus-circle fs-1"></i>
                                    </button>
                                    <!--end::Increase control-->
                                </div>

                            </div>
                            <div class="col-md-2">
                                <input type="text" value="Mois" class="required form-control form-control-flush" readonly />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('A compter de') }}" readonly />
                            </div>
                            <div class="col-md-3">
                            <div class="input-group mb-2">
                                <select wire:model="start_month" name="start_month" class="form-select form-control-select" data-dropdown-parent="#kt_modal_add_invoice_no_taxpayer">
                                    <option></option>
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

                        <div class="separator separator-dashed my-2"></div>

                        <div class="table-responsive mb-2">
                            <!--begin::Table-->
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-300px w-450px">Item</th>
                                        <th class="min-w-150px w-150px">Dimensions</th>
                                        <th class="min-w-150px w-150px">Price</th>
                                        <!-- <th class="min-w-100px w-100px">period</th> -->
                                        <th class="min-w-100px text-end">Total</th>
                                        <!-- <th class="min-w-50px w-50px text-end"></th> -->
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7">
                                            <input type="hidden" wire:model="taxpayer_taxable_id" name="taxpayer_taxable_id" name="taxpayer_taxable_id" class="form-control form-control-solid mb-2" />
                                            
                                            <div class="form-floating">
                                                <input type="text" wire:model="taxpayer_taxable" name="taxpayer_taxable" id="taxpayer_taxable" class="form-control form-control-solid" readonly />
                                                <label for="taxpayer_taxable">{{ $taxlabel_name }}</label>
                                            </div>
                                            
                                        </td>
                                        <!-- <td class="pe-7">
                                            <input type="number" class="form-control form-control-solid mb-2" name="quantity" placeholder="1" value="1" data-kt-element="quantity"/>
                                            <input type="text" class="form-control form-control-solid" name="mesure" placeholder="m3" value="m3"/>
                                        </td> -->
                                        <td class="ps-0">

                                        <div class="input-group mb-2">
                                            <input wire:model="s_seize" name="s_seize" type="text" class="form-control form-control-solid text-end" readonly />
                                            <span class="input-group-text" id="basic-addon1">{{ $unit }}</span>
                                        </div>
                                        </td>
                                        <td>
                                        <div class="input-group mb-2">
                                            <input wire:model="s_tariff" name="s_tariff" type="text" class="form-control form-control-solid text-end" placeholder="0.00" readonly />
                                            <span class="input-group-text" id="basic-addon1">{{ $tariff_type }}</span>
                                        </div>
                                        </td>
                                        <!-- <td>
                                            <input wire:model="qty" name="qty" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" value=""  data-kt-action="load_invoice"/>
                                        </td> -->
                                        <td>
                                            <input wire:model="s_amount" name="s_amount" type="text" class="form-control form-control-solid mb-2 text-end" placeholder="0.00" readonly />
                                        </td>


                                        <!-- <td class="pt-5">FCFA -->
                                        <!-- <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                                                    </svg>
                                                </span>
                                            </button> -->
                                        <!-- </td> -->
                                    </tr>
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
                                        <th colspan="2" class="border-bottom border-bottom-dashed text-end">
                                            <input type="text" class="fs-6 form-control form-control-flush text-end" wire:model="amount_ph" name="amount_ph" placeholder="0.00 FCFA" readonly />

                                            @if ($view_mode)
                                            <input type="text" class="fs-6 form-control text-end" wire:model="amount_ph_e" name="amount_ph_e" placeholder="0.00 FCFA" readonly />
                                            @endif
                                            <input type="hidden" class="form-control form-control-flush text-end" wire:model="amount" name="amount" placeholder="0.00" readonly />
                                            <input type="hidden" class="form-control form-control-flush text-end" wire:model="amount_e" name="amount_e" placeholder="0.00" readonly />
                                        </th>
                                    </tr>
                                    <tr class="align-top fw-bolder text-gray-700">
                                        <th></th>
                                        <th colspan="2" class="fs-4 ps-0">Total</th>
                                        <th colspan="2" class="text-end fs-4 text-nowrap">
                                            <input type="text" class="fs-5 form-control form-control-flush text-end" wire:model="amount_ph" name="amount_ph" placeholder="0.00 FCFA" readonly />
                                            @if ($view_mode)
                                            <input type="text" class="fs-5 form-control text-end" wire:model="amount_ph_e" name="amount_ph_e" placeholder="0.00 FCFA" readonly />
                                            @endif
                                            <input type="hidden" class="form-control form-control-flush text-end" wire:model="amount" name="amount" placeholder="0.00" readonly />
                                            <input type="hidden" class="form-control form-control-flush text-end" wire:model="amount_e" name="amount_e" placeholder="0.00" readonly />
                                        </th>

                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Thanks for your business"></textarea>
                            </div>
                        </div>
                        <!-- <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                            <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                <td class="pe-7">
                                    <input type="text" class="form-control form-control-solid mb-2" name="name" placeholder="Item name" />
                                    <input type="text" class="form-control form-control-solid" name="description" placeholder="Description" />
                                </td>
                                <td class="ps-0">
                                    <input class="form-control form-control-solid" type="number" min="1" name="quantity" placeholder="1" data-kt-element="quantity" />
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-solid text-end" name="price" placeholder="0.00" data-kt-element="price" />
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
                            </table> -->

                        

                            <div class="separator separator-content mb-5">
                                <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('payment info') }}</span>
                            </div>

                        <div class="row">
                            <div class=" d-flex bg-light-warning rounded border-warning border border-dashed mb-1 p-2">
                                <div class="col-md-4 me-10">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('amount paid') }}</label>
                                    <!-- <input wire:model="amount" name="amount" class="form-control mb-2" type="text" /> -->
                                    <div class="input-group mb-2">
                                        <input wire:model="amount" name="amount" class="form-control text-end" type="text" readonly />
                                        <span class="input-group-text" id="basic-addon1">{{ __('currency') }}</span>    
                                    </div>
                                </div>
                                <!-- <div class="col-md-2">
                                    <label class="fw-semibold fs-6 mb-2">{{ __('.') }}</label>
                                    <input class="form-control form-control-flush mb-2" type="text" placeholder="FCFA" readonly />
                                </div> -->
                                <!-- <div class="col-md-2">
                                </div> -->
                                <div class="col-md-3 me-10">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('payment type') }}</label>
                                    <select wire:model="payment_type" name="payment_type" class="form-select" data-dropdown-parent="#kt_modal_add_payment">
                                        <option></option>
                                        <option value="CASH">CASH</option>
                                        <option value="DIGI">DIGI</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('reference no') }}</label>
                                    <input wire:model="reference" name="reference" class="form-control mb-2 text-end" type="text" />
                                </div>
                            </div>
                        </div>


                            <!--end::Input group-->
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-5">
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