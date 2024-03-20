<!--begin::User details-->
<div class="d-flex flex-column">
    @if($invoice->validity == "EXPIRED")
        <div class="badge badge-lg badge-light-danger d-inline">{{ __($invoice->validity) }}</div>
    @elseif($invoice->validity == "VALID")
        <div class="badge badge-lg badge-light-success d-inline">{{ __($invoice->validity) }}</div>
    @elseif($invoice->validity == "CANCELED")
        <div class="badge badge-lg badge-light-info d-inline">{{ __($invoice->validity) }}</div>
    @else
        <div class="badge badge-lg badge-light-primary d-inline">{{ __($invoice->validity) }}</div>
    @endif
</div>
<!--begin::User details-->