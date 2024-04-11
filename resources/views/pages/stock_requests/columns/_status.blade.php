<!--begin::User details-->
<div class="d-flex flex-column">
    
@if($stock_request->type == "ACTIVE")
    @if($stock_request->req_type == "DEMANDE")
        <div class="badge badge-lg badge-light-warning d-inline">{{ $stock_request->req_type }}</div>
    @elseif($stock_request->req_type == "COMPTABILISE")
        <div class="badge badge-lg badge-light-success d-inline">{{ $stock_request->req_type }}
        <button type="button"
                class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                data-kt-user-id="{{ $stock_request->id }}"
                data-kt-menu-target="#kt_request_modal_add_status"
                data-kt-menu-trigger="click"
                data-kt-menu-placement="bottom-end"
                data-kt-action="update_request_status">
            <i class="ki-duotone ki-setting-3 fs-3">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
            </i>
        </button>

<!--begin::Task menu-->
<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
        data-kt-menu="true" data-kt-menu-id="kt_request_modal_add_status">
    <!--begin::Header-->
    <div class="px-7 py-5">
        <div class="fs-5 text-gray-900 fw-bold">Metre à jour le status</div>
    </div>
    <!--end::Header-->
    <!--begin::Menu separator-->
    <div class="separator border-gray-200"></div>
    <!--end::Menu separator-->
    <!--begin::Form-->
    <livewire:stock_request.add-status-form />

    <!--end::Form-->
</div>
<!--end::Task menu-->
</div>
    @endif
    @else
        <div class="badge badge-lg badge-light-primary d-inline">COMPTE RENDU</div>
    @endif
</div>
<!--begin::User details-->