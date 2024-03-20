<div class="d-flex flex-column">
    @if($invoice->status == "PENDING")
    <div class="badge badge-lg badge-light-primary d-inline">{{ __($invoice->status) }}</div>
    @elseif($invoice->status=="APROVED")
    <div class="badge badge-lg badge-light-success d-inline">{{ __('APROVED') }}</div>
    @elseif($invoice->status=="REJECTED")
    <div class="badge badge-lg badge-light-danger d-inline">{{ __('REJECTED') }}</div>
    @elseif($invoice->status=="DRAFT")
    <div class="badge badge-lg badge-light-secondary d-inline">{{ __('DRAFT')}}</div>
    @else
    <div class="badge badge-lg badge-light-info d-inline">{{ __($invoice->status)}}</div>
    @endif
</div>