@php
    $count_invoices_t = $stats_reactive[ \App\Enums\StatisticKeysEnums::BY_INVOICE];
        $count_invoices_c = $stats_reactive[ \App\Enums\StatisticKeysEnums::BY_INVOICE_COMPTANT];

@endphp


@php
@endphp
<div wire:poll.visible.120s="fetchStats">

    <div class="grid-3">
        <div>
            @include('pages.dashboards._widget_invoices_stats_total',[
                'type'=>'(Avis sur titre)',
                'NOEXPIRED'=>isset($count_invoices_t['NOEXPIRED'])?$count_invoices_t['NOEXPIRED']:null,
                'APROVED'=>isset($count_invoices_t['APROVED'])?$count_invoices_t['APROVED']:null,
                'REJECTED'=>isset($count_invoices_t['REJECTED'])?$count_invoices_t['REJECTED']:null,
                'Pending'=>isset($count_invoices_t['Pending'])?$count_invoices_t['Pending']:null,
                'estimation'=>$invoice_estimation_t,
    ])
        </div>
        <div>
            @include('pages.dashboards._widget_invoices_stats_balance',[
            'type'=>'(Avis sur titre)',
            'NOEXPIRED'=>isset($count_invoices_t['NOEXPIRED'])?$count_invoices_t['NOEXPIRED']:null,
            'in_count'=>$invoice_count_t
            ])
        </div>
        <div>
            @include('pages.dashboards._widget_invoices__stats_recoveries',[
            'type'=>'(Avis sur titre)',
            'count_titre'=>$invoice_count_collected['count_titre'],
            'titre_total'=>$invoice_count_collected['titre_total']
            ])
        </div>




        <div>
            @include('pages.dashboards._widget_invoices_stats_total',[
                'type'=>'(Avis au comptant)',
                'NOEXPIRED'=>isset($count_invoices_c['NOEXPIRED'])?$count_invoices_c['NOEXPIRED']:null,
                'APROVED'=>isset($count_invoices_c['APROVED'])?$count_invoices_c['APROVED']:null,
                'REJECTED'=>isset($count_invoices_c['REJECTED'])?$count_invoices_c['REJECTED']:null,
                'Pending'=>isset($count_invoices_c['Pending'])?$count_invoices_c['Pending']:null,
                'estimation'=>$invoice_estimation_c,
    ])
        </div>
        <div>
            @include('pages.dashboards._widget_invoices_stats_balance',[
            'type'=>'(Avis au comptant)',
            'NOEXPIRED'=>isset($count_invoices_c['NOEXPIRED'])?$count_invoices_c['NOEXPIRED']:null,
            'in_count'=>$invoice_count_c
            ])
        </div>
        <div>
            @include('pages.dashboards._widget_invoices__stats_recoveries',[
            'type'=>'(Avis au comptant)',
            'count_titre'=>$invoice_count_collected['count_comptant'],
            'titre_total'=>$invoice_count_collected['comptant_total']
            ])
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
