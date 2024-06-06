<div class="modal fade" id="kt_modal_add_payment_general" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{__("Enregistrer un paiement")}} </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        {!! getIcon('cross', 'fs-1') !!}
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_payment_general_form" class="form" action="#" wire:submit.prevent="submit">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_payment_general_scroll"
                             data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                             data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_payment_general_header"
                             data-kt-scroll-wrappers="#kt_modal_add_payment_general_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->

                            <!--end::Input group-->
                            <!--begin::Permissions-->
                            <div class="fv-row">
                                <div class="d-flex align-items-center position-relative my-1">
                                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                    <input type="text" data-kt-taxpayer-table-filter="search" class="form-control ps-13" wire:model.live="search"
                                           placeholder="{{ __('search')." un avis" }}" id="mySearchInput" />
                                </div>
                                <div class="table-responsive">
                                    @if(count($invoices) > 0)
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6">
                                                <th>N° d'avis</th>
                                                <th>Montant</th>
                                                <th>Montant payé</th>
                                                <th>Reste</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($invoices as $invoice)
                                                @if($invoice->canGetPayment()&&$invoice->get_remains_to_be_paid()!=0 && $invoice->get_remains_to_be_paid()!="-")
                                                    <tr wire:key="{{ $invoice->id }}" wire:click="select_invoice({{  $invoice->id }})">
                                                        <td>
                                                            <div class="d-flex flex-column">

                                                                <header class="text-gray-800 mb-1">
                                                                    {{  $invoice->invoice_no }}
                                                                </header>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <header class="text-gray-800 mb-1">
                                                                    {{$invoice->amount }}
                                                                </header>
                                                                <span></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span >{{ $invoice::getPaid($invoice->invoice_no)}}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ $invoice->get_remains_to_be_paid()}}</span>
                                                        </td>
                                                    </tr>

                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                @if( $invoice)
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <span class="text-muted">Avis sélectionné: </span>
                                                <span class="text-primary"> {{ "NO d'avis:  ".$invoice->invoice_no }}</span>
                                                <span class="text-dark">{{" ". $invoice->amount }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                @endif
                            <!--begin::Label-->
                                @if($invoice)
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">{{$invoice->invoice_no}}</h3>
                                            <div class="card-toolbar">
                                                @can('peut ajouter un paiement')
                                                    <button type="button" class="btn  btn-sm  btn-light-success ms-auto me-5"
                                                            data-kt-user-id="{{$invoice_id }}" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_add_payment" data-kt-action="update_payment">
                                                        <i class="ki-duotone ki-add-files fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i> {{ __('create payment') }}
                                                    </button>


                                                @endcan
                                                @can('peut émettre un avis')
                                                    <button type="button" class="btn  btn-sm  btn-light-danger ms-auto"
                                                            data-kt-user-id="{{ $invoice_id }}" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_add_invoice" data-kt-action="update_invoice"
                                                            data-kt-stepper-action="submit">
                                                        <i class="ki-duotone ki-add-files fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i> {{ __('reduction cancelation') }}
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr class="fw-bold fs-6">
                                                        <th>N° d'avis</th>
                                                        <th>Montant</th>
                                                        <th>Montant payé</th>
                                                        <th>Reste</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex flex-column">

                                                                <header class="text-gray-800 mb-1">
                                                                    {{  $invoice->invoice_no }}
                                                                </header>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <header class="text-gray-800 mb-1">
                                                                    {{$invoice->amount }}
                                                                </header>
                                                                <span></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span >{{ $invoice::getPaid($invoice->invoice_no)}}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ $invoice->get_remains_to_be_paid()}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                @endif


                            </div>




                            <!--end::Permissions-->
                        </div>
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-6 pb-8">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                                    wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <script>

    </script>
@endpush
