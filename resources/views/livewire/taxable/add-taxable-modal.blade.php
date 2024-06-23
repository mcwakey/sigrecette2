<div class="modal fade" id="kt_modal_add_taxable" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_taxable_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ __('taxables') }}</h2>
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
                <form id="kt_modal_add_taxable_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="taxable_id" name="taxable_id"/>
                    <input type="hidden" wire:model="modality" name="modality"  value="Quitance"/>

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxable_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxable_header" data-kt-scroll-wrappers="#kt_modal_add_taxable_scroll" data-kt-scroll-offset="300px">

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
                                @error('gender')
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
                                <select wire:model="periodicity" name="periodicity" class="form-select"
                                        data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="Jours">Jours</option>
                                    <option value="Mois">Mois</option>
                                    <option value="Ans">Ans</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <!--end::Input-->
                                @error('periodicity')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('unit type') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <select aria-label="Select an ID Type" data-control="select2" data-placeholder="Select an ID Type..." class="form-select"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select> -->

                                {{--
                                 <option value="Volume">{{ __('volume') }}</option>
                                  <option value="Type">{{ __('type') }}</option>
                                --}}
                                <select wire:model="unit_type" name="unit_type" class="form-select" data-dropdown-parent="#kt_modal_add_taxable">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="Superficie">{{ __('surface') }}</option>
                                    <option value="Nombre">{{ __('Nombre') }}</option>
                                </select>
                                <!--end::Input-->
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('unit') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <select aria-label="Select an ID Type" data-control="select2" data-placeholder="Select an ID Type..." class="form-select"
                                    data-dropdown-parent="#kt_modal_add_taxable">
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select> -->
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
                            <div class="col-md-3">
                                <label class=" fw-semibold fs-6 mb-2 mb-7"></label>
                                <div class="form-control form-check form-switch form-check-custom form-check-solid"  style="border: none;">
                                    <input class="form-check-input" type="checkbox"  id="flexSwitchDefault"  wire:model="use_second_formula" name="use_second_formula" value="{{$use_second_formula}}"  @if($use_second_formula) checked="checked" @endif/>
                                    <label class="form-check-label text-nowrap" for="flexSwitchDefault">
                                        {{__("Ignorer la valeur d'assiète")}}
                                    </label>
                                </div>

                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-taxables-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>{{ __('submit') }}</span>
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
