<div class="d-flex flex-column">
    @if($erea->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $erea->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $erea->status }}</div>
    @endif
</div>