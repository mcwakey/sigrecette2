<div class="d-flex flex-column">
    @if($taxable->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $taxable->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $taxable->status }}</div>
    @endif
</div>
