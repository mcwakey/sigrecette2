<div class="widget-container" style="background-color: #F1416C;padding-left:24px;padding-right:24px;">
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
            <span class="card-label fw-bold text-white">{{$type}}</span>
        </h3>

        @if(isset($NOEXPIRED))
            <div class="card-title d-flex flex-column">
                <div class="rd-invoice">
                    <span class="fw-bold me-2 lh-1 ls-n2">{{$NOEXPIRED}}</span>
                </div>
                <span class="opacity-75 pt-1 fw-semibold fs-6" style="color:#000000;margin-bottom:16px;">Avis émis</span>
                <span
                    class="fw-bold text-white me-2 lh-1 ls-n2" style="font-size: 32px;">
                    {{format_amount($estimation) .\App\Helpers\Constants::CURRENCY}}
                </span>
                <span class="opacity-75 pt-1 fw-bold fs-6" style="color:#000000">Estimation(Prends en compte tout les avis émis et valide)</span>
            </div>
        @endif

        <!--end::Title-->
    </div>
    <!--end::Header-->

    <!--begin::Card body-->
</div>
