@php
    $charts = [
            //    [
            //        'container_id' => 'kt_amcharts_77',
            //        'data' => \App\Models\Taxpayer::countTaxpayersByActivity(),
            //    ],
               [
                   'container_id' => 'kt_amcharts_3',
                   'data' =>  \App\Models\Taxpayer::countTaxpayersByCanton(),
               ],
            //    [
            //        'container_id' => 'kt_amcharts_30',
            //        'data' =>\App\Models\Taxpayer::countTaxpayersByTaxables(),
            //    ],
            //    [
            //        'container_id' => 'kt_amcharts_37',
            //        'data' =>\App\Models\Taxpayer::countTaxpayersState(),
            //    ]
           ];
@endphp
<div class="card card-flush h-xl-100">
    <!--begin::Header-->
    <div class="card-header pt-5">
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <ul class="nav" id="kt_chart_widget_8_tabs">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active" data-bs-toggle="tab" id="kt_chart_widget_8_month_toggle" href="#kt_chart_widget_8_month_tab">Graphique - Contribuable Par Canton</a>
                </li>



                {{-- <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_chart_widget_8_gender_toggle" href="#kt_chart_widget_8_gender_tab">genre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_chart_widget_8_canton_toggle" href="#kt_chart_widget_8_canton_tab">Canton</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_chart_widget_8_activity_toggle" href="#kt_chart_widget_8_activity_tab">Secteur d'activité</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_chart_widget_8_taxable_toggle" href="#kt_chart_widget_8_taxable_tab">Matière taxables</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_chart_widget_8_state_toggle" href="#kt_chart_widget_8_state_tab">Etat</a>
                </li> --}}
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
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                <!--begin::Chart-->
                <div id="kt_amcharts_3" class="ms-n5 min-h-auto" style="height: 275px"></div>
                <!--end::Chart-->
            </div>

            {{-- <div class="tab-pane fade" id="kt_chart_widget_8_gender_tab" role="tabpanel">
                <!--begin::Statistics-->
                <div class="mb-5">
                    <!--begin::Statistics-->
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2">Solde</span>
                        <span class="badge badge-light-success fs-base">{!! getIcon('arrow-up', 'fs-5 text-success ms-n1') !!} 2.2%</span>
                    </div>
                    <!--end::Statistics-->
                    <!--begin::Description-->
                    <span class="fs-6 fw-semibold text-gray-500">******</span>
                    <!--end::Description-->
                </div>
                <!--end::Statistics-->
                <!--begin::Chart-->
                <div  style="height: 300px">
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
                                        <!--end::Label-->

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
            </div> --}}
        </div>
    </div>
</div>

@push('scripts')


    @foreach($charts as $chart)
        <script>
            (function() {
                let data = @json($chart['data']);
                console.log("data", data);
                am5.ready(function () {
                    var root = am5.Root.new("{{ $chart['container_id'] }}");
                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);
                    var chart = root.container.children.push(am5percent.PieChart.new(root, {
                        layout: root.verticalLayout
                    }));
                    var series = chart.series.push(am5percent.PieSeries.new(root, {
                        alignLabels: true,
                        calculateAggregates: true,
                        valueField: "value",
                        categoryField: "category"
                    }));
                    series.slices.template.setAll({
                        strokeWidth: 3,
                        stroke: am5.color(0xffffff)
                    });
                    series.labelsContainer.set("paddingTop", 5);
                    series.slices.template.adapters.add("radius", function (radius, target) {
                        var dataItem = target.dataItem;
                        var high = series.getPrivate("valueHigh");
                        if (dataItem) {
                            var value = target.dataItem.get("valueWorking", 0);
                            return radius * value / high;
                        }
                        return radius;
                    });
                    series.data.setAll(data);

                    // Ajouter une légende externe


                    //series.appear(100, 10);
                });
            })();

        </script>
    @endforeach
@endpush

