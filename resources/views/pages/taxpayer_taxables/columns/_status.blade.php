<!--begin::User details-->
<div class="d-flex flex-column">
    @if($taxpayer_taxable->invoice_id == null)
        <div class="badge badge-lg badge-light-danger d-inline">{{ __('not billed') }}</div>
    @else
        <div class="badge badge-lg badge-light-success d-inline">{{ __('billed') }} : {{ $taxpayer_taxable->invoice_id }}</div>
    @endif
</div>
<!--begin::User details-->