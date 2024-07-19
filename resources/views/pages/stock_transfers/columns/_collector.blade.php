@php

   if(request()->has('rc') && request()->input('rc') =='versement'){
$url = "stock-transfers/{$stock_transfer->to_user_id}?s_date={$stock_transfer->period_from}&e_date={$stock_transfer->period_to}&autoClick=addvertbtn";

   }elseif (request()->has('rc') && request()->input('rc') =='etat'){
       $url = "stock-transfers/{$stock_transfer->to_user_id}?s_date={$stock_transfer->period_from}&e_date={$stock_transfer->period_to}&autoClick=addstatetbtn";

   }
   else{
$url = "stock-transfers/{$stock_transfer->to_user_id}?s_date={$stock_transfer->period_from}&e_date={$stock_transfer->period_to}";

   }

@endphp
<div class="d-flex flex-column">

        <a             href="{{$url}}"
                       class="menu-link px-3 text-start text-wrap">
        {{ $stock_transfer->user->name }}
        </a>
</div>
<!--begin::User details-->
