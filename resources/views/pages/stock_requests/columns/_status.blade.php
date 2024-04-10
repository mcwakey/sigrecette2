<!--begin::User details-->
<div class="d-flex flex-column">
    @if($stock_request->req_type == "DEMANDE")
        <div class="badge badge-lg badge-light-warning d-inline">{{ $stock_request->req_type }}</div>
    @elseif($stock_request->req_type == "COMPTABILISE")
        <div class="badge badge-lg badge-light-success d-inline">{{ $stock_request->req_type }}</div>
    @else
        <div class="badge badge-lg badge-light-primary d-inline">{{ $stock_request->req_type }}</div>
    @endif
</div>
<!--begin::User details-->