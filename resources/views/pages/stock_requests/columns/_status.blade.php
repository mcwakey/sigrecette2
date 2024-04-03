<!--begin::User details-->
<div class="d-flex flex-column">
    @if($stock_request->type == "ACTIVE")
        <div class="badge badge-lg badge-light-warning d-inline">DEMANDE</div>
    @elseif($stock_request->type == "DONE")
        <div class="badge badge-lg badge-light-success d-inline">COMPTABILISE</div>
    @else
        <div class="badge badge-lg badge-light-primary d-inline">COMPTE RENDU</div>
    @endif
</div>
<!--begin::User details-->