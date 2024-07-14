@php
    $charts = [

               [
                   'container_id' => 'kt_amcharts_c',
                   'data' => $stats[ \App\Enums\StatisticKeysEnums::BY_CANTON],
               ],
                [
                   'container_id' => 'kt_amcharts_t',
                    'data' =>$stats[ \App\Enums\StatisticKeysEnums::BY_TOWN],
              ],
                [
                    'container_id' => 'kt_amcharts_z',
                    'data' =>$stats[ \App\Enums\StatisticKeysEnums::BY_ZONE],
                ],

           ];
@endphp
<div class="card card-flush h-xl-100">
    <!--begin::Header-->
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Graphique - Contribuables</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Par découpages adminstratifs</span>
        </h3>
        <div class="card-toolbar">
            <ul class="nav" id="kt_chart_widget_8_tabs">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active" data-bs-toggle="tab" id="kt_amcharts_c_toggle" href="#kt_amcharts_c_tab">Canton</a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_amcharts_t_toggle" href="#kt_amcharts_t_tab">Town</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 " data-bs-toggle="tab" id="kt_amcharts_z_toggle" href="#kt_amcharts_z_tab">Zone</a>
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
            <div class="tab-pane fade active show" id="kt_amcharts_c_tab" role="tabpanel">
                <div id="kt_amcharts_c" class="ms-n5 min-h-auto" style="height: 275px"></div>
            </div>

             <div class="tab-pane fade" id="kt_amcharts_t_tab" role="tabpanel">
                 <div id="kt_amcharts_t" class="ms-n5 min-h-auto" style="height: 275px"></div>
            </div>
            <div class="tab-pane fade" id="kt_amcharts_z_tab" role="tabpanel">
                <div id="kt_amcharts_z" class="ms-n5 min-h-auto" style="height: 275px"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')


    @foreach($charts as $chart)
        <script>
            (function() {
                let data = @json($chart['data']);

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

