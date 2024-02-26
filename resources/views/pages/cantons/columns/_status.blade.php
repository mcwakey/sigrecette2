<div class="d-flex flex-column">
    @if($canton->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $canton->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $canton->status }}</div>
    @endif
</div>