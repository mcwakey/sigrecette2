@if($taxpayerinfo->from_mobile_and_validate_state == App\Enums\TaxpayerStateEnums::PENDING )
    <div class="badge badge-lg badge-light-primary d-inline">{{ __($taxpayerinfo->from_mobile_and_validate_state) }}
            @can('peut prendre en charge un avis sur titre')
                <button type="button"
                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto  pulse pulse-warning"
                        data-kt-user-id="{{ $taxpayerinfo->id }}"
                        data-kt-menu-target="#kt_modal_add_status"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end"
                        data-kt-action="update_status">
                    <i class="ki-duotone ki-setting-3 fs-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <span class="pulse-ring"></span>
                </button>
                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                     data-kt-menu="true" data-kt-menu-id="kt_modal_add_status"
                     data-kt-menu-placement="bottom-end">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-gray-900 fw-bold">Metre à jour le
                            status</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Menu separator-->
                    <div class="separator border-gray-200"></div>
                    <livewire:taxpayer.add-status-form  :id="$taxpayerinfo->id"/>
                </div>
            @endcan
    </div>
@elseif($taxpayerinfo->from_mobile_and_validate_state ==  App\Enums\TaxpayerStateEnums::APPROVED)
<div class="badge badge-lg badge-light-success d-inline">
{{ __('APROVED') }}</div>
@elseif($taxpayerinfo->from_mobile_and_validate_state ==  App\Enums\TaxpayerStateEnums::REJECTED)
<div class="badge badge-lg badge-light-danger d-inline">{{ __('REJECTED') }}</div>
@endif



