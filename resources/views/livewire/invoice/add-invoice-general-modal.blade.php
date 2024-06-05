<div class="modal fade" tabindex="-1" id="kt_modal_add_invoice_general" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="col-md-1"></div>
        <div class="col-md-10">
        <form class="modal-content stepper stepper-pills" id="kt_stepper_add_invoice_general">
            <div class="modal-header px-10">
                <h3 class="modal-title">Modal title</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body px-10">
                <!--begin::Stepper-->
                <div>
                    <!--begin::Nav-->
                    <div class="stepper-nav flex-center flex-wrap mb-10">
                        <!--begin::Step 1-->
                        <div class="stepper-item mx-4 my-4 current" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                       Contribuables
                                    </h3>

                                    <div class="stepper-desc">
                                        Choisisser un contibuable
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                        <!--end::Step 1-->

                        <!--begin::Step 2-->
                        <div class="stepper-item mx-4 my-4" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                       Taxation
                                    </h3>

                                    <div class="stepper-desc">
                                        sélection les taxtion du contribables
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                        <!--end::Step 2-->

                        <!--begin::Step 3-->
                        <div class="stepper-item mx-4 my-4" data-kt-stepper-element="nav">
                            <!--begin::Wrapper-->
                            <div class="stepper-wrapper d-flex align-items-center">
                                <!--begin::Icon-->
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">3</span>
                                </div>
                                <!--begin::Icon-->

                                <!--begin::Label-->
                                <div class="stepper-label">
                                    <h3 class="stepper-title">
                                       Avis
                                    </h3>

                                    <div class="stepper-desc">
                                        Creer l'avis
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Line-->
                            <div class="stepper-line h-40px"></div>
                            <!--end::Line-->
                        </div>
                    </div>
                    <!--end::Nav-->

                    <!--begin::Group-->
                    <div class="px-20 mb-5 scroll-y mh-300px">
                        <!--begin::Step 1-->
                        <div class="flex-column current" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <div class="fv-row">
                                <!--begin::Label-->
                                <div class="d-flex align-items-center position-relative my-1">
                                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                    <input type="text" data-kt-taxpayer-table-filter="search" class="form-control ps-13" wire:model.live="search"
                                           placeholder="{{ __('search')." un contribuables" }}" id="mySearchInput" />
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
                                                            @if($taxpayer->profile_photo_url)
                                                                <div class="symbol-label">
                                                                    <img src="{{ $taxpayer->profile_photo_url }}" class="w-100"/>
                                                                </div>
                                                            @else
                                                                <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxpayer->name) }}">
                                                                    {{ substr($taxpayer->name, 0, 1) }}
                                                                </div>
                                                            @endif
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
                                                    <td>{{ $taxpayer->town->canton->name }}</td>
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
                                                <span class="text-primary"> ID: {{ $taxpayer->id }}</span>
                                                <span class="text-dark">{{" ". $taxpayer->name }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <div class="fv-row">
                                <!--begin::Label-->
                                @if($taxpayer)
                                    <div class="card card-dashed mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">{{$taxpayer->name}}</h3>
                                            <div class="card-toolbar">
                                                @can('peut créer une taxation')
                                                    <button type="button" class="btn  btn-sm  btn-light-success ms-auto me-5"
                                                            data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal"
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

                                                @can('peut émettre un avis')
                                                    <button type="button" class="btn  btn-sm btn-light-danger ms-auto"
                                                            data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_add_invoice" data-kt-action="add_invoice">
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
                                                    @foreach ($taxpayer->taxpayer_taxables as $taxpayer_taxable)
                                                        @if($taxpayer_taxable->bill_status == "NOT BILLED")
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex flex-column">
                                                                        <form class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                                                                            @if($taxpayer_taxable->invoice_id == null)
                                                                                @if($taxpayer_taxable->billable == 1)
                                                                                    <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox" checked />
                                                                                @else
                                                                                    <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox"/>
                                                                                @endif
                                                                            @else
                                                                                <input name="billable" class="form-check-input" type="checkbox" value="{{ $taxpayer_taxable->billable }}" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-kt-action="update_checkbox" disabled />
                                                                            @endif
                                                                        </form>
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
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>

                                 @endif
                            </div>

                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label d-flex align-items-center">
                                    <span class="required">Input 1</span>
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input1" placeholder="" value=""/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Input 2
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input2" placeholder="" value=""/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Step 1-->
                    </div>
                    <!--end::Group-->
                </div>
                <!--end::Stepper-->
            </div>

            <div class="modal-footer px-10 d-flex flex-stack">
                <!--begin::Wrapper-->
                <div class="me-2">
                    <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                        Back
                    </button>
                </div>
                <!--end::Wrapper-->

                <!--begin::Wrapper-->
                <div>
                    <button type="button" class="btn btn-primary" data-kt-stepper-action="submit">
                        <span class="indicator-label">
                            Submit
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    @if($this->is_set_taxpayer)
                        @can('peut émettre un avis')
                            <button type="button" class="btn  btn-sm btn-light-danger ms-auto"
                                    data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_invoice" data-kt-action="add_invoice">
                                <i class="ki-duotone ki-add-files fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>{{ __('create invoice') }}
                            </button>
                        @endcan
                    @endif


                    <button type="button" class="btn btn-primary" data-kt-stepper-action="next"  @if(!$this->is_set_taxpayer) disabled @endif >
                        Continue
                    </button>
                </div>
                <!--end::Wrapper-->
            </div>
        </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>

    </script>
@endpush
