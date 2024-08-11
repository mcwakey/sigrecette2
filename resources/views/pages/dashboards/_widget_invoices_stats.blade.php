@php
    $count_invoices = $stats_reactive[ \App\Enums\StatisticKeysEnums::BY_INVOICE];
@endphp
<div class="widget-container" style="background-color: #F1416C;padding-left:24px;padding-right:24px;">
    @if(isset($count_invoices['APROVED'], $count_invoices['REJECTED'],$count_invoices['Pending'],$count_invoices['NOEXPIRED'],$count_invoices['CANCELED']))
    @endif
    <div class="card-header pt-5">

        <style>
            .rd-invoice {
                width:40px;
                height: 40px;
                display:flex;
                border-radius:100%;
                background-color: #ffffffef;
                justify-content: center;
                align-items: center;
            }

            .rd-invoice span {
                color: #ff338d;
                display: block;
                margin-left: 4px;
                margin-top: 1px;
                font: bold;
            }
        </style>

        <h3 class="card-title align-items-start flex-column mb-6">
            <span class="card-label fw-bold text-white">Graphique</span>
        </h3>

        @if(isset($count_invoices['NOEXPIRED']))
            <div class="card-title d-flex flex-column">
                <div class="rd-invoice">
                    <span class="fw-bold me-2 lh-1 ls-n2">{{$count_invoices["NOEXPIRED"]}}</span>
                </div>
                <span class="opacity-75 pt-1 fw-semibold fs-6" style="color:#000000;margin-bottom:16px;">Avis émis</span>
                <span
                    class="fw-bold text-white me-2 lh-1 ls-n2" style="font-size: 32px;">{{format_amount($invoice_count) .\App\Helpers\Constants::CURRENCY}}</span>
                <span class="opacity-75 pt-1 fw-bold fs-6" style="color:#000000">Montant à recouvrer</span>
            </div>
        @endif

        <!--end::Title-->
    </div>
    <!--end::Header-->

    <!--begin::Card body-->
    <div class="card-body d-flex align-items-end pt-0" style="height: 100%">
        <div class="d-flex align-items-center flex-column mt-3 w-100">
            <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                @if(isset($count_invoices['APROVED']))
                    <span>{{ $count_invoices["APROVED"]." " }}Approuvé</span>
                @endif
                @if(isset($count_invoices['REJECTED']))
                    <span>{{ $count_invoices["REJECTED"]." " }}Rejeté</span>
                @endif


            </div>
            @if(isset($count_invoices['Pending'], $count_invoices['NOEXPIRED']) && intval($count_invoices["NOEXPIRED"])>0 )
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                    <span> {{$count_invoices["Pending"]}} Non délivré</span>
                    <span>{{ round( ($count_invoices["Pending"] *100)/$count_invoices["NOEXPIRED"]) }}%</span>
                </div>
            @endif
            <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                @if(isset($count_invoices['Pending'],$count_invoices['NOEXPIRED'])&& intval($count_invoices["NOEXPIRED"])>0 )
                    <div class="bg-white rounded h-8px" role="progressbar"
                         style="width: {{round( ($count_invoices["Pending"] *100)/$count_invoices["NOEXPIRED"]) }}}}%;"
                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                @endif
            </div>
        </div>
    </div>
</div>
