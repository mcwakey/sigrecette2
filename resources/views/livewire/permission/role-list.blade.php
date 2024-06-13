<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">

    @can("peut créer un rôle")
    <!--begin::Add new card-->
    <div class="ol-md-4">
        <!--begin::Card-->
        <div class="card h-md-100">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-center">
                <!--begin::Button-->
                <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_update_role">
                    <!--begin::Illustration-->
                    <img src="{{ image('illustrations/sketchy-1/4.png') }}" alt=""
                        class="mw-100 mh-150px mb-7" />
                    <!--end::Illustration-->
                    <!--begin::Label-->
                    <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Créer un rôle</div>
                    <!--end::Label-->
                </button>
                <!--begin::Button-->
            </div>
            <!--begin::Card body-->
        </div>
        <!--begin::Card-->
    </div>
    <!--begin::Add new card-->
    @endcan

    @foreach ($roles as $role)
        @if ($role->name == 'administrateur_system')
            @hasanyrole(['administrateur_system'])
                <!--begin::Col-->
                <div class="col-md-4">
                    <!--begin::Card-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{ ucwords(__($role->name)) }}</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-1">
                            <!--begin::Users-->
                            <div class="fw-bold text-gray-600 mb-5">{{ __('utilisateur total avec ce role') }}:
                                {{ $role->users->count() }}</div>
                            <!--end::Users-->
                            <!--begin::Permissions-->
                            <div class="d-flex flex-column text-gray-600">
                                @foreach ($role->permissions->shuffle()->take(5) ?? [] as $permission)
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bg-primary me-3"></span>{{ ucfirst(__($permission->name)) }}
                                    </div>
                                @endforeach
                                @if ($role->permissions->count() > 5)
                                    <div class='d-flex align-items-center py-2'>
                                        <span class='bullet bg-primary me-3'></span>
                                        <em>et {{ $role->permissions->count() - 5 }} plus...</em>
                                    </div>
                                @endif
                                @if ($role->permissions->count() === 0)
                                    <div class="d-flex align-items-center py-2">
                                        <span class='bullet bg-primary me-3'></span>
                                        <em>{{ __('no permissions given') }}...</em>
                                    </div>
                                @endif
                            </div>
                            <!--end::Permissions-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer flex-wrap pt-0">
                            <a href="{{ route('user-management.roles.show', $role) }}"
                                class="btn btn-light btn-active-primary my-1 me-2">Afficher</a>

                            @if ($role->user_id === 0 && auth()->user()->hasRole('administrateur_system'))
                                @can('peut modifier un rôle')
                                    <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                        data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_role">Modifier</button>
                                @endcan
                            @elseif (
                                $role->user_id === null &&
                                    auth()->user()->hasanyrole(['administrateur_system']))
                                @can('peut modifier un rôle')
                                    <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                        data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_role">Modifier</button>
                                @endcan
                            @endif


                            @if ($role->user_id === 0 && auth()->user()->hasRole('administrateur_system'))
                                @can('peut supprimer un rôle')
                                    <button type="button" class="btn btn-light btn-active-light-danger my-1"
                                        data-kt-role-id="{{ $role->id }}" data-kt-action="delete_role">Supprimer</button>
                                @endcan
                            @elseif (
                                $role->user_id === null &&
                                    auth()->user()->hasanyrole(['administrateur_system']))
                                @can('peut supprimer un rôle')
                                    <button type="button" class="btn btn-light btn-active-light-danger my-1"
                                        data-kt-role-id="{{ $role->id }}" data-kt-action="delete_role">Supprimer</button>
                                @endcan
                            @endif
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            @endhasanyrole
        @else
            <div class="col-md-4">
                <!--begin::Card-->
                <div class="card card-flush h-md-100">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>{{ ucwords(__($role->name)) }}</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-1">
                        <!--begin::Users-->
                        <div class="fw-bold text-gray-600 mb-5">{{ __('utilisateur total avec ce role') }}:
                            {{ $role->users->count() }}</div>
                        <!--end::Users-->
                        <!--begin::Permissions-->
                        <div class="d-flex flex-column text-gray-600">
                            @foreach ($role->permissions->shuffle()->take(5) ?? [] as $permission)
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span>{{ ucfirst(__($permission->name)) }}
                                </div>
                            @endforeach
                            @if ($role->permissions->count() > 5)
                                <div class='d-flex align-items-center py-2'>
                                    <span class='bullet bg-primary me-3'></span>
                                    <em>et {{ $role->permissions->count() - 5 }} plus...</em>
                                </div>
                            @endif
                            @if ($role->permissions->count() === 0)
                                <div class="d-flex align-items-center py-2">
                                    <span class='bullet bg-primary me-3'></span>
                                    <em>{{ __('no permissions given') }}...</em>
                                </div>
                            @endif
                        </div>
                        <!--end::Permissions-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer flex-wrap pt-0">
                        <a href="{{ route('user-management.roles.show', $role) }}"
                            class="btn btn-light btn-active-primary my-1 me-2">Afficher</a>

                        @if ($role->user_id === 0 && auth()->user()->hasAnyRole(['administrateur_system', 'administrateur']))
                            @can('peut modifier un rôle')
                                <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                    data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_role">Modifier</button>
                            @endcan
                        @elseif (
                            $role->user_id === null &&
                                auth()->user()->hasAnyRole(['administrateur_system', 'administrateur']))
                            @can('peut modifier un rôle')
                                <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                    data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_role">Modifier</button>
                            @endcan
                        @endif


                        @if ($role->user_id === 0 && auth()->user()->hasRole('administrateur_system'))
                            @can('peut supprimer un rôle')
                                <button type="button" class="btn btn-light btn-active-light-danger my-1"
                                    data-kt-role-id="{{ $role->id }}" data-kt-action="delete_role">Supprimer</button>
                            @endcan
                        @elseif (
                            $role->user_id === null &&
                                auth()->user()->hasAnyRole(['administrateur_system', 'administrateur']))
                            @can('peut supprimer un rôle')
                                <button type="button" class="btn btn-light btn-active-light-danger my-1"
                                    data-kt-role-id="{{ $role->id }}" data-kt-action="delete_role">Supprimer</button>
                            @endcan
                        @endif
                    </div>
                    <!--end::Card footer-->
                </div>
                <!--end::Card-->
            </div>
        @endif
    @endforeach
</div>

@push('scripts')
    <script>
        // Initialize KTMenu
        KTMenu.init();

        // Add click event listener to delete buttons
        document.querySelectorAll('[data-kt-action="delete_role"]').forEach(function(element) {
            element.addEventListener('click', function() {
                Swal.fire({
                    text: 'Voulez-vous supprimer ce role?',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete_role', [this.getAttribute('data-kt-role-id')]);
                    }
                });
            });
        });

        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                $('#kt_modal_update_role').modal('hide');
                window.location.reload();
            });
        });
    </script>
@endpush
