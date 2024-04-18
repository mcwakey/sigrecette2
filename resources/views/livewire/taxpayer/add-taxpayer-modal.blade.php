<div class="modal fade" id="kt_modal_add_taxpayer" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <!-- <div class="modal-dialog  mw-1800px"> -->
        <div class="modal-dialog modal-dialog-centered modal-fullscreen ">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_taxpayer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">{{ __('new taxpayer') }}</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            {!! getIcon('cross','fs-1') !!}
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body px-5 mb-5">
                        <!--begin::Form-->
                        <div>
                        <!--begin::Information-->
                            <div class="d-flex align-items-center rounded py-5 px-5 bg-light-danger ">
                                <i class="ki-duotone ki-information-5 fs-3x text-dager me-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>    <!--begin::Description-->
                                <div class="text-gray-700 fw-bold fs-6">
                                Tous les champs marque par <code>*</code> sont obligatoire! Veiller remplir le formulaire judicieusement et avec le plus d information possible. Clicker sur <button class="btn btn-success btn sm mx-3" wire:loading.attr="disabled">{{ __('submit') }}</button> en bas de la page, pour sauvegarder les informations du contribuable.
                                </div>    <!--end::Description-->
                            </div>
                            <!--end::Information-->
                        </div>

                        <form id="kt_modal_add_taxpayer_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                            <input type="hidden" type="text" wire:model="taxpayer_id" name="taxpayer_id" />
                            <!--begin::Scroll-->
                            <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxpayer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxpayer_header" data-kt-scroll-wrappers="#kt_modal_add_taxpayer_scroll" data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                {{--
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="d-block fw-semibold fs-6 mb-5">{{ __('avatar') }}</label>
                                    <!--end::Label-->
                                    <!--begin::Image placeholder-->
                                    <style>
                                        .image-input-placeholder {
                                            background-image: url('{{ image(' svg/files/blank-image.svg') }}');
                                        }

                                        [data-bs-theme="dark"] .image-input-placeholder {
                                            background-image: url('{{ image(' svg/files/blank-image-dark.svg') }}');
                                        }
                                    </style>
                                    <!--end::Image placeholder-->
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline image-input-placeholder {{ $avatar || $saved_avatar ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                                        <!--begin::Preview existing avatar-->
                                        @if($avatar)
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $avatar ? $avatar->temporaryUrl() : '' }});"></div>
                                        @else
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $saved_avatar }});"></div>
                                        @endif
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Label-->
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                            {!! getIcon('pencil','fs-7') !!}
                                            <!--begin::Inputs-->
                                            <input type="file" wire:model="avatar" name="avatar" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Cancel-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                            {!! getIcon('cross','fs-2') !!}
                                        </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                            {!! getIcon('cross','fs-2') !!}
                                        </span>
                                        <!--end::Remove-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">{{ __('allowed file') }}</div>
                                    <!--end::Hint-->
                                    @error('avatar')
                                    <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                --}}
                                <!--end::Input group-->
                                <!--begin::Input group-->


                                <div class="separator separator-content mb-7 mt-7">
                                    <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('taxpayer info') }}</span>
                                </div>

                                <div class="row mb-7">
                                    <div class="col-md-4">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="name" name="name" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('fullname') }}" />
                                        <!--end::Input-->
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('gender') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select wire:model="gender" name="gender" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($genders as $gender)
                                            <option value="{{ $gender->name}}">{{ $gender->name }}</option>
                                            @endforeach
                                            <!-- <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option> -->
                                        </select>
                                        <!--end::Input-->
                                        @error('gender')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('id type') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <!-- <select aria-label="Select an ID Type" data-control="select2" data-placeholder="Select an ID Type..." class="form-select "
                                            data-dropdown-parent="#kt_modal_add_taxpayer">
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                        </select> -->

                                        <select wire:model="id_type" name="id_type" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer" data-allow-clear="true">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($id_types as $id_type)
                                            <option value="{{ $id_type->name}}">{{ $id_type->name }}</option>
                                            @endforeach
                                            <!-- <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="3">In Process</option>
                                            <option value="4">Rejected</option> -->
                                        </select>
                                        <!--end::Input-->
                                        @error('id_type')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('id number') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="id_number" name="id_number" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('id number') }}" />
                                        <!--end::Input-->
                                        @error('id_number')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <!-- <div class="notice"> -->
                                        <div class="col-md-3">

                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">{{ __('mobilephone') }}</label>
                                            <!--end::Label-->

                                            <div class="input-group mb-5">
                                                <span class="input-group-text" id="basic-addon1">+228</span>
                                                <!--begin::Input-->
                                                <input type="text" wire:model="mobilephone" name="mobilephone" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('mobilephone') }}" />
                                                <!--end::Input-->
                                            </div>

                                            @error('mobilephone')
                                            <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">{{ __('telephone') }}</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" wire:model="telephone" name="telephone" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('telephone') }}" />
                                            <!--end::Input-->
                                            @error('telephone')
                                            <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-semibold fs-6 mb-2">{{ __('email') }}</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email" wire:model="email" name="email" class="form-control  mb-3 mb-lg-0" placeholder="example@domain.com" />
                                            <!--end::Input-->
                                            @error('email')
                                            <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    <!-- </div> -->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->

                                <div class="separator separator-content mb-5">
                                    <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('activity info') }}</span>
                                </div>

                                <div class="row mb-7 bg-light-warning rounded border-warning border border-dashed py-3">

                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('social work') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="social_work" name="social_work" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('social work') }}" />
                                        <!--end::Input-->
                                        @error('social_work')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('work category') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select data-kt-action="load_drop" wire:model="category_id" name="category_id" class="form-select" data-dropdown-parent="#kt_modal_add_taxpayer">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                        @error('category_id')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('work') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select wire:model="activity_id" name="activity_id" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($activities as $activity)
                                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                        @error('activity_id')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('other work') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="other_work" name="other_work" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('other work') }}" />
                                        <!--end::Input-->
                                        @error('other_work')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="separator separator-content mb-5 mt-7">
                                    <span class="w-200px text-gray-500 fw-semibold fs-7">{{ __('business info') }}</span>
                                </div>

                                <div class="row mb-7 bg-light-primary rounded border-primary border border-dashed py-3">
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('file no') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <!-- <select aria-label="" data-control="select2" data-placeholder="Select a District..." class="form-select "
                                            data-dropdown-parent="#kt_modal_add_taxpayer">
                                        </select> -->
                                        <input type="text" wire:model="file_no" name="file_no" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('file no') }}" />
                                        <!--end::Input-->
                                        @error('file_no')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('authorisation') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select wire:model="authorisation" name="authorisation" class="form-select ">
                                            <option>{{ __('select an option') }}</option>
                                            <option value="YES">{{ __('yes') }}</option>
                                            <option value="NO">{{ __('no') }}</option>
                                        </select>
                                        <!--end::Input-->
                                        @error('authorisation')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('auth reference') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="auth_reference" name="auth_reference" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('auth reference') }}" />
                                        <!--end::Input-->
                                        @error('auth_reference')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('nif') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="nif" name="nif" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('nif') }}" />
                                        <!--end::Input-->
                                        @error('nif')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="separator separator-content mb-5 mt-7">
                                    <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('location info') }}</span>
                                </div>

                                <div class="row mb-7">
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('canton') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <!-- <select aria-label="" data-control="select2" data-placeholder="Select a District..." class="form-select "
                                            data-dropdown-parent="#kt_modal_add_taxpayer">
                                        </select> -->

                                        <select data-kt-action="load_drop" wire:model="canton" name="canton" class="form-select">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($cantons as $canton)
                                            <option value="{{ $canton->id }}">{{ $canton->name }}</option>
                                            @endforeach
                                        </select>

                                        <!--end::Input-->
                                        @error('canton')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('Villages/Quartiers') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select data-kt-action="load_drop" wire:model="town_id" name="town_id" class="form-select">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                        @error('town_id')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select wire:model="zone_id" name="zone_id" class="form-select " data-dropdown-parent="#kt_modal_add_taxpayer">
                                            <option>{{ __('select an option') }}</option>
                                            @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                        @error('zone_id')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row mb-7">

                                    <div class="col-md-6">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('address') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea rows="1" wire:model="address" name="address" class="form-control  rounded-3" placeholder="{{ __('address') }}"></textarea>
                                        <!--end::Input-->
                                        @error('address')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('longitude') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="longitude" name="longitude" class="form-control  mb-3 mb-lg-0" placeholder="0.123456789" />
                                        <!--end::Input-->
                                        @error('longitude')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">{{ __('latitude') }}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" wire:model="latitude" name="latitude" class="form-control  mb-3 mb-lg-0" placeholder="0.123456789" />
                                        <!--end::Input-->
                                        @error('latitude')
                                        <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                                <button type="submit" class="btn btn-success" data-kt-taxpayers-modal-action="submit">
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
            </div>
            <!--end::Modal content-->
        </div>
    <!-- </div> -->
    <!--end::Modal dialog-->
</div>
