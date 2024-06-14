<div class="modal fade" id="kt_modal_add_invoice_no_taxpayer" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered" style="max-width:calc(1000px - 20px)!important;">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_invoice_no_taxpayer_header">
                <h2 class="fw-bold">{{ __('invoices') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-5">
                <!--begin::Form-->
                <form id="kt_modal_add_invoice_no_taxpayer_form" class="form" action="#" wire:submit="submit"
                    enctype="multipart/form-data">
                    <input type="hidden" wire:model="invoice_id" name="invoice_id" />
                    <input type="hidden" wire:model="taxpayer_id" name="taxpayer_id" />
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_invoice_no_taxpayer_scroll"
                        data-kt-scroll="false" data-kt-scroll-activate="false" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_invoice_no_taxpayer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_invoice_no_taxpayer_scroll"
                        data-kt-scroll-offset="300px">


                        <div class="card-body py-4">
                            <div class="separator separator-content mb-6">
                                <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('taxpayer info') }}</span>
                            </div>
                            <div class="row mb-7">
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" wire:model="fullname" name="fullname" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('fullname') }}" />
                                    <!--end::Input-->
                                    @error('fullname')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2">
                                    <!--begin::Label-->
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('gender') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select wire:model="gender" name="gender" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer">
                                        <option>{{ __('select an option') }}</option>
                                        @foreach($genders as $gender)
                                            <option value="{{ $gender->name}}">{{ $gender->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option> -->
                                    </select>
                                    <!--end::Input-->
                                    @error('gender')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <!--begin::Label-->
                                    <label class=" fw-semibold fs-6 mb-2">{{ __('id type') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <!-- <select aria-label="Select an ID Type" data-control="select2" data-placeholder="Select an ID Type..." class="form-select "
                                        data-dropdown-parent="#kt_modal_add_taxpayer">
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                    </select> -->

                                    <select wire:model="id_type" name="id_type" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer" data-allow-clear="true">
                                        <option>{{ __('select an option') }}</option>
                                        @foreach($id_types as $id_type)
                                            <option value="{{ $id_type->name}}">{{ $id_type->name }}</option>
                                    @endforeach
                                    <!-- <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="3">In Process</option>
                                            <option value="4">Rejected</option> -->
                                    </select>
                                    <!--end::Input-->
                                    @error('id_type')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">{{ __('id number') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" wire:model="id_number" name="id_number" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('id number') }}" />
                                    <!--end::Input-->
                                    @error('id_number')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-7">
                                <!-- <div class="notice"> -->
                                <div class="col-md-3">

                                    <!--begin::Label-->
                                    <label class=" fw-semibold fs-6 mb-2">{{ __('mobilephone') }}</label>
                                    <!--end::Label-->

                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="basic-addon1">+228</span>
                                        <!--begin::Input-->
                                        <input type="text" wire:model="mobilephone" name="mobilephone" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('mobilephone') }}" />
                                        <!--end::Input-->
                                    </div>

                                    @error('mobilephone')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">{{ __('telephone') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" wire:model="telephone" name="telephone" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('telephone') }}" />
                                    <!--end::Input-->
                                    @error('telephone')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold fs-6 mb-2">{{ __('email') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="email" wire:model="email" name="email" class="form-control  mb-3 mb-lg-0" placeholder="example@domain.com" />
                                    <!--end::Input-->
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- </div> -->
                            </div>
                        </div>

                        <!--begin::Card body-->

                        <!--end::Card body-->


                        <div class="separator separator-content mb-6">
                            <span class="w-300px text-gray-500 fw-semibold fs-7">{{ __('taxable info') }}</span>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-5">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxlabels') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->

                                <select data-kt-action="load_drop" wire:model="taxlabel_id" name="taxlabel_id"
                                    class="form-select" data-dropdown-parent="#kt_modal_add_invoice_no_taxpayer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach ($taxlabels as $taxlabel)
                                        <option value="{{ $taxlabel->id }}">{{ $taxlabel->code }} --
                                            {{ $taxlabel->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                                @error('taxlabel_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxables') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select data-kt-action="load_drop" wire:model="taxable_id" name="taxable_id"
                                    class="form-select" data-dropdown-parent="#kt_modal_add_invoice_no_taxpayer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach ($taxables as $taxable)
                                        <option value="{{ $taxable->id }}">{{ $taxable->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('taxable_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('tariff') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tariff" name="tariff"
                                    class="form-control mb-3 mb-lg-0" placeholder="{{ __('tariff') }}"
                                    data-kt-action="change_tarrif"

                                />
                                <!--end::Input-->
                                @error('tariff')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('description') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name"
                                    class="form-control mb-3 mb-lg-0" placeholder="{{ __('description') }}"
                                    data-kt-action="add_taxable" />
                                <!--end::Input-->
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($option_calculus == "surface")
                                <div class="row mb-7">
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">{{ __('length') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input wire:model="length" name="length" type="text" class="form-control mb-3 mb-lg-0" placeholder="{{ __('length') }}" data-kt-action="load_drop"/>
                                        <!--end::Input-->
                                        @error('length')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">{{ __('width') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input wire:model="width" name="width" type="text" class="form-control mb-3 mb-lg-0" placeholder="{{ __('width') }}" data-kt-action="load_drop"/>
                                        <!--end::Input-->
                                        @error('width')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('seize') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="seize" name="seize"
                                    class="form-control mb-3 mb-lg-0" placeholder="{{ __('seize') }}"
                                       data-kt-action="change_tarrif"/>
                                <!--end::Input-->
                                @error('seize')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                                    <div class="col-md-1">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">{{ __('unit') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="unit" name="unit" class="form-control form-control-flush mb-3 mb-lg-0" placeholder="{{ __('unit') }}" readonly/>
                                        <!--end::Input-->
                                        @error('taxable_id')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                        </div>




                        <div class="row mb-2">
                            <div class="col-md-3">
                                <input type="hidden" class="required form-control form-control-flush text-end"
                                    placeholder="{{ __('Nombre de taxation') }}" readonly />
                                @error('qty')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <input wire:model="qty" name="qty" type="hidden"
                                       class="form-control border-0 ps-12" data-kt-dialer-control="input"
                                       placeholder="1"  readonly="readonly" data-kt-action="load_invoice_comptant" />
                            </div>



                            <div class="col-md-3">
                                <div class="input-group mb-2">
                                    @foreach ($months as $monthNumber => $monthName)
                                    <input  type="hidden" wire:model="start_month" name="start_month" class="form-control mb-3 mb-lg-0" placeholder="{{ $monthName}}" data-kt-action="load_invoice_comptant" value="{{ $monthNumber}}"/>
                                    @endforeach
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
                                            <input type="hidden" wire:model="taxpayer_taxable_id"
                                                name="taxpayer_taxable_id" name="taxpayer_taxable_id"
                                                class="form-control form-control-solid mb-2" />

                                            <div class="form-floating  overflow-hidden">
                                                <input type="text" wire:model="taxpayer_taxable"
                                                    name="taxpayer_taxable" id="taxpayer_taxable"
                                                    class="form-control form-control-solid" readonly />
                                                <label for="taxpayer_taxable">{{ $taxlabel_name }}</label>
                                            </div>

                                        </td>
                                        <!-- <td class="pe-7">
                                            <input type="number" class="form-control form-control-solid mb-2" name="quantity" placeholder="1" value="1" data-kt-element="quantity"/>
                                            <input type="text" class="form-control form-control-solid" name="mesure" placeholder="m3" value="m3"/>
                                        </td> -->
                                        <td class="ps-0">

                                            <div class="input-group mb-2">
                                                <input wire:model="s_seize" name="s_seize" type="text"
                                                    class="form-control form-control-solid text-end" readonly />
                                                <span class="input-group-text"
                                                    id="basic-addon1">{{ $unit }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group mb-2">
                                                <input wire:model="s_tariff" name="s_tariff" type="text"
                                                    class="form-control form-control-solid text-end"
                                                    placeholder="0.00" readonly />
                                                <span class="input-group-text"
                                                    id="basic-addon1">{{ $tariff_type }}</span>
                                            </div>
                                        </td>
                                        <!-- <td>
                                            <input wire:model="qty" name="qty" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" value=""  data-kt-action="load_invoice_comptant"/>
                                        </td> -->
                                        <td>
                                            <input wire:model="s_amount" name="s_amount" type="text"
                                                class="form-control form-control-solid mb-2 text-end"
                                                placeholder="0.00" readonly />
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
                                            <input type="text"
                                                class="fs-6 form-control form-control-flush text-end"
                                                wire:model="amount_ph" name="amount_ph" placeholder="0.00 FCFA"
                                                readonly />

                                            @if ($view_mode)
                                                <input type="text" class="fs-6 form-control text-end"
                                                    wire:model="amount_ph_e" name="amount_ph_e"
                                                    placeholder="0.00 FCFA" readonly />
                                            @endif
                                            <input type="hidden" class="form-control form-control-flush text-end"
                                                wire:model="amount" name="amount" placeholder="0.00" readonly />
                                            <input type="hidden" class="form-control form-control-flush text-end"
                                                wire:model="amount_e" name="amount_e" placeholder="0.00" readonly />
                                        </th>
                                    </tr>
                                    <tr class="align-top fw-bolder text-gray-700">
                                        <th></th>
                                        <th colspan="2" class="fs-4 ps-0">Total</th>
                                        <th colspan="2" class="text-end fs-4 text-nowrap">
                                            <input type="text"
                                                class="fs-5 form-control form-control-flush text-end"
                                                wire:model="amount_ph" name="amount_ph" placeholder="0.00 FCFA"
                                                readonly />
                                            @if ($view_mode)
                                                <input type="text" class="fs-5 form-control text-end"
                                                    wire:model="amount_ph_e" name="amount_ph_e"
                                                    placeholder="0.00 FCFA" readonly />
                                            @endif
                                            <input type="hidden" class="form-control form-control-flush text-end"
                                                wire:model="amount" name="amount" placeholder="0.00" readonly />
                                            <input type="hidden" class="form-control form-control-flush text-end"
                                                wire:model="amount_e" name="amount_e" placeholder="0.00" readonly />
                                        </th>

                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Thanks for your business"></textarea>
                            </div>
                        </div>


                        @can('create invoice payment')
                        {{--
                            <div class="separator separator-content mb-5">
                                <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('payment info') }}</span>
                            </div>

                            <div class="row">
                                <div class=" d-flex bg-light-warning rounded border-warning border border-dashed mb-1 p-2">
                                    <div class="col-md-4 me-10">
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('amount paid') }}</label>
                                        <!-- <input wire:model="amount" name="amount" class="form-control mb-2" type="text" /> -->
                                        <div class="input-group mb-2">
                                            <input wire:model="amount" name="amount" class="form-control text-end"
                                                type="text" readonly />
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
                                        <select wire:model="payment_type" name="payment_type" class="form-select"
                                            data-dropdown-parent="#kt_modal_add_payment">
                                            <option></option>
                                            <option value="CASH">CASH</option>
                                            <option value="DIGI">DIGI</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('reference no') }}</label>
                                        <input wire:model="reference" name="reference" class="form-control mb-2 text-end"
                                            type="text" />
                                    </div>
                                </div>
                            </div>
                            --}}
                        @endcan


                        <!--end::Input group-->
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-5">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
                                aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
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
