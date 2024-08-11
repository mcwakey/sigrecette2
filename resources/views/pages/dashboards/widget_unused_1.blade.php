@php
    $count_invoices = $stats_reactive[ \App\Enums\StatisticKeysEnums::BY_INVOICE];
@endphp

<div class="widget-container" style="background-color: #17c653;padding-left:24px;padding-right:24px;">

    <style>
        .rd-invoice1 {
            width:40px;
            height: 40px;
            display:flex;
            border-radius:100%;
            background-color: #ffffffef;
            justify-content: center;
            align-items: center;
        }

        .rd-invoice1 span {
            color: #17c653;
            display: block;
            margin-left: 4px;
            margin-top: 1px;
            font: bold;
        }
    </style>

    <!--begin::Header-->
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-white">Graphique</span>
        </h3>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <div class="card-title d-flex flex-column">
            <div class="rd-invoice1">
                <span class="fw-bold me-2 lh-1 ls-n2">{{$count_invoices["NOEXPIRED"]}}</span>
            </div>
            <span class="opacity-75 pt-1 fw-semibold fs-6" style="color:#000000;margin-bottom:16px;">Avis recouvré</span>
            <span
                class="fw-bold text-white me-2 lh-1 ls-n2" style="font-size: 32px;">{{ '5 000'.\App\Helpers\Constants::CURRENCY}}</span>
            <span class="opacity-75 pt-1 fw-bold fs-6" style="color:#000000">Montant recouvrer</span>
        </div>
    </div>
</div>