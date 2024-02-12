<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{-- route('settings.taxables.show', $taxable) --}}" class="text-gray-800 text-hover-primary mb-1">
        {{ $taxpayer_taxable->seize }}
    </a>
    <span>{{ $taxpayer_taxable->taxable->unit }}</span>
</div>
<!--begin::User details-->
