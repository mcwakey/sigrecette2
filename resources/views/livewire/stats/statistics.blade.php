<div wire:poll.visible.120s="fetchStats">



    <div class="widget-container">
        @include('pages.dashboards._widget_invoices_stats')
    </div>
    <div class="grid-2">
        <div class="widget-container">
            @include('pages.dashboards.widget_notifications')
        </div>
        <div class="widget-container">
            @include('pages.dashboards.widget_taxpayer_with_gender')
        </div>
    </div>

</div>
