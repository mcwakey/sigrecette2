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
                                <div class="d-flex align-items-center position-relative my-1">
                                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                    <input type="text" data-kt-taxpayer-table-filter="search" class="form-control ps-13" wire:model.live="search"
                                           placeholder="{{ __('search')." un contribuable" }}" id="mySearchInput" />
                                </div>
                                <!--end::Label-->
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    @if(count($taxpayers) > 0)
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6">
                                                <th>Name</th>
                                                <th>Genre</th>
                                                <th>Téléphone</th>
                                                <th>Canton</th>
                                                <th>Addresse</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($taxpayers as $taxpayer)
                                                <tr wire:key="{{ $taxpayer->id }}" wire:click="select_taxpayer({{ $taxpayer->id }})">
                                                    <td class="d-flex align-items-center text-uppercase">
                                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxpayer->name) }}">
                                                                {{ substr($taxpayer->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <!--end::Avatar-->
                                                        <!--begin::User details-->
                                                        <div class="d-flex flex-column">
                                                            <span  class="text-gray-800 text-hover-primary mb-1">
                                                                {{ $taxpayer->name }}
                                                            </span>
                                                            <span>{{ $taxpayer->id }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $taxpayer->gender }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <div class="text-gray-800 mb-1">
                                                                @if($taxpayer->telephone)
                                                                    {{ $taxpayer->mobilephone }} / {{ $taxpayer->telephone }}
                                                                @else
                                                                    {{ $taxpayer->mobilephone }}
                                                                @endif
                                                            </div>
                                                            <span>{{ $taxpayer->email }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $taxpayer->town?->canton?->name }}</td>
                                                    <td>{{ $taxpayer->address }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                @if($taxpayer)
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <span class="text-muted">Contribuable sélectionné: </span>
                                                <span class="text-primary"> {{ "NIC:  ".$taxpayer->id }}</span>
                                                <span class="text-dark">{{" ". $taxpayer->name }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                @endif
                            <!--begin::Label-->
                                @if($taxpayer)
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">{{$taxpayer->name}}</h3>
                                            <div class="card-toolbar">
                                                @can('peut créer une taxation')
                                                    <button type="button" class="btn  btn-sm  btn-light-success ms-auto me-5"
                                                            data-kt-user-id="{{$taxpayer_id }}" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_add_taxpayer_taxable"
                                                            data-kt-action="add_taxpayer_taxable">
                                                        <i class="ki-duotone ki-add-files fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>{{ __('create asset') }}
                                                    </button>


                                                @endcan
                                                @can('peut émettre un avis sur titre')
                                                    <button type="button" class="btn  btn-sm  btn-light-danger ms-auto"
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
                                                        <th>Dimesion</th>
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
