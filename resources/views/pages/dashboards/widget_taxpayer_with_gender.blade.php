@php
   $count_tapyers_with_gender = $stats_reactive[ \App\Enums\StatisticKeysEnums::BY_GENDER];
@endphp
<div class="card card-flush h-xl-100">
    <!--begin::Header-->
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - Contribuables</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par genre</span>
        </h3>
        <div class="card-toolbar">
            <ul class="nav" id="kt_chart_widget_8_tabs">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active" data-bs-toggle="tab" id="kt_chart_widget_8_month_toggle" href="#kt_chart_widget_8_month_tab">Genre</a>
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

            <div class="tab-pane fade show active" id="kt_chart_widget_8_gender_tab" role="tabpanel">

                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <div class="card-title d-flex flex-column">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{$count_tapyers_with_gender["Total"] ?? ''}}</span>
                        <!--end::Amount-->
                        <!--begin::Subtitle-->
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">{{ __('Contribuables')}}</span>
                        <!--end::Subtitle-->
                    </div>
                    <!--end::Title-->
                </div>
                <div >
                    @if(isset($count_tapyers_with_gender["Homme"],$count_tapyers_with_gender["Femme"]))
                        <div class="card-body d-flex align-items-end pt-6">
                            <!--begin::Row-->
                            <div class="row align-items-center mx-0 w-100">
                                <!--begin::Col-->
                                <div class="col-7 px-0">
                                    <!--begin::Labels-->
                                    <div class="d-flex flex-column content-justify-center" >
                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet bg-success me-3" style="border-radius: 3px;width: 12px;height: 12px"></div>
                                            <!--end::Bullet-->

                                            <!--begin::Label-->
                                            <div class="fs-5 fw-bold text-gray-600 me-5">Femme</div>
                                            <!--end::Label-->

                                            <!--begin::Stats-->
                                            <div class="ms-auto fw-bolder text-gray-700 text-end">{{$count_tapyers_with_gender["Femme"] ?? ''}}</div>

                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center my-4">
                                            <!--begin::Bullet-->
                                            <div class="bullet bg-primary me-3" style="border-radius: 3px;width: 12px;height: 12px"></div>
                                            <!--end::Bullet-->

                                            <!--begin::Label-->
                                            <div class="fs-5 fw-bold text-gray-600 me-5">Homme</div>
                                            <!--end::Label-->

                                            <!--begin::Stats-->
                                            <div class="ms-auto fw-bolder text-gray-700 text-end"> {{$count_tapyers_with_gender["Homme"] ?? ''}}</div>

                                        </div>
                                        <div class="d-flex fs-6 fw-semibold align-items-center my-4">
                                            <div class="bullet bg-info me-3" style="border-radius: 3px;width: 12px;height: 12px"></div>
                                            <div class="fs-5 fw-bold text-gray-600 me-5">Entreprise</div>
                                            <div class="ms-auto fw-bolder text-gray-700 text-end"> {{$count_tapyers_with_gender["Autre"] ?? ''}}</div>

                                        </div>

                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet me-3" style="border-radius: 3px;background-color: #E4E6EF;width: 12px;height: 12px"></div>
                                            <!--end::Bullet-->

                                            <!--begin::Label-->
                                            <div class="fs-5 fw-bold text-gray-600 me-5">Ratio F/H</div>
                                            <!--end::Label-->


                                            <div class="ms-auto fw-bolder text-gray-700 text-end">{{"~ ". round(  ($count_tapyers_with_gender["Femme"]/$count_tapyers_with_gender["Homme"]) ,2)}}</div>                        <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                    </div>

                                    <!--end::Labels-->
                                </div>

                                <!--end::Col-->

                                <!--begin::Col-->

                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


