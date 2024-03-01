<div class="d-flex flex-column">
    @if($category->status == 'ACTIVE')
        <div class="badge badge-lg badge-light-success d-inline">{{ $category->status}}</div>
    @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ $category->status }}</div>
    @endif
</div>
