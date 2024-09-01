<!--begin::User details-->
<div class="d-flex flex-column">
    @if($payment->status == "DONE")
        <div class="badge badge-lg badge-light-danger d-inline">{{ __($payment->status) }}</div>
    @elseif($payment->status == "CANCELED")
        <div class="badge badge-lg badge-light-warning d-inline">{{ __($payment->status) }}</div>
    @else
        <div class="badge badge-lg badge-light-success d-inline">{{ __($payment->status) }}</div>
    @endif
</div>
<!--begin::User details-->