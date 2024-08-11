<x-default-layout>

    @section('title')
        {{ __('dashboard') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection
        <style>
            .grid-2 {
                margin-bottom: 12px;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                width: 100%;
                height: 100%;
            }

            .grid-3 {
                margin-bottom: 12px;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                width: 100%;
                height: 100%;
                padding: 40px 20px;
                border-radius: 20px;
            }

            .widget-container {
                padding: 10px;
                box-sizing: border-box;
                border-radius: 8px;
                height: 100%;
            }
        </style>
        <livewire:stats.statistics :startDate="$s_date" :endDate="$e_date" />
        <div>
            <div style="margin-bottom: 20px;">
                @include('pages.dashboards.widget_root')
            </div>


            <div style="margin-bottom: 20px;">
                @include('pages.dashboards.widget_activity_and_category')
            </div>
            <div style="margin-bottom: 20px;">
                @include('pages.dashboards.widget_taxpayer_state')
            </div>
            <div style="margin-bottom: 20px;">
                @include('pages.dashboards.widget_taxpayer_taxable')
            </div>

        </div>
</x-default-layout>
