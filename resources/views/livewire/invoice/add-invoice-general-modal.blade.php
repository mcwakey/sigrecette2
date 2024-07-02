<div class="modal fade" id="kt_modal_add_invoice_general" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered" style="max-width:calc(1600px - 20px)!important;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{__("Nouveau avis sur titre")}} </h2>
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
                    <form id="kt_modal_add_invoice_general_form" class="form" action="#" wire:submit.prevent="submit">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_invoice_general_scroll"
                             data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                             data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_invoice_general_header"
                             data-kt-scroll-wrappers="#kt_modal_add_invoice_general_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->

                            <!--end::Input group-->
                            <!--begin::Permissions-->
                            <div class="fv-row">
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <span class="text-muted">Contribuable sélectionné: </span>
                                                <span class="text-primary" > {{ "NIC:  ".$taxpayer_id }}</span>
                                                <span class="text-dark"  >{{" ". $taxpayer->name }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">{{$taxpayer->name}}</h3>
                                            <div class="card-toolbar">
                                                @can('peut émettre un avis sur titre')
                                                    <button type="button" class="btn    btn-light-danger ms-auto"
                                                            data-kt-user-id="{{ $taxpayer_id }}" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_add_invoice" data-kt-action="add_invoice"
                                                            data-kt-stepper-action="submit">
                                                        <i class="ki-duotone ki-add-files fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>{{ __('create invoice') }}
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr class="fw-bold fs-6">
                                                        <th>Nom de la taxation</th>
                                                        <th>Matière Taxable</th>
                                                        <th>Dimenssion</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($taxpayer_taxables && count($taxpayer_taxables)>0)
                                                        @foreach ($taxpayer_taxables as $taxpayer_taxable)
                                                            @if($taxpayer_taxable->bill_status == "NOT BILLED")
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex flex-column">

                                                                            @if($taxpayer_taxable->invoice_id == null)
                                                                                <input
                                                                                    name="billable"
                                                                                    class="form-check-input"
                                                                                    type="checkbox"
                                                                                    value="{{ $taxpayer_taxable->billable }}"
                                                                                    data-kt-user-id="{{ $taxpayer_taxable->id }}"
                                                                                    wire:change="updateCheckbox({{ $taxpayer_taxable->id }}, $event.target.checked)"
                                                                                    @if($taxpayer_taxable->billable) checked @endif
                                                                                />
                                                                            @else
                                                                                <input
                                                                                    name="billable"
                                                                                    class="form-check-input"
                                                                                    type="checkbox"
                                                                                    value="{{ $taxpayer_taxable->billable }}"
                                                                                    data-kt-user-id="{{ $taxpayer_taxable->id }}"
                                                                                    disabled
                                                                                    @if($taxpayer_taxable->billable) checked @endif
                                                                                />
                                                                            @endif
                                                                        </div>

                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex flex-column">
                                                                            <header class="text-gray-800 mb-1">
                                                                                {{ $taxpayer_taxable->taxable->name }}
                                                                            </header>
                                                                            <span>{{ $taxpayer_taxable->taxable->tax_label->code }} -- {{ $taxpayer_taxable->taxable->tax_label->name }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="form-check-label">{{$taxpayer_taxable->seize. " ". $taxpayer_taxable->taxable->unit }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>







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
@endpush
