<div class="modal fade" id="kt_modal_add_stock_transfer-deposit" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_stock_transfer-deposit_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('Versement du Collecteur') }}</h2>
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
                <form id="kt_modal_add_stock_transfer-deposit_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <!-- <input type="text" wire:model="stock_transfer_id" name="stock_transfer_id"  value=""/> -->
                    <input type="text" wire:model="user_id" name="user_id" hidden value=""/>
                    <!-- <input type="text" wire:model="collector_id" name="collector_id" value=""/> -->
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_stock_transfer-deposit_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_stock_transfer-deposit_header" data-kt-scroll-wrappers="#kt_modal_add_stock_transfer-deposit_scroll" data-kt-scroll-offset="300px">

                        <!--begin::Input group-->

                        @if (!$edit_mode)

                        <div class="row mb-7">
                            <div class="col-md-8">

                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('collector') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->

                                <select data-kt-action="load_drop" wire:model="collector_id" name="collector_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer" disabled>
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($collectors as $collector)
                                    <option value="{{ $collector->id }}">{{ $collector->user_name }}</option>
                                    @endforeach
                                </select>


                                <!--end::Input-->
                                @error('collector_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">

                            <label class="required fs-6 fw-semibold mb-2">{{ __('req no') }}</label>

                            <select data-kt-action="load_drop" wire:model.live="trans_no" name="trans_no" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer">
                                <option>{{ __('select an option') }}</option>
                                @foreach($request_nos as $request_no)
                                <option value="{{ $request_no->trans_no}}">{{ $request_no->trans_no}}</option>
                                @endforeach
                            </select>

                                <!-- <input  data-kt-action="load_drop" type="text" wire:model.live="trans_no" name="trans_no" class="form-control mb-3 mb-lg-0" placeholder="{{ __('req no') }}" readonly/> -->
                                <!--end::Input-->
                                @error('trans_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('tickets') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->

                                <select data-kt-action="load_drop" wire:model="stock_transfer_id" name="stock_transfer_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer-deposit">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($stock_transfers as $stock_transfer)
                                        <option value="{{  $stock_transfer->id}}">{{ $stock_transfer->taxable->name." (".$stock_transfer->taxable->tariff ." FCFA) [No: ". $stock_transfer->start_no."-". $stock_transfer->end_no."]" }}</option>
                                    @endforeach
                                </select>

                                @error('taxable_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label  class="required fs-6 fw-semibold mb-2">{{ __('Quantité restante') }} </label>
                                <input  data-kt-action="load_drop" type="text" wire:model.live="remaining_qty" name="remaining_qty" class="form-control mb-3 mb-lg-0" placeholder="{{ __('0') }}" readonly/>
                            </div>
                        </div>

                        <div class="separator saperator-dashed my-3"></div>

                        <div class="row mb-5">
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('start no') }}</label>
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
                                <input type="text" wire:model="qty" name="qty" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('qty') }}" data-kt-action="change_qty"/>
                                <!--end::Input-->
                                @error('qty')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('total') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="total" name="total" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('total') }}" readonly />
                                <!--end::Input-->
                                @error('qty')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">

                            @if ($deposit_mode)
                            <div class="col-md-9">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('code') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <input type="text" wire:model="code" name="code" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('code') }}" data-kt-action="change_qty" /> -->
                                <select wire:model="code" name="code" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer-deposit">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxlabel_list as $taxlabel)<option value="{{ $taxlabel->code}}">{{ $taxlabel->code." -- ".$taxlabel->name }}</option>@endforeach

                                </select>
                                <!--end::Input-->
                                @error('code')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @endif

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
                        @endif

                        <div class="separator separator-content separator-dashed my-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('Résumé du stock') }}</span>
                        </div>

                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-50px">{{ __('ticket') }}</th>
                                                <th class="min-w-50px">{{ __('tariff') }}</th>
                                                <th class="min-w-50px">{{ __('qty') }}</th>
                                                <th class="min-w-50px">{{ __('amount') }}</th>
                                                <th class="min-w-50px">{{ __('num') }}</th>
                                                <th class="min-w-50px">{{ __('reference no') }}</th>
                                                <th class="min-w-50px">{{ __('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                        @if( $stock_transfers_v)
                                            @foreach( $stock_transfers_v as $stock_transfer)
                                                <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                                    <td>
                                                        {{ $stock_transfer->taxable->name }}
                                                    </td>
                                                    <td class="ps-0">
                                                        {{ $stock_transfer->taxable->tariff }}
                                                    </td>
                                                    <td>
                                                        {{ $stock_transfer->qty }}
                                                    </td>
                                                    <td>
                                                        {{ $stock_transfer->qty*$stock_transfer->taxable->tariff }}
                                                    </td>
                                                    <td>
                                                        {{ $stock_transfer->start_no." - ".$stock_transfer->end_no }}
                                                    </td>
                                                    <td>

                                                        {{ $stock_transfer->payment?->reference }}
                                                    </td>
                                                    <td>

                                                <button type="button"   class="btn btn-sm btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger me-1" wire:click="deleteStockTransfer({{ $stock_transfer->id }})">

                                                    <span class="indicator-label">
                                                          <span class="indicator-label">
                                                        <i class="ki-duotone ki-trash">
                                                             <span class="path1"></span>
                                                             <span class="path2"></span>
                                                             <span class="path3"></span>
                                                             <span class="path4"></span>
                                                             <span class="path5"></span>
                                                            </i>
                                                    </span>
                                                    </span>
                                                </button>
                                            </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                            </table>

                        <!--end::Input group-->

                        <div class="separator separator-content separator-dashed my-3 mb-8 mt-10">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('reference no') }}</span>
                        </div>

                        <div class="row mb-7">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                                <!--begin::Label-->
                                <!--end::Label-->
                                <!--begin::Input-->

                                <input type="text" wire:model="taxlabel_id" name="taxlabel_id" class="form-control mb-3 mb-lg-0" placeholder="{{ __('reference no') }}"/>

                                <!-- <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer-deposit">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select> -->
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

<div class="col-md-3">
    <!--begin::Label-->
    <!-- <label class="fw-semibold fs-6 mb-2">{{ __('empty') }}.</label> -->
    <!--end::Label-->
    <!--begin::Input-->
    <button type="submit" class="btn btn-warning" data-kt-taxpayer-taxables-modal-action="submit">
        <span class="indicator-label" wire:loading.remove>{{ __('apply') }}</span>
        <span class="indicator-progress" wire:loading wire:target="submit">
        {{ __('chargenment ...') }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>

    <!--end::Input-->
</div>
                                </div>
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-10">
                    <button type="reset" class="btn btn-light me-5" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('close') }}</button>
                      @if ($edit_mode)
                                <button type="submit" class="btn btn-danger" data-kt-taxpayer-taxables-modal-action="submit">
                                    <span class="indicator-label" wire:loading.remove>{{ __('faire etat de compte du collecteur') }}</span>
                                    <span class="indicator-progress" wire:loading wire:target="submit">
                                    {{ __('chargenment ...') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            @endif
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
