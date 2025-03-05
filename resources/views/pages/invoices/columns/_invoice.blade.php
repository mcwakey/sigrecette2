<!--begin:: Avatar -->
@if($invoice->taxpayer)
    <div class="d-flex align-items-center">
        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
            <a href="{{ route('taxpayers.show', $invoice->taxpayer) }}">
                @if($invoice->taxpayer->profile_photo_url)
                    <div class="symbol-label">
                        <img src="{{ $invoice->taxpayer->profile_photo_url }}" class="w-100"/>
                    </div>
                @else
                    <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $invoice->taxpayer->name) }}">
                        {{ substr($invoice->taxpayer->name, 0, 1) }}
                    </div>
                @endif
            </a>

        </div>
        @if($invoice->status==App\Enums\InvoiceStatusEnums::PENDING)
            @php
            $printFile = $invoice?->printFiles->first();
             @endphp
            <span class="badge {{ $invoice?->printFiles->first()!=null ? 'badge-light-success' : 'badge-light-danger' }}">
            </span>
        @endif

    </div>
<div class="d-flex flex-column">
    <a href="{{ route('taxpayers.show', $invoice->taxpayer) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $invoice->taxpayer->name ?? "-" }}
    </a>
    <span>{{ $invoice->taxpayer->mobilephone ?? "-"}}</span>
</div>
@else
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', 'z') }}">
        {{ '-' }}
    </div>
</div>

<div class="d-flex flex-column">
         -
</div>
@endif
