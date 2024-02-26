<div class="d-flex flex-column">
    @if($zone->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $zone->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $zone->status }}</div>
    @endif
</div>