
    @if ($payment->status ==App\Enums\PaymentStatusEnums::PENDING->value )
        <span
            class="badge badge-light-primary">{{ __($payment->status) }}</span>
        @can('peut accepter un paiement')
            @if($payment->reference)
                <button type="button"
                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                        data-kt-user-id="{{ $payment->id }}"
                        data-kt-menu-target="#kt_payment_modal_add_status"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end"
                        data-kt-action="update_payment_status">
                    <i class="ki-duotone ki-setting-3 fs-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                </button>
@endif
        @endcan
    @elseif($payment->status == App\Enums\PaymentStatusEnums::ACCOUNTED->value)
        <span
            class="badge badge-light-success">{{ __(App\Enums\PaymentStatusEnums::ACCOUNTED->value) }}</span>
    @elseif($payment->status == App\Enums\PaymentStatusEnums::DONE->value)
        <span
            class="badge badge-light-warning">{{ __(App\Enums\PaymentStatusEnums::DONE->value)}}</span>
        @else
        <div class="badge badge-lg badge-light-danger d-inline">{{ __(App\Enums\PaymentStatusEnums::CANCELED->value) }}</div>
    @endif



<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
        data-kt-menu="true" data-kt-menu-id="kt_payment_modal_add_status">
    <div class="px-7 py-5">
        <div class="fs-5 text-gray-900 fw-bold">Metre à jour le status</div>
    </div>
    <div class="separator border-gray-200"></div>
    <livewire:payment.add-status-form />

</div>

