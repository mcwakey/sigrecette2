<div class="d-flex flex-column">
    <div class="text-gray-800 mb-1">
        @if ( $taxpayerinfo->telephone )
            {{ $taxpayerinfo->mobilephone }} / {{ $taxpayerinfo->telephone }}
        @else
            {{ $taxpayerinfo->mobilephone }}
        @endif
</div>
    <span>{{ $taxpayerinfo->email }}</span>
</div>
<!--begin::User details-->
