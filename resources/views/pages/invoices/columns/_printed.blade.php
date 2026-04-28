@if($invoice->printed_at)
    <span class="badge badge-light-success text-nowrap">
        <i class="ki-duotone ki-printer fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
        {{ $invoice->printed_at->format('d/m/Y H:i') }}
    </span>
@else
    <span class="badge badge-light-warning">
        <i class="ki-duotone ki-minus-circle fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
        {{ __('Non imprimé') }}
    </span>
@endif
