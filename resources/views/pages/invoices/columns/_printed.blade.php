@php
    $printedAt = $invoice->printed_at
        ? ($invoice->printed_at instanceof \Carbon\CarbonInterface
            ? $invoice->printed_at
            : \Carbon\Carbon::parse($invoice->printed_at))
        : null;
@endphp

@if($printedAt)
    <span class="badge badge-light-success text-nowrap">
        <i class="ki-duotone ki-printer fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
        {{ $printedAt->format('d/m/Y H:i') }}
    </span>
@else
    <span class="badge badge-light-warning">
        <i class="ki-duotone ki-minus-circle fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
        {{ __('Non imprimé') }}
    </span>
@endif
