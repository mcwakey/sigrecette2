@php
    $charts = [
        [
            'container_id' => 'kt_amcharts_77',
            'data' => \App\Models\Taxpayer::countTaxpayersByActivity(),
        ],
    ];
    $count_tapyers_with_gender = \App\Models\Taxpayer::countTaxpayers();
@endphp
<div class="card card-flush h-xl-100">
    <!--begin::Header-->
    <div class="card-header pt-5">
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <ul class="nav" id="kt_chart_widget_8_tabs">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active"
                        data-bs-toggle="tab" id="kt_chart_widget_8_month_toggle"
                        href="#kt_chart_widget_8_month_tab">Graphique - Contribuables par catégorie d'activité</a>
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
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                <!--begin::Chart-->
                <div id="kt_amcharts_77" class="ms-n5 min-h-auto" style="height: 275px"></div>
                <!--end::Chart-->
            </div>
        </div>
    </div>
</div>

@push('scripts')


    @foreach ($charts as $chart)
        <script>
            (function() {
                let data = @json($chart['data']);
                console.log("data", data);
                am5.ready(function() {
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
                    series.slices.template.adapters.add("radius", function(radius, target) {
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
            })
            ();
        </script>
    @endforeach
@endpush
