<div class="modal fade" id="kt_modal_add_taxable" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <!--begin::Modal dialog-->
    <div  class="modal-dialog modal-dialog-centered" style="max-width:calc(1020px - 20px)!important;">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_taxable_header">
                <!--begin::Modal title-->
                @if (!$edit_mode)
                    <h2 class="fw-bold">{{ __('Nouvelle Matière Taxable') }}</h2>
                @else
                    <h2 class="fw-bold">{{ __('Modifier Matière Taxable') }}</h2>
                @endif
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-action="close_taxable_modal" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_taxable_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="taxable_id" name="taxable_id"/>
                    <input type="hidden" wire:model="modality" name="modality"  value="Quitance"/>

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxable_scroll"   data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxable_header" data-kt-scroll-wrappers="#kt_modal_add_taxable_scroll" data-kt-scroll-offset="300px">

                        <!--begin::Input group-->

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxlabel') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="tax_label_id" name="tax_label_id" class="form-select"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($tax_labels as $tax_label)
                                    <option value="{{ $tax_label->id}}">{{ $tax_label->code }} -- {{ $tax_label->name }}</option>
                                    @endforeach
                                    <!-- <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option> -->
                                </select>
                                <!--end::Input-->
                                @error('tax_label_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="separator saperator-dashed my-5"></div>

                        <div class="row mb-7">
                            <div class="col-md-12">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxable') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control " placeholder="{{  'Nom de la '.__('taxable') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('periodicity') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                @if (!$edit_mode)
                                    <select wire:model="periodicity" name="periodicity" class="form-select"
                                            data-dropdown-parent="#kt_modal_add_taxable">
                                        <option>{{ __('select an option') }}</option>
                                        <option value="Jours">Jours</option>
                                        <option value="Mois">Mois</option>
                                        <option value="Ans">Ans</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                @else
                                    <select wire:model="periodicity" name="periodicity" class="form-select"
                                        data-dropdown-parent="#kt_modal_add_taxable">
                                            <option>{{ __('select an option') }}</option>
                                            @if ($periodicity == 'Jours')
                                              <option selected  value="Jours">Jours</option>
                                              <option value="Mois">Mois</option>
                                              <option value="Ans">Ans</option>
                                              <option value="Autre">Autre</option>
                                            @elseif ($periodicity == 'Mois')
                                                <option selected  value="Mois">Mois</option>
                                                <option value="Jours">Jours</option>
                                                <option value="Ans">Ans</option>
                                                <option value="Autre">Autre</option>
                                            @elseif ($periodicity == 'Ans')
                                                <option selected  value="Ans">Ans</option>
                                                <option value="Jours">Jours</option>
                                                <option value="Mois">Mois</option>
                                                <option value="Autre">Autre</option>
                                            @else
                                                <option selected  value="Autre">Autre</option>
                                                <option value="Jours">Jours</option>
                                                <option value="Mois">Mois</option>
                                                <option value="Ans">Ans</option>
                                            @endif
                                          
                                    </select>
                                @endif
                  
                                <!--end::Input-->
                                @error('periodicity')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('unit type') }}</label>
                                <!--end::Label-->
   
                                <select wire:model="unit_type" name="unit_type" class="form-select" data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="Superficie">{{ __('surface') }}</option>
                                    <option value="Nombre">{{ __('Nombre') }}</option>
                                    <option value="Volume">{{ __('volume') }}</option>
                                </select>
                                <!--end::Input-->
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('unit') }}</label>
                                <!--end::Label-->
   
                                <input type="text" wire:model="unit" name="unit" class="form-control mb-3 mb-lg-0" placeholder="{{ __('unit') }}"/>
                                <!--end::Input-->
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">

                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('tariff type') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="tariff_type" name="tariff_type" class="form-select" data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="FIXED">{{ __('fixed') }}</option>
                                    <option value="PERCENT">{{ __('percent') }}</option>
                                </select>
                                <!--end::Input-->
                                @error('periodicity')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3 ">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('tariff') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="tariff" name="tariff" class="form-control mb-3 mb-lg-0" placeholder="{{ __('tariff') }}"/>
                                <!--end::Input-->
                                @error('tariff')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3 mt-9">
                                @if($use_second_formula)
                                    <div class="form-check form-switch form-check-custom form-check-solid" style="border: none;">
                                        <input class="form-check-input" type="checkbox" value="{{$use_second_formula}}" id="flexSwitchChecked" checked="checked"  wire:model.live.debounce.250ms="use_second_formula"/>
                                        <label class="form-check-label" for="flexSwitchChecked">
                                            {{__("Ignorer la valeur d'assiette")}}
                                        </label>
                                    </div>
                                         @else
                                    <div class="form-check form-switch form-check-custom form-check-solid" style="border: none;">
                                        <input class="form-check-input" type="checkbox"  value="{{$use_second_formula}}"  id="flexSwitchDefault"   wire:model.live.debounce.250ms="use_second_formula"/>
                                        <label class="form-check-label" for="flexSwitchDefault">
                                            {{__("Ignorer la valeur d'assiette")}}
                                        </label>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="fw-semibold fs-6 mb-2">Penalité</label>
                            <textarea  wire:model="penalty" name="description"  class="form-control" rows="2" placeholder=""></textarea>
                        </div>
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-taxables-modal-action="submit" wire:loading.attr="disabled">
                            <span class="indicator-label" wire:loading.remove >{{ __('submit') }}</span>
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
@push('scripts')
    <script>

    </script>
@endpush
