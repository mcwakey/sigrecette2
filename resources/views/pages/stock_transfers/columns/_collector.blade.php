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

    <a href="{{ $url }}"
       class="{{ $stock_transfer->period_from != $stock_transfer->period_to && $stock_transfer->period_to >= now()->toDateString()
                ? 'menu-link text-start text-wrap badge badge-warning'
                : 'menu-link text-start text-wrap' }}">
        {{ $stock_transfer->period_from . " - " . $stock_transfer->period_to }}
    </a>
</div>
<!--begin::User details-->
