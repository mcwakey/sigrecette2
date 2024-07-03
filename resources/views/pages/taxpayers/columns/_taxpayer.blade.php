@php
    $taxpayer_url=route('taxpayers.show', $taxpayerinfo->id);
   if(request()->has('rc') && request()->input('rc') =='taxation'){
       $taxpayer_url =route('invoicing.taxpayers.show',  ['taxpayer' => $taxpayerinfo->id, 'autoClick' => 'taxationbtn']);

   }
   elseif(request()->has('rc') && request()->input('rc') =='avis'){
       $taxpayer_url =route('invoicing.taxpayers.show',  ['taxpayer' => $taxpayerinfo->id, 'autoClick' => 'invoicebtng']);

   }else{
        $taxpayer_url =route('taxpayers.show');
   }

@endphp
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{$taxpayer_url }}">
        @if($taxpayerinfo->profile_photo_url)
            <div class="symbol-label">
                <img src="{{ $taxpayerinfo->profile_photo_url }}" class="w-100"/>
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $taxpayerinfo->name) }}">
                {{ substr($taxpayerinfo->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{$taxpayer_url}}" class="text-gray-800 text-hover-primary mb-1">
        {{ $taxpayerinfo->name }}
    </a>
    <span>{{ $taxpayerinfo->id }}</span>
</div>
<!--begin::User details-->
