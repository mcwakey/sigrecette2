<x-default-layout>

    @section('title')
        @if (!request()->has('disable') && request()->has('type') && request()->has('type') == 'col')
            {{ __('Liste des collecteurs') }}
        @elseif(request()->has('disable') && request()->has('type') && request()->has('type') == 'col')
            {{ __('Liste des collecteurs désactivés') }}
        @elseif(request()->has('disable'))
            {{ __('Liste des utilisateurs désactivés') }}
        @else
            {{ __('Liste des utilisateurs') }}
        @endif
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('search') }}"
                        id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                @can('peut créer un utilisateur')
                    @if (!request()->has('disable') && !request()->has('type'))
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_user">
                                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                {{ __('Créer un utilisateur') }}
                            </button>
                            <!--end::Add user-->
                        </div>
                        <!--end::Toolbar-->
                    @endif
                @endcan
                @can('peut créer un utilisateur')
                    @if (!request()->has('disable') && request()->has('type'))
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_user_collector">
                                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                {{ __('Créer un collecteur') }}
                            </button>
                            <!--end::Add user-->
                        </div>
                        <!--end::Toolbar-->
                    @endif
                @endcan

                <!--begin::Modal-->
                <livewire:user.add-user-modal></livewire:user.add-user-modal>
                <livewire:user.add-user-collector-modal></livewire:user.add-user-collector-modal>
                <!--end::Modal-->
                @if (!request()->has('disable'))
                <!--begin::Tuto-->
                <div class="d-flex justify-content-end" data-kt-stock_request-table-toolbar="base">
                    <a href="#" class="ms-5 mt-1" data-bs-toggle="collapse" data-bs-target="#kt_tutorial_form">
                        <span>
                            <i class="ki-outline ki-information fs-2tx text-warning"></i>
                        </span>
                    </a>
                </div>
                <!--end::Tuto-->
                @endif

            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">

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
                            <h4 class="text-gray-900 fw-bold">Tutoriel sur <a class="fw-bold" href="#">
                                    {{ __('Utilisateurs') }}</a>
                            </h4>
                            <div class="fs-6 text-gray-700">

                                -> Clicker sur le boutton

                                <!--begin::Add user-->
                                <button type="button" class="btn btn-light-success h-45px ms-auto"
                                    data-bs-toggle="modal">
                                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                    {{ __('add_user') }}
                                </button>
                                <!--end::Add user-->
                                pour procéder a la création d'un utilisateur.
                            </div>
                            <div class="fs-6 text-gray-700 mt-2">
                                -> Clicker sur le selecteur
                                <a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                    data-kt-menu-target="#kt-users-actions" data-kt-menu-trigger="click"
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
                                            {{ __('Désactiver') }}
                                        </a>
                                    </div>
                                </div>
                                pour plus de controle sur le tableau en dessous selon vos permissions.
                                <br> -> Clicker sur le <span>nom de l'utilisateur</span> ou sur
                                <a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-sm">{{ __('view') }}</a>

                                disponible dans le sélecteur <a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                    data-kt-menu-target="#kt-users-actions" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    {{ __('actions') }}
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                pour accéder a la page de l'utilisateur.

                            </div>
                            <div class="fs-6 text-gray-700 mt-2">
                                -> Clicker sur la commande<a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                                ou
                                <a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('désactiver') }}</a>
                                disponible dans le sélecteur <a href="#"
                                    class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                    data-kt-menu-target="#kt-users-actions" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    {{ __('actions') }}
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                pour pouvoir modifier ou désactiver l'utilisateur selon vos permissions.
                            </div>
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Notice-->

                <div class="separator separator-dashed mt-5 mb-5"></div>
            </div>
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['users-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                    $('#kt_modal_add_user').modal('hide');
                    window.LaravelDataTables['users-table'].ajax.reload();
                });

                Livewire.on('error', function() {
                    $('#kt_modal_add_user').modal('hide');
                });
            });
        </script>
    @endpush

</x-default-layout>
