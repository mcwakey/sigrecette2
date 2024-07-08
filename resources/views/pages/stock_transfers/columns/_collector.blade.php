<!--begin::User details-->
<div class="d-flex flex-column">
        <a             href="stock-transfers/{{ $stock_transfer->to_user_id }}?s_date={{ $stock_transfer->period_from?->format('Y-m-d H:i:s') }}&e_date={{ $stock_transfer->period_to?->format('Y-m-d H:i:s') }}"
                       class="menu-link px-3 text-start text-wrap">
        {{ $stock_transfer->user->name }}
        </a>
</div>
<!--begin::User details-->
