<!--begin::User details-->
<div class="d-flex flex-column">
    @if($taxpayer_taxable->bill_status == "NOT BILLED")
        <div class="badge badge-lg badge-light-danger d-inline">{{ __($taxpayer_taxable->bill_status) }}</div>
    @else
        <div class="badge badge-lg badge-light-success d-inline">{{ __($taxpayer_taxable->bill_status) }} : {{ $taxpayer_taxable->invoice->invoice_no }}</div>
    @endif
</div>
<!--begin::User details-->