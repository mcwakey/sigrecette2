<a href="#" class="btn btn-light btn-active-light-success btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
{{ __('actions') }}
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ route('taxpayers.show', $taxpayer) }}" class="menu-link px-3">
        {{ __('view') }}
        </a>
    </div>
    <!--end::Menu item-->
    @can('peut modifier un contribuable')
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_taxpayer" data-kt-action="update_taxpayer">
        {{ __('edit') }}
        </a>
    </div>
    <!--end::Menu item-->
    @endcan

@if(request()->has('disable'))
    @can('peut supprimer un contribuable')
        <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer->id }}" data-kt-action="active_taxpayer">
                    {{ __('active') }}
                </a>
            </div>
            <!--end::Menu item-->
    @endcan
@else
    @can('peut supprimer un contribuable')
        <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3" data-kt-user-id="{{ $taxpayer->id }}" data-kt-action="delete_taxpayer">
                    {{ __('disable') }}
                </a>
            </div>
            <!--end::Menu item-->
        @endcan
@endif


</div>
<!--end::Menu-->
