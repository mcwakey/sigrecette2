
<div class="d-flex flex-column">
    @if($invoice->status == 'SUCCESS')
        <div class="badge badge-success fw-bold">{{ $invoice->status}}</div>
    @elseif($invoice->status == 'PENDING')
        <div class="badge badge-primary fw-bold">{{ $invoice->status }}</div>
    @else
        <div class="badge badge-danger fw-bold">{{ $invoice->status }}</div>
    @endif
</div>
