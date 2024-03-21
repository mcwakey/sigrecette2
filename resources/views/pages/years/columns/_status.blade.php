<div class="d-flex flex-column">
    @if($year->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $year->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $year->status }}</div>
    @endif
</div>
