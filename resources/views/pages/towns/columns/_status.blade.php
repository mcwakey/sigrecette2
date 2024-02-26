<div class="d-flex flex-column">
    @if($town->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $town->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $town->status }}</div>
    @endif
</div>