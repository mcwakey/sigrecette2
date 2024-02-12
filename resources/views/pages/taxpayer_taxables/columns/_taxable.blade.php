<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('settings.taxables.show', $taxable) }}">
        @if($taxable->profile_photo_url)
            <div class="symbol-label">
                <img src="{{ $taxable->profile_photo_url }}" class="w-100"/>
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxable->name) }}">
                {{ substr($taxable->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('settings.taxables.show', $taxable) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $taxable->name }}
    </a>
    <span>{{ $taxable->tax_label->name }}</span>
</div>
<!--begin::User details-->
