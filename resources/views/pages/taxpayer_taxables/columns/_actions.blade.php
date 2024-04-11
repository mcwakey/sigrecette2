<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    {{ __('actions') }}
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">
    <!--begin::Menu item-->
    <!-- <div class="menu-item px-3">
        <a href="{{ route('taxations.taxables.show', $taxpayer_taxable) }}" class="menu-link px-3">
            Aviser
        </a>
    </div> -->

    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-bs-toggle="modal"
            data-bs-target="#kt_modal_add_taxpayer_taxable" data-kt-action="update_taxable">
            {{ __('view') }}
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
        @can('peut modifier une taxation')
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer_taxable->id }}" data-bs-toggle="modal"
                data-bs-target="#kt_modal_add_taxpayer_taxable" data-kt-action="update_taxable">
                {{ __('edit') }}
            </a>
        </div>
        @endcan
        <!--end::Menu item-->

        <!--begin::Menu item-->
        @can('peut supprimer une taxation')
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer_taxable->id }}"
                data-kt-action="delete_taxpayer">
                {{ __('delete') }}
            </a>
        </div>
        @endcan
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
