<x-default-layout>

    @section('title')
        Liste des rôles
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.roles.index') }}
    @endsection

    <div class="d-flex justify-content-end" style="position: relative" data-kt-stock_request-table-toolbar="base">
        <a href="#" class="ms-5 mt-1" style="position: absolute; right:30px; top:-55px;" data-bs-toggle="collapse"
            data-bs-target="#kt_tutorial_form">
            <span>
                <i class="ki-outline ki-information fs-2tx text-warning"></i>
            </span>
        </a>
    </div>

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">

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
                        <h4 class="text-gray-900 fw-bold">Tutoriel sur les <a class="fw-bold" href="#">
                                {{ __('Rôles') }}</a>
                        </h4>
                        <div class="fs-6 text-gray-700">

                            -> Clicker sur l'icon

                            <!--begin::Add role-->
                            <img src="{{ image('illustrations/sketchy-1/4.png') }}" alt="" class="w-80px h-80px"
                                style="object-fit: cover" />
                            <!--end::Add role-->
                            pour procéder a la création d'un rôle.
                        </div>
                        <div class="fs-6 text-gray-700 mt-2">
                            -> Clicker sur la commande
                            <a href="#"
                                style="margin-left: 4px;margin-right:4px;"
                                class="btn btn-outline-success btn-light btn-active-light-primary btn-sm">{{ __('view') }}</a>

                            pour accéder a la page de détail du rôle.

                        </div>
                        <div class="fs-6 text-gray-700 mt-2">
                            -> Clicker sur la commande<a href="#"
                                style="margin-left: 4px;margin-right:4px;"
                                class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                            ou
                            <a href="#"
                                style="margin-left: 4px;margin-right:4px;"
                                class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('Supprimer') }}</a>

                            pour modifier ou supprimer un rôle selon vos permissions.
                        </div>

                        <div class="fs-6 text-gray-700 mt-2">
                            -> Clicker sur la commande<a href="#"
                                style="margin-left: 4px;margin-right:4px;"
                                class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('edit') }}</a>
                            ou
                            <a href="#"
                                style="margin-left: 4px;margin-right:4px;"
                                class="btn btn-outline-success btn-light btn-active-light-primary btn-flex btn-center btn-sm">{{ __('Supprimer') }}</a>

                            pour modifier ou supprimer un rôle selon vos permissions.
                        </div>

                        <div class="fv-row mb-4 mt-4">
                            -> Clicker sur la commande
                            <a style="margin-left: 4px;margin-right:4px;" href="#"
                                id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                                class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary rotate"
                                data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                                {{ __('advanced search') }} <i
                                    class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                        class="path1"></span><span class="path2"></span></i></a>
                            lors de la modification d'un rôle pour rechercher des permissions.
                        </div>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Notice-->

            <div class="separator separator-dashed mt-5 mb-5"></div>
        </div>

        <livewire:permission.role-list></livewire:permission.role-list>
    </div>
    <!--end::Content container-->

    <!--begin::Modal-->
    <livewire:permission.role-modal></livewire:permission.role-modal>
    <!--end::Modal-->

</x-default-layout>
