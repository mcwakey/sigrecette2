@php
    use Carbon\Carbon;
    $year= \App\Models\Year::getActiveYear();
    $count_invoices = \App\Models\Invoice::countInvoices($year);
@endphp
<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
	<!--begin::Header-->
	<div class="card-header pt-5">

		<!--begin::Title-->
		<div class="card-title d-flex flex-column">
			<!--begin::Amount-->
			<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{$count_invoices["NOEXPIRED"]}}</span>
			<!--end::Amount-->
			<!--begin::Subtitle-->
			<span class="text-white opacity-75 pt-1 fw-semibold fs-6">Active Invoices</span>

			<!--end::Subtitle-->
		</div>
		<!--end::Title-->
	</div>
	<!--end::Header-->
	{{--
<!-- <div class="card-body d-flex align-items-end pt-0">
		<div class="d-flex align-items-center flex-column mt-3 w-100">
            <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
				
                <span>{{ $count_invoices["APROVED"]." " }}APROVED</span>
                <span>{{ $count_invoices["REJECTED"]." " }}Rejected</span>
                <span> {{$count_invoices["REDUCED"]." "}} Reduced</span>
                <span>{{ $count_invoices["CANCELED"]." " }}CANCELED</span>
            </div>
			<div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
				<span> {{$count_invoices["Pending"]}} Not delivery</span>
                <span>{{ round( ($count_invoices["Pending"] *100)/$count_invoices["NOEXPIRED"]) }}%</span>
            </div>
			<div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
				<div class="bg-white rounded h-8px" role="progressbar" style="width: {{round( ($count_invoices["Pending"] *100)/$count_invoices["NOEXPIRED"]) }}}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		</div>
	
	</div> -->


	--}}
	
	
</div>
<!--end::Card widget 20-->
