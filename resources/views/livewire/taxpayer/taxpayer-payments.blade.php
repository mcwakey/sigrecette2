<div class="card pt-4 mb-6 mb-xl-9">
    <!--begin::Card header-->
    <div class="card-header border-0">
        <!--begin::Card title-->
        <div class="card-title flex-column">
            <h2 class="mb-1">{{ __('taxpayers payments') }}</h2>
        </div>
        <!--end::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">

        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0 pb-5">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed gy-5" id="payment-table">
                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                <tr class="text-start text-muted text-uppercase gs-0">
                    <th class="min-w-50px">{{ __('payment date') }}</th>
                    <th class="min-w-50px">{{ __('invoice no') }}</th>
                    <th class="min-w-50px">{{ __('reference no') }}</th>
                    <th class="min-w-50px">{{ __("code d'imputation") }}</th>
                    <th class="min-w-50px">{{ __('amount') }}</th>
                    <th class="min-w-50px">{{ __('type') }}</th>
                    <th class="min-w-50px">{{ __('aproval') }}</th>
                    <th class="min-w-50px">{{ __('actions') }}</th>
                </tr>
                </thead>
                <tbody class="fs-6 fw-semibold text-gray-600">
                @foreach ($taxpayer->payments as $payment)
                    <tr>
                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                        <td>{{ $payment->invoice->invoice_no }}</td>
                        <td>{{ $payment->reference }}</td>
                        <td>{{ $payment->code }}</td>
                        <td>
                            {{ $payment->amount }}
                        </td>

                        <td><span
                                class="badge badge-light-secondary">{{ $payment->payment_type }}</span>
                        </td>



                        <td>
                            @if ($payment->status == App\Enums\PaymentStatusEnums::PENDING)
                                <span
                                    class="badge badge-light-primary">{{ __($payment->status) }}</span>
                                @can('peut accepter un paiement')
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
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                         data-kt-menu="true"
                                         data-kt-menu-id="#kt_payment_modal_add_status">
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Metre à jour le
                                                status
                                            </div>
                                        </div>
                                        <div class="separator border-gray-200"></div>
                                        <livewire:payment.add-status-form />

                                        <!--end::Form-->
                                    </div>
                                @endcan
                            @elseif($payment->status == App\Enums\PaymentStatusEnums::ACCOUNTED)
                                <span
                                    class="badge badge-light-success">{{ __(App\Enums\PaymentStatusEnums::ACCOUNTED) }}</span>
                            @else
                                @if (
                                    $payment->payment_type == App\Helpers\Constants::ANNULATION ||
                                        $payment->payment_type == App\Helpers\Constants::REDUCTION)
                                    <span class="badge badge-light-info">
                                                                {{ $payment->payment_type }} </span>
                                @endif
                            @endif

                        </td>
                        <td>
                            @if (
                                $payment->payment_type != App\Helpers\Constants::ANNULATION &&
                                    $payment->payment_type != App\Helpers\Constants::REDUCTION)
                                <a href="#"
                                   class="btn btn-light bnt-active-light-success btn-sm">{{ __('view') }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Card body-->
</div>
