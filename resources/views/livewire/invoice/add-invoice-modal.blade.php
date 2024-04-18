@php
    use Carbon\Carbon;
    $year= \App\Models\Year::getActiveYear();
    $months = [];
    // Obtenez le mois actuel
    $currentMonth = Carbon::now()->month;
    $remainingMonths = 12 - $currentMonth;

    for ($i = $currentMonth + 1; $i <= $currentMonth + $remainingMonths; $i++) {
        $monthIndex = $i > 12 ? $i - 12 : $i;
        $monthName = Carbon::createFromFormat('m',$monthIndex)->monthName;
        $monthNumber = str_pad($monthIndex, 2, '0', STR_PAD_LEFT);
        $months[$monthNumber] = $monthName;
    }

@endphp
<div class="modal fade" id="kt_modal_add_invoice" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_invoice_header">
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
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_invoice_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="invoice_id" name="invoice_id" />
                    <input type="hidden" wire:model="taxpayer_id" name="taxpayer_id" />
                    <!--begin::Scroll-->

                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_invoice_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_invoice_header" data-kt-scroll-wrappers="#kt_modal_add_invoice_scroll" data-kt-scroll-offset="300px">
                        <!--(begin::Input group-->
                        @if ($taxpayer_id)
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
                                <input type="text" wire:model="tnif" name="tnif" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('tnif') }}"  readonly/>

                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="zone" name="zone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('zone') }}" readonly/>

                                <!--end::Input-->
                                @error('zone')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator separator-dashed my-2"></div>

                        @endif

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="required form-control form-control-flush text-end"> {{ __('Nombre de Taxation') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="{{count($months)}}" data-kt-dialer-step="1" id='month-dialer'>
                                    <!--begin::Decrease control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
                                        @if ($edit_mode==false)
                                        <i class="ki-outline ki-minus-circle fs-1"></i>
                                        @endif
                                    </button>
                                    <!--end::Decrease control-->
                                    <!--begin::Input control-->
                                    <input wire:model="qty" name="qty" value="{{count($months)}}" type="text" class="form-control border-0 ps-12" data-kt-dialer-control="input" placeholder="1" readonly="readonly" data-kt-action="load_invoice" />
                                    <!--end::Input control-->
                                    @error('qty')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                    <!--begin::Increase control-->
                                    <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
                                        @if ($edit_mode==false)
                                        <i class="ki-outline ki-plus-circle fs-1"></i>
                                        @endif
                                    </button>
                                    <!--end::Increase control-->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-control form-control-flush"> Mois</label>
                            </div>
                            <div class="col-md-2">
                                <label  class="required form-control form-control-flush text-end">{{ __('A compter de') }} </label>
                            </div>
                            <div class="col-md-3">

                            <div class="input-group mb-2">
                                <select wire:model="start_month" name="start_month" class="form-select form-control-select" data-dropdown-parent="#kt_modal_add_invoice">
                                    @foreach ($months as $monthNumber => $monthName)
                                        <option value="{{ $monthNumber }}" @if($start_month!=null && $monthNumber==$start_month) selected @endif>{{ $monthName }}</option>
                                    @endforeach
                                </select>

                                <span class="input-group-text" id="basic-addon1">{{" ".$year->name}}</span>
                                        </div>
                                @error('start_month')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>


                                @push('scripts')
                                    <script>
                                        // let monthDialer = document.getElementById('month-dialer');
                                        // let totalMounth = @json($months);

                                        // monthDialer.setAttribute('data-kt-dialer-min', totalMounth.length);
                                        // monthDialer.setAttribute('data-kt-dialer-max', totalMounth.length);

                                        // console.log(monthDialer);
                                    </script>
                                @endpush


                        <div class="separator separator-dashed my-2"></div>

                        @if ($taxpayer_taxables instanceof \Illuminate\Support\Collection && $taxpayer_taxables->count() > 0)

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

                                    @foreach($taxpayer_taxables as $taxpayer_taxable)
                                    <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                        <td class="pe-7">
                                            <input type="hidden" wire:model="taxpayer_taxable_id.{{ $loop->index }}" name="taxpayer_taxable_id[]" name="taxpayer_taxable_id" class="form-control form-control-solid mb-2" />

                                            <div class="form-floating">
                                                <input type="text" wire:model="taxpayer_taxable.{{ $loop->index }}" name="taxpayer_taxable[]" id="taxpayer_taxable" class="form-control form-control-solid" readonly />
                                                <label for="taxpayer_taxable">{{ $taxpayer_taxable->taxable->tax_label->name ?? '' }} {{ $taxpayer_taxable->taxpayer_taxable->taxable->tax_label->name ?? '' }}</label>
                                            </div>

                                        </td>
                                        <!-- <td class="pe-7">
                                            <input type="number" class="form-control form-control-solid mb-2" name="quantity" placeholder="1" value="1" data-kt-element="quantity"/>
                                            <input type="text" class="form-control form-control-solid" name="mesure" placeholder="m3" value="m3"/>
                                        </td> -->
                                        <td class="ps-0">

                                            <div class="input-group mb-2">
                                                <input wire:model="s_seize.{{ $loop->index }}" name="s_seize[]" type="text" class="form-control form-control-solid text-end" readonly />
                                                <span class="input-group-text" id="basic-addon1">{{ $taxpayer_taxable->taxable->unit ?? '' }} {{ $taxpayer_taxable->taxpayer_taxable->taxable->unit ?? '' }}</span>
                                            </div>
                                            @if ($view_mode)
                                            <div class="input-group mb-2">
                                                <input wire:model="s_seize_e.{{ $loop->index }}" name="s_seize_e[]" type="text" class="form-control text-end" readonly />
                                                <span class="input-group-text" id="basic-addon1">{{ $taxpayer_taxable->taxable->unit ?? '' }} {{ $taxpayer_taxable->taxpayer_taxable->taxable->unit ?? '' }}</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="input-group mb-2">
                                                <input wire:model="s_tariff.{{ $loop->index }}" name="s_tariff[]" type="text" class="form-control form-control-solid text-end" placeholder="0.00" readonly />
                                                <span class="input-group-text" id="basic-addon1">{{ (isset($taxpayer_taxable->taxable->tariff_type) ? ($taxpayer_taxable->taxable->tariff_type == "FIXED" ? "" : "%") : "") ?? '' }} {{ (isset($taxpayer_taxable->taxpayer_taxable->taxable->tariff_type) ? ($taxpayer_taxable->taxpayer_taxable->taxable->tariff_type == "FIXED" ? "" : "%") : "") ?? '' }}</span>
                                            </div>
                                            @if ($view_mode)
                                            <div class="input-group mb-2">
                                                <input wire:model="s_tariff_e.{{ $loop->index }}" name="s_tariff_e[]" type="text" class="form-control text-end" placeholder="0.00" readonly />
                                                <span class="input-group-text" id="basic-addon1">{{ (isset($taxpayer_taxable->taxable->tariff_type) ? ($taxpayer_taxable->taxable->tariff_type == "FIXED" ? "" : "%") : "") ?? '' }} {{ (isset($taxpayer_taxable->taxpayer_taxable->taxable->tariff_type) ? ($taxpayer_taxable->taxpayer_taxable->taxable->tariff_type == "FIXED" ? "" : "%") : "") ?? '' }}</span>
                                            </div>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            <input wire:model="qty" name="qty" class="form-control form-control-solid mb-2" type="number" min="1" placeholder="1" value=""  data-kt-action="load_invoice"/>
                                        </td> -->
                                        <td>
                                            <input wire:model="s_amount.{{ $loop->index }}" name="s_amount[]" type="text" class="form-control form-control-solid mb-2 text-end" placeholder="0.00" readonly />
                                            @if ($view_mode)
                                            <input wire:model="s_amount_e.{{ $loop->index }}" name="s_amount_e[]" type="text" class="form-control text-end" placeholder="0.00" readonly />
                                            @endif
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
                            <div>
                                <label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder=""></textarea>
                            </div>
                        </div>

                        @if ($view_mode)
                        <div class="separator separator-content separator-dashed my-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('Reduction / Annulation') }}</span>
                        </div>

                        <div class="row mb-2">

                            <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed mb-1 p-2">
                                <div class="col-md-3">
                                    <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Choisir une option') }}" readonly />
                                </div>
                                <div class="col-md-3">
                                    <select wire:model="cancel_reduct" name="cancel_reduct" class="form-select form-control-select" data-dropdown-parent="#kt_modal_add_invoice">
                                        <option></option>
                                        @if ($reduce_amount > 0)
                                        <option value="REDUCED">Reduction</option>
                                        @endif
                                        <option value="CANCELED">Annulation</option>
                                    </select>
                                    @error('cancel_reduct')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                @if ($reduce_amount > 0)
                                <div class="col-md-3">
                                    <input type="text" class="required form-control form-control-flush text-end" placeholder="{{ __('Montant de la reduction') }}" readonly />
                                </div>
                                <div class="col-md-3">
                                    <!--end::Decrease control-->
                                    <!--begin::Input control-->
                                    <input wire:model="reduce_amount" name="reduce_amount" type="text" class="form-control text-end" readonly />
                                    <!--end::Input control-->
                                    @error('reduce_amount')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                    <!--begin::Increase control-->


                                </div>
                                @endif
                            </div>

                        </div>
                        @else
                        <div class="separator separator-content separator-dashed mt-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('payment history') }}</span>
                        </div>

                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                            <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                <td class="pe-5">
                                    <input type="text" class="form-control form-control-flush mb-2" name="" placeholder="date" />
                                    <!-- <input type="text" class="form-control form-control-solid" name="description" placeholder="Description" /> -->
                                </td>
                                <td class="ps-0">
                                    <input type="text"  class="form-control form-control-flush" name="" placeholder="Montant" />
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-flush text-end" name="" placeholder="Type de payment" />
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-flush text-end" name="" placeholder="No de quittance" data-kt-element="price" />
                                </td>
                            </tr>
                            </table>
                        @endif



                        @else
                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                            <tr data-kt-element="empty">
                                <th colspan="5" class="text-muted text-center py-10">No taxables selected</th>
                            </tr>
                        </table>
                        @endif

                        <!--end::Input group-->
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        @if ($button_mode)
                        <div class="text-center pt-5">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                            @if ($taxpayer_taxables instanceof \Illuminate\Support\Collection && $taxpayer_taxables->count() > 0)
                            <button type="submit" class="btn btn-success" data-kt-invoices-modal-action="submit">
                                <span class="indicator-label" wire:loading.remove>{{ __('submit') }}</span>
                                <span class="indicator-progress" wire:loading wire:target="submit">
                                    {{ __('please wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            @endif
                        </div>
                        @endif
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
