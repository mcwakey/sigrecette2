<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('invoices.show', $invoice) }}">
        @if($invoice->taxpayer->profile_photo_url)
            <div class="symbol-label">
                <img src="{{ $invoice->profile_photo_url }}" class="w-100"/>
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $invoice->name) }}">
                {{ substr($invoice->taxpayer->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $invoice->taxpayer->name }}
    </a>
    <span>{{ $invoice->taxpayer->mobilephone }}</span>
</div>
<!--begin::User details-->
