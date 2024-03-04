<!--begin::User details-->
<div class="text-gray-800 d-flex flex-column">
        <span>{{ $taxable->tax_label->name }}</span>
    
        <a href="{{ route('settings.taxlabels.index') }}" class="text-gray-500 text-hover-primary mb-1">
    {{ $taxable->name }}
    </a>
</div>
<!--begin::User details-->
