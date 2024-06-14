<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">
    @if (!request()->has('disable'))
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('user-management.users.show', $user) }}" class="menu-link px-3">
                {{ __('view') }}
            </a>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $user->id }}" data-bs-toggle="modal"
                data-bs-target="#kt_modal_add_user" data-kt-action="update_row">
                {{ __('edit') }}
            </a>
        </div>
        <!--end::Menu item-->
    @endif

    @if ($user->deleted_at)
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $user->id }}" data-kt-action="restore_row">
                {{ __('Activer') }}
            </a>
        </div>
    @else
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-user-id="{{ $user->id }}"
                data-kt-action="disabeld_row">
                {{ __('Désactiver') }}
            </a>
        </div>
    @endif

</div>
<!--end::Menu-->
