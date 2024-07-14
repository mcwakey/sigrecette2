@php
    $charts = [
        [
            'container_id' => 'kt_amcharts_77',
            'data' => $stats[ \App\Enums\StatisticKeysEnums::BY_CATEGORY],
        ],
    ];
@endphp
            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab1" role="tabpanel">
                <!--begin::Chart-->
                <div id="kt_amcharts_77" class="ms-n5 min-h-auto" style="height: 275px"></div>
                <!--end::Chart-->
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
