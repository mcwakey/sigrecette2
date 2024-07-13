@php
    use Carbon\Carbon;
    $year = \App\Models\Year::getActiveYear();
    $count_invoices = \App\Models\Invoice::countInvoices($year);
@endphp
<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
     style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
    @if(isset($count_invoices['APROVED'], $count_invoices['REJECTED'],$count_invoices['Pending'],$count_invoices['NOEXPIRED'],$count_invoices['CANCELED']))
    @endif
    <div class="card-header pt-5">

        @if(isset($count_invoices['NOEXPIRED']))
            <div class="card-title d-flex flex-column">

                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{$count_invoices["NOEXPIRED"]}}</span>
                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Avis émis</span>
                <span
                    class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{format_amount(\App\Models\Invoice::getTotalRemainingToBeCollected()) .\App\Helpers\Constants::CURRENCY}}</span>
                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Avis à recouvrer</span>


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
                    <span>{{ $count_invoices["APROVED"]." " }}APROVED</span>
                @endif
                @if(isset($count_invoices['REJECTED']))
                    <span>{{ $count_invoices["REJECTED"]." " }}Rejected</span>
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
