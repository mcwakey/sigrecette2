<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
{{ __('actions') }}
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
    <!--begin::Menu item-->
    <!-- <div class="menu-item px-3">
            Aviser
        </a>
    </div> -->

    <!-- <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{-- $stock_request->id --}}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_stock_request" data-kt-action="update_taxable">
        {{-- {{ __('view') }} --}}
        </a>
    </div> -->
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <!-- <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{-- $stock_request->id --}}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_stock_request" data-kt-action="update_taxable">
        {{-- {{ __('edit') }} --}}
        </a>
    </div> -->
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <!-- <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{ $stock_request->id }}" data-kt-action="delete_taxpayer">
        {{-- {{ __('delete') }} --}}
        </a>
    </div> -->
    <!--end::Menu item-->

    <!--begin::Menu item-->
    @if ($stock_request->req_type == 'DEMANDE')
        @can('peut faire un etat de compte')
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{ $stock_request->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_stock_request" data-kt-action="update_request">
                    {{ __('account state') }}
                </a>
            </div>
        @endcan
    @endif
            @php
                $data = [$stock_request->id];
            @endphp
        <div class="menu-item px-3">
            <a href="{{route('generatePdf', ['data' => json_encode($data),'type' => '7']) }}" class="menu-link px-3 text-start text-wrap" data-kt-user-id="{{ $stock_request->id }}" data-kt-action="delete_taxpayer">
                {{ __('print account state') }}
            </a>
        </div>

    <!--end::Menu item-->
</div>
<!--end::Menu-->
