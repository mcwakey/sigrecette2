<!--begin::User details-->
<div class="d-flex flex-column">

        <a             href="stock-transfers/{{ $stock_transfer->to_user_id }}?s_date={{ $stock_transfer->period_from }}&e_date={{ $stock_transfer->period_to }}"
                       class="menu-link px-3 text-start text-wrap">
        {{ $stock_transfer->user->name }}
        </a>
</div>
<!--begin::User details-->
