<div class="card card-flush h-xl-100 ">
    <!--begin::Header-->
    <div class="card-header pt-5">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - Contribuables</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par activités économiques</span>
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <ul class="nav" id="kt_chart_widget_8_tabs">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active"
                       data-bs-toggle="tab" id="kt_chart_widget_8_week_toggle"
                       href="#kt_chart_widget_8_month_tab1">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 "
                       data-bs-toggle="tab" id="kt_chart_widget_8_month_toggle"
                       href="#kt_chart_widget_8_month_tab2">Activités</a>
                </li>
            </ul>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab pane-->
            @include('pages.dashboards.widget_taxpayer_category')
            @include('pages.dashboards.widget_taxpayer_activity')
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Body-->
</div>
