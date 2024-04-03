<!--begin:: Avatar -->
@if($payment->taxpayer)
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('taxpayers.show', $payment->taxpayer) }}">
        @if($payment->taxpayer->profile_photo_url)
            <div class="symbol-label">
                <img src="{{ $payment->taxpayer->profile_photo_url }}" class="w-100"/>
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $payment->taxpayer->name) }}">
                {{ substr($payment->taxpayer->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('taxpayers.show', $payment->taxpayer) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $payment->taxpayer->name ?? "-" }}
    </a>
    <span>{{ $payment->taxpayer->mobilephone ?? "-"}}</span>
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
<!--begin::User details-->
