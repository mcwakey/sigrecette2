<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>

<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true" data-kt-menu-id="#kt-users-actions">

    @php
    switch ($printFile->name) {
        case App\Enums\PrintNameEnums::BORDEREAU:
            $r_url=route('generateWithPrintData', ['printFile' => $printFile->id, 'type' => 2, 'action' => 1]);
            break;
        case App\Enums\PrintNameEnums::BORDEREAU_REDUCTION:
            $r_url=route('generateWithPrintData', ['printFile' => $printFile->id, 'type' => 2, 'action' => 2]);
            break;
        default:
            $r_url="#";
    }
    @endphp

        <div class="menu-item px-3">
            <a href="{{$r_url}}" class="menu-link px-3" target="_blank">{{ __('print') }}</a>
        </div>


</div>
