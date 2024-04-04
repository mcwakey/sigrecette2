<!--begin::User details-->
<div class="text-gray-800 d-flex flex-column">
        <a href="{{ route('taxations.taxlabels.index') }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $taxable->tax_label->name }}
    </a>
    
    <span class="text-gray-500">{{ $taxable->name }}</span>
</div>
<!--begin::User details-->
