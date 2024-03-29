<div class="d-flex flex-column">
    @if($year->auto_switch == true)
        <div class="badge badge-lg badge-light-success d-inline">ACTIVE</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">INACTIVE</div>
    @endif
</div>
