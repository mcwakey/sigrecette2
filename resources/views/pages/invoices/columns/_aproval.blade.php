    @if($invoice->status == "PENDING")
    <div class="badge badge-lg badge-light-primary d-inline">{{ __($invoice->status) }}
        @can('change invoice pending status to approved')
            <button type="button"
                    class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                    data-kt-user-id="{{ $invoice->id }}"
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
            </button>
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                 data-kt-menu="true" data-kt-menu-id="kt_modal_add_status"
                 data-kt-menu-placement="bottom-end">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-5 text-gray-900 fw-bold">Metre a jour le
                        status</div>
                </div>
                <!--end::Header-->
                <!--begin::Menu separator-->
                <div class="separator border-gray-200"></div>
                <livewire:invoice.add-status-form />
            </div>
        @endcan
    </div>
    @elseif($invoice->status=="APROVED")
    <div class="badge badge-lg badge-light-success d-inline">{{ __('APROVED') }}</div>
    @elseif($invoice->status=="REJECTED")
    <div class="badge badge-lg badge-light-danger d-inline">{{ __('REJECTED') }}</div>
    @elseif($invoice->status=="DRAFT")
    <div class="badge badge-lg badge-light-secondary d-inline">{{ __('DRAFT')}}
        @can('change invoice draft status to pending')
            <button type="button"
                    class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                    data-kt-user-id="{{ $invoice->id }}"
                    data-kt-menu-target="#kt_modal_add_status"
                    data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end"
                    data-kt-action="update_status">
                <!-- <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-target="#kt-users-tasks" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"> -->
                <i class="ki-duotone ki-setting-3 fs-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
            </button>
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                 data-kt-menu="true" data-kt-menu-id="kt_modal_add_status"
                 data-kt-menu-placement="bottom-end">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-5 text-gray-900 fw-bold">Metre a jour le
                        status</div>
                </div>
                <!--end::Header-->
                <!--begin::Menu separator-->
                <div class="separator border-gray-200"></div>
                <livewire:invoice.add-status-form />
            </div>

        @endcan
    </div>
    @else
    <div class="badge badge-lg badge-light-info d-inline">{{ __($invoice->status)}}</div>
    @endif



