@php

   if(request()->has('rc') && request()->input('rc') =='versement'){
$url = "stock-transfers/{$stock_transfer->to_user_id}?p_s_date={$stock_transfer->period_from}&p_e_date={$stock_transfer->period_to}&autoClick=addvertbtn";

   }elseif (request()->has('rc') && request()->input('rc') =='etat'){
       $url = "stock-transfers/{$stock_transfer->to_user_id}?p_s_date={$stock_transfer->period_from}&p_e_date={$stock_transfer->period_to}&autoClick=addstatetbtn";

   }
   else{
$url = "stock-transfers/{$stock_transfer->to_user_id}?p_s_date={$stock_transfer->period_from}&p_e_date={$stock_transfer->period_to}";

   }

@endphp
<div class="d-flex flex-column">

        <a             href="{{$url}}"
                       class="menu-link px-3 text-start text-wrap btn btn-icon btn-light pulse pulse-warning">
        {{ $stock_transfer->period_from." - ".$stock_transfer->period_to}}
            @if ($stock_transfer->period_from != $stock_transfer->period_to && $stock_transfer->period_to >= now()->toDateString())
                <i class="ki-duotone ki-element-11 fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                <span class="pulse-ring"></span>
            @endif

        </a>
</div>
<!--begin::User details-->
