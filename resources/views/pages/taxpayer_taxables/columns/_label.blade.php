<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{-- route('settings.taxables.show', $taxable) --}}" class="text-gray-800 text-hover-primary mb-1">
        {{ $taxpayer_taxable->taxable->name }}
    </a>
    <span>{{ $taxpayer_taxable->taxable->tax_label->name }}</span>
</div>
<!--begin::User details-->
