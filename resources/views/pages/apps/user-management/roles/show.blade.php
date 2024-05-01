<x-default-layout>

    @section('title')
        Roles
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.roles.show', $role) }}
    @endsection

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2 class="mb-0">{{ ucwords(__($role->name)) }}</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Permissions-->
                        <div class="d-flex flex-column text-gray-600">
                            @foreach ($role->permissions->shuffle()->take(5) as $permission)
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span>
                                    {{ ucfirst(__($permission->name)) }}
                                </div>
                            @endforeach
                            @if ($role->permissions->count() > 5)
                                <div class="d-flex align-items-center py-2">
                                    <span class='bullet bg-primary me-3'></span>
                                    <em>et {{ $role->permissions->count() - 5 }} plus...</em>
                                </div>
                            @endif
                            @if ($role->permissions->count() === 0)
                                <div class="d-flex align-items-center py-2">
                                    <span class='bullet bg-primary me-3'></span>
                                    <em>{{ __('Acune permission assignée') }}...</em>
                                </div>
                            @endif
                        </div>
                        <!--end::Permissions-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer pt-0">
                        @if (
                            $role->user_id === 0 &&
                                auth()->user()->hasAnyRole(['administrateur_system', 'administrateur']))
                            @can('peut modifier un rôle')
                                <button type="button" class="btn btn-light btn-active-primary"
                                    data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_role">
                                    Modifier
                                </button>
                            @endcan
                        @elseif (
                            $role->user_id === null &&
                                auth()->user()->hasAnyRole(['administrateur_system', 'administrateur']))
                            @can('peut modifier un rôle')
                                <button type="button" class="btn btn-light btn-active-primary"
                                    data-role-id="{{ $role->name }}" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_role">
                                    Modifier
                                </button>
                            @endcan
                        @endif

                        @if ($role->user_id === 0 && auth()->user()->hasRole('administrateur_system'))
                            @can('peut supprimer un rôle')
                                <button type="button" class="btn btn-light btn-active-light-danger my-1"
                                    data-kt-role-id="{{ $role->id }}" data-kt-action="delete_role">Supprimer</button>
                            @endcan
                        @elseif (
                            $role->user_id === null &&
                                auth()->user()->hasanyrole(['administrateur_system', 'administrateur']))
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
            <!--end::Sidebar-->
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-10">
                <!--begin::Card-->
                <div class="card card-flush mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header pt-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2 class="d-flex align-items-center">
                                {{ __('users assigned') }}
                                <span class="text-gray-600 fs-6 ms-1">({{ $role->users->count() }})</span>
                            </h2>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            {{ $dataTable->table() }}
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Layout-->
    </div>
    <!--end::Content container-->

    <!--begin::Modal-->
    <livewire:permission.role-modal></livewire:permission.role-modal>
    <!--end::Modal-->

    @push('scripts')
        {{ $dataTable->scripts() }}


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
                    window.location.href = '/user-management/roles';
                });
            });
        </script>
    @endpush

</x-default-layout>
