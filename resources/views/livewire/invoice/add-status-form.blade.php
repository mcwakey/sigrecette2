<form id="kt_modal_add_status" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_status" >
    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->

        <!--end::Label-->
        <!--begin::Input-->
        <input type="hidden" wire:model="invoice_id" name="invoice_id" class="form-control form-control-solid mb-3 mb-lg-0"
            placeholder="{{ __('invoice_id') }}" />
        <div class="">
            <label class="required fw-semibold fs-6 mb-2">{{ __('status') }}</label>
            <select class="form-select form-select-solid" wire:model="status" name="status"
                    data-placeholder="Select option" data-allow-clear="true">
                <option>{{ __('select an option') }}</option>

                @if ($status ==  App\Enums\InvoiceStatusEnums::PENDING)
                    @if($invoice->type ==  App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY)
                        @can('peut prendre en charge un avis sur titre')
                            <option value="{{App\Enums\InvoiceStatusEnums::APPROVED  }}">{{ __('APROVED') }}</option>
                        @endcan

                        @can('peut rejeter un avis sur titre (agent par délégation du receveur)')
                            <option value="{{ App\Enums\InvoiceStatusEnums::REJECTED }}">{{ __('REJECTED') }}</option>
                        @endcan

                    @else
                        @can('peut prendre en charge un avis au comptant')
                            <option value="{{App\Enums\InvoiceStatusEnums::APPROVED  }}">{{ __('APROVED') }}</option>
                        @endcan

                        @can('peut rejeter un avis au comptant')
                            <option value="{{ App\Enums\InvoiceStatusEnums::REJECTED }}">{{ __('REJECTED') }}</option>
                        @endcan
                    @endif

    
                @elseif($status == App\Enums\InvoiceStatusEnums::DRAFT)
                    @can('peut accepter un avis sur titre')
                        <option value="{{App\Enums\InvoiceStatusEnums::ACCEPTED}}">{{ __('ACCEPTED') }}</option>
                    @endcan

                    @can('peut rejeter un avis sur titre (agent par délégation de l\'ordonateur)')
                        <option value="{{App\Enums\InvoiceStatusEnums::REJECTED_BY_OR}}">{{ __('CANCELED') }}</option>
                    @endcan
                @endif
            </select>
            @error('status')<span class="text-danger  text-wrap">{{ $message }}</span>@enderror
        </div>

        @if($status ==  App\Enums\InvoiceStatusEnums::PENDING)
            <div class="">
                <label class="form-label fw-semibold fs-6 mt-2 mb-2">{{ __('reason_for_reject') }}</label>
                <textarea name="notes"  wire:model="reason_for_reject" class="form-control" rows="2" placeholder=""></textarea>
                @error('reason_for_reject')<span class="text-danger  text-wrap">{{ $message }}</span>@enderror
            </div>
        @endif



    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-sm btn-success">
            <span wire:loading.remove>{{ __('apply') }}</span>
            <span class="indicator-progress" wire:loading wire:target="submit">
                {{ __('loading') }}
            </span>
        </button>
    </div>
    <!--end::Actions-->
</form>
