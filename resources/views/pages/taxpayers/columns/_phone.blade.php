<div class="d-flex flex-column">
    <div class="text-gray-800 mb-1">
        @if ( $taxpayer->telephone )
            {{ $taxpayer->mobilephone }} / {{ $taxpayer->telephone }}
        @else
            {{ $taxpayer->mobilephone }}
        @endif
</div>
    <span>{{ $taxpayer->email }}</span>
</div>
<!--begin::User details-->
