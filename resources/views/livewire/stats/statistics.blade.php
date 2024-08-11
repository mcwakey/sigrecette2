<div wire:poll.visible.120s="fetchStats">
    <div class="grid-3">
        <div>
            @include('pages.dashboards._widget_invoices_stats')
        </div>
        <div>
            @include('pages.dashboards.widget_unused_2')
        </div>
        <div>
            @include('pages.dashboards.widget_unused_1')
        </div>
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
