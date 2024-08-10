<div class="modal fade" id="kt_modal_add_tax_label" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop='static'>
    <!--begin::Modal dialog-->
    <div  class="modal-dialog modal-dialog-centered" style="max-width:calc(1020px - 20px)!important;">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_tax_label_header">
                <!--begin::Modal title-->
                @if (!$edit_mode)
                    <h2 class="fw-bold">{{ __('new taxlabel') }}</h2>
                @else
                    <h2 class="fw-bold">{{ __('Modifier Libellé Fiscale') }}</h2>
                @endif
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-action="close_tax_label_modal" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_tax_label_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="tax_label_id" name="tax_label_id"/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_tax_label_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_tax_label_header" data-kt-scroll-wrappers="#kt_modal_add_tax_label_scroll" data-kt-scroll-offset="300px">



                        <div class="row mb-7">
                            <div class="col-md-12">
                                <label class="required fs-6 fw-semibold mb-2">{{ __('category') }}</label>

                                @foreach($allCategories as $category)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"  value="{{ $category }}" id="category_{{ $loop->index }}"  wire:model="categories" />
                                        <label class="form-check-label" for="category_{{ $loop->index }}">
                                            {{ __($category) }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('categories')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="separator saperator-dashed my-5"></div>

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('taxlabel') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control mb-3 mb-lg-0" placeholder="{{ __('taxlabel') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('code') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="code" name="code" class="form-control mb-3 mb-lg-0" placeholder="{{ __('code') }}"/>
                                <!--end::Input-->
                                @error('code')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-tax_labels-modal-action="submit">
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
