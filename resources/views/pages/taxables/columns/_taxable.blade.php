<!--begin::User details-->
<div class="text-gray-800 d-flex flex-column">
        <span class="text-gray-800  mb-1">
            {{ $taxable->name }}
   </span>
    <a href="{{ route('taxations.taxlabels.index') }}" class="text-gray-500 text-hover-primary">
        {{ $taxable->tax_label->name }}
    </a>

</div>
<!--begin::User details-->
