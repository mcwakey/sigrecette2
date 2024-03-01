<div class="d-flex flex-column">
    @if($activity->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $activity->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $activity->status }}</div>
    @endif
</div>
