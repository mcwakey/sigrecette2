<div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Role</h2>
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
                <form id="kt_modal_update_role_form" class="form" action="#" wire:submit="submit">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                        data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header"
                        data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">Nom du role</span>
                            </label>
                            <!--end::Label-->
                            @if ($role?->user_id === 0)
                                <!--begin::Input-->
                                <input readonly class="form-control form-control" placeholder="Entrer le nom du role"
                                    name="name" wire:model="name" />
                                <!--end::Input-->
                            @else
                                <!--begin::Input-->
                                <input class="form-control form-control" placeholder="Entrer le nom du role"
                                    name="name" wire:model="name" />
                                <!--end::Input-->
                            @endif

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--end::Input group-->
                        <!--begin::Permissions-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">Permissions du role</label>

                            <div class="fv-row mb-4 mt-4">
                                <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                                    class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                                    data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                    {{ __('advanced search') }} <i
                                        class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                            class="path1"></span><span class="path2"></span></i></a>
                            </div>

                            <div class="collapse" id="kt_advanced_search_form">
                                <div class="d-flex align-items-center position-relative mt-1 mb-4">
                                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                                    <input type="text" data-kt-taxpayer_invoices-table-filter="search"
                                        class="form-control w-100 ps-13"
                                        placeholder="{{ __('Rechercher une permission.') }}" id="auto-complete-input" />
                                </div>
                            </div>

                            <!--end::Label-->
                            <!--begin::Table wrapper-->
                            <div class="table-responsive">
                                <!--begin::Table-->

                                <div class="d-flex w-100 mb-4" id="select-all">
                                    <label class="form-check my-4 form-check-sm form-check-custom form-check me-9">
                                        <input class="form-check-input" type="checkbox" id="kt_roles_select_all"
                                            wire:model="check_all" wire:change="checkAll" />
                                        <span class="form-check-label" for="kt_roles_select_all">
                                            Séléctionner tout
                                        </span>
                                    </label>
                                </div>

                                <div id="auto-complete">
                                    <!--end::Table row-->
                                    @foreach ($permissions_by_group as $group => $permissions)
                                        <div class="d-flex justify-content-between w-100 mb-6">

                                            <!--begin::Table row-->
                                            <!--begin::Label-->
                                            <span class="text-gray-800 fw-bolder">{{ ucfirst(__($group)) }}</span>
                                            <!--end::Label-->
                                            <!--begin::Input group-->
                                            @foreach ($permissions as $permission)
                                                <!--begin::Wrapper-->
                                                <div class="d-flex">
                                                    <!--begin::Checkbox-->
                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check me-5 me-lg-20">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model="checked_permissions"
                                                            value="{{ $permission->name }}" />
                                                        <span
                                                            class="form-check-label">{{ ucfirst(__(Str::before($permission->name, ' '))) }}</span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                </div>
                                                <!--end::Wrapper-->
                                            @endforeach
                                            <!--end::Input group-->
                                            <!--end::Table row-->
                                        </div>
                                    @endforeach
                                    <!--begin::Table row-->
                                </div>


                            </div>

                            <!--end::Table body-->
                            <!--end::Table-->
                        </div>
                        <!--end::Table wrapper-->
                    </div>
                    <!--end::Permissions-->
            </div>
            <!--end::Scroll-->
            <!--begin::Actions-->
            <div class="text-center pt-6 pb-8">
                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                    wire:loading.attr="disabled">{{ __('cancel') }}</button>
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label" wire:loading.remove>{{ __('submit') }}</span>
                    <span class="indicator-progress" wire:loading wire:target="submit">
                        {{ __('please wait...') }}
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
        const modal = document.querySelector('#kt_modal_update_role');

        modal.addEventListener('show.bs.modal', (e) => {
            Livewire.dispatch('modal.show.role_name', [e.relatedTarget.getAttribute('data-role-id')]);
        });
    </script>


    <script>
        let permissionsByGroup = @json($permissions_by_group);
        let permissions = Object.keys(permissionsByGroup);
        let autoCompleteBox = document.getElementById('auto-complete');
        let autoCompleteInput = document.getElementById('auto-complete-input');
        let selectAll = document.getElementById('select-all');

        autoCompleteInput.addEventListener('input', (e) => {
            e.preventDefault();
            let value = e.target.value.toLowerCase();
            let results = []; // Tableau pour stocker les résultats de recherche

            permissions.forEach(permission => {
                if (permission.toLowerCase().includes(value) && !results.includes(permission)) {
                    results.push(
                        permission
                    ); // Ajouter la permission au tableau des résultats si elle correspond à la valeur de recherche et n'est pas déjà présente
                }
            });

            if (!value) {
                selectAll.classList.replace('d-none', 'd-flex');
                autoCompleteBox.classList.remove('mt-4');
            }

            autoCompleteBox.innerHTML = '';

            if (results.length > 0) {
                // S'il y a des résultats, remplir la div "auto-complete" avec le template des résultats
                results.forEach(permission => {
                    let template = `
                    <div class="d-flex justify-content-between w-100 mb-6">
                        <span class="text-gray-800 fw-bolder">${permission.charAt(0).toUpperCase() + permission.slice(1)}</span>
                            <div class="d-flex">
                                <label
                                    class="form-check form-check-sm form-check-custom form-check me-5 me-lg-20">
                                    <input class="form-check-input" type="checkbox"
                                        wire:model="checked_permissions"
                                        value="${permissionsByGroup[permission][0]['name'].split(' ')[0] + ' ' + permission }" />
                                    <span
                                        class="form-check-label">${permissionsByGroup[permission][0]['name'].split(' ')[0]}</span>
                                </label>
                            </div>
                        </div>
            `;
                    autoCompleteBox.innerHTML += template;
                    if (value) {
                        selectAll.classList.replace('d-flex', 'd-none');
                        autoCompleteBox.classList.add('mt-4');
                    }
                });


            } else {
                // S'il n'y a pas de résultats, afficher "Aucun résultat"
                autoCompleteBox.innerHTML = "<p class='fw-bolder'>Aucun résultat</p>";
            }

        });
    </script>
@endpush
