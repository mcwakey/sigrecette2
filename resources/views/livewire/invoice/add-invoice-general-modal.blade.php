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

                                <div class="d-flex align-items-center">
                                    <!--begin::Input group-->
                                    <div class="d-flex align-items-center position-relative my-1">
                                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                        <input type="text" data-kt-taxpayer-table-filter="search" class="form-control w-250px ps-13"  wire:model.live.debounce.300ms="search"
                                               placeholder="{{ __('search') }}" id="mySearchInput" />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin:Action-->
                                    <div class="d-flex align-items-center ms-5">
                                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                                           class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                                           data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                            {{ __('advanced search') }} <i
                                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    </div>


                                </div>
                                <form action="#">
                                    <div class="collapse" id="kt_advanced_search_form">
                                        <!--begin::Separator-->
                                        <!-- <div class="separator separator-dashed mt-5 mb-5"></div> -->
                                        <!--end::Separator-->
                                        <!--begin::Row-->
                                        <div class="row mb-8">
                                            <!--begin::Col-->
                                            <!-- <div class="col-xxl-6"> -->
                                            <!--begin::Col-->
                                            <div class="col-xxl-1">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('id') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchZero" />
                                            </div>
                                            <div class="col-xxl-2">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchOne" />
                                            </div>
                                            <!--begin::Col-->
                                            <div class="col-xxl-2">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('mobilephone') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchThree" />
                                            </div>
                                            <!--begin::Col-->
                                            <div class="col-xxl-2">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('canton') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchFour" />
                                            </div>
                                            <div class="col-xxl-2">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('town') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchFive" />
                                            </div>
                                            <div class="col-xxl-2">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('erea') }}</label>
                                                <input type="text" class="form-control" name="tags" id="mySearchSix" />
                                            </div>
                                            <div class="col-xxl-1">
                                                <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                                                <!-- <input type="text" class="form-control" name="tags" /> -->

                                                <!--begin::Select-->
                                                <select class="form-select" id="mySearchEight">
                                                    <option value=""></option>
                                                    @foreach ($zones as $zone)
                                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                    @endforeach
                                                </select>
                                                <!--end::Select-->
                                            </div>
                                            <!-- </div> -->
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->

                                        <div class="separator separator-dashed mt-5 mb-5"></div>
                                    </div>

                                    <div class="collapse" id="kt_tutorial_form">
                                        <!--begin::Notice-->
                                        <div class="notice d-flex bg-light-danger rounded border-warning border border-dashed p-6">
                                            <!--begin::Icon-->
                                            <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                            <!--end::Icon-->
                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-stack flex-grow-1">
                                                <!--begin::Content-->
                                                <div class="fw-semibold">
                                                    <h4 class="text-gray-900 fw-bold">Tutoriel sur <a class="fw-bold" href="#"> {{ __('taxpayers') }}</a></h4>
                                                    <div class="fs-6 text-gray-700">
                                                        -> clicker ici
                                                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed bg-light-secondary btn-outline-secondary btn-active-light-secondary mx-1 rotate"
                                                           data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                                            {{ __('advanced search') }} <i
                                                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                                                    class="path1"></span><span class="path2"></span></i></a> pour afficher le formulaire de recherche avancée.
                                                        <!-- </div>
                                                        <div class="fs-6 text-gray-700"> -->
                                                        -> clicker ici

                                                        <!--begin::Add user-->
                                                        <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal"
                                                                @can("create taxpayer") data-bs-target="#kt_modal_add_taxpayer" @endcan >
                                                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                                            {{ __('new taxpayer') }}
                                                        </button>
                                                        <!--end::Add user-->
                                                        pour faire une nouvelle demande d'approvisionnement.
                                                    </div>
                                                    <div class="fs-6 text-gray-700 mt-2">
                                                        -> utiliser le selecteur <a href="#"
                                                                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                                                    data-kt-menu-target="#kt-users-actions"
                                                                                    data-kt-menu-trigger="click"
                                                                                    data-kt-menu-placement="bottom-end">
                                                            {{ __('actions') }}
                                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                        </a>

                                                        <!--begin::Menu-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                             data-kt-menu="true" data-kt-menu-id="#kt-users-actions">
                                                            <!--begin::Menu item-->

                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('view') }}
                                                                </a>
                                                            </div>
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('edit') }}
                                                                </a>
                                                            </div>
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    {{ __('delete') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                        pour plus de controle sur le tableau en dessous selon vos permissions. -> vous pouvez clicker sur le <code>Nom du Contribuable</code> ou sur
                                                        <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-sm">{{ __('view') }}</a> pour acceder a la page de detail du contribuable.
                                                        <!-- </div>
                                                        <div class="fs-6 text-gray-700 mt-2"> -->
                                                    </div>
                                                    <div class="fs-6 text-gray-700 mt-2">
                                                        -> clicker sur <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                                                        <a href="#" class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('delete') }}</a> ou pour
                                                        pouvoir modifié ou supprimer le contribuable selon vos permissions.
                                                    </div>
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Notice-->

                                        <div class="separator separator-dashed mt-5 mb-5"></div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table  table-striped table-row-bordered gy-5 gs-7">
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

                                    {{ $taxpayers->links() }}

                                </div>
                                @if($is_set_taxpayer)
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

        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                window.LaravelDataTables['taxpayers-table'].ajax.reload();
            });
        });
    </script>
@endpush
