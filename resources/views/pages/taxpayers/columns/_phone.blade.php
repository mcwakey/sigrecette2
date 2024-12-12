<div class="d-flex flex-column">
    <div class="text-gray-800 mb-1">
        @if ( $taxpayerinfo->telephone && $taxpayerinfo->telephone )
            {{ $taxpayerinfo->mobilephone }} / {{ $taxpayerinfo->telephone }}
        @elseif($taxpayerinfo->telephone)
            {{ $taxpayerinfo->telephone }}
        @elseif($taxpayerinfo->mobilephone)
            {{ $taxpayerinfo->mobilephone }}
        @endif
</div>
    <span>{{ $taxpayerinfo->email }}</span>
</div>
<!--begin::User details-->
