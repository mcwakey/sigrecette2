<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0 d-flex align-items-center">
    <div class="mt-1">
        @foreach(auth()->user()->getRoleNames() as $role)
            <span style="cursor: pointer" id="user-role" class="p-3 text-center rounded-1 fw-bolder badge-light-primary">{{ __($role) }}</span>
             @if($role =='administrateur'|| $role =='administrateur_system' && $public_ip!='null')
                <span id="server-ip" style="opacity:0;pointer-events:none;display:none;" class="p-3 text-center rounded-1 fw-bolder  badge-light-info">{{$public_ip }}</span>
            @endif
        @endforeach

        @push('scripts')
            <script async defer>
                let userRole = document.getElementById('user-role');
                let serverIp = document.getElementById('server-ip');
                let toggle = false;

                userRole?.addEventListener('click', () => {
                    if(serverIp && !toggle){
                        serverIp.style.opacity = 1;
                        serverIp.style.display = 'flex';
                        toggle = true;
                    }else{
                        serverIp.style.opacity = 0;
                        serverIp.style.display = 'none';
                        toggle = false;
                    }
                });
            </script>
        @endpush

    </div>

    <div class="app-navbar-item ms-1 ms-md-4">
		<div data-globalnotif="true" class="btn btn-icon btn-custom btn-icon-muted pulse pulse-danger btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
            <span  class="pulse-ring d-none"></span>
            {!! getIcon('notification-status', 'fs-2') !!}
        </div>
        @include('partials/menus/_notifications-menu')
        <!--end::Menu wrapper-->
    </div>

	<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
		<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
           @if(Auth::user())
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ \Auth::user()->profile_photo_url }}" class="rounded-3" alt="user" />
                @else
                    <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', Auth::user()->name) }}">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            @endif

        </div>
        @include('partials/menus/_user-account-menu')
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
    <!--begin::Header menu toggle-->
	<div class="app-navbar-item d-lg-none ms-2 me-n2" title="{{ __('show header menu') }}">
		<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">{!! getIcon('element-4', 'fs-1') !!}</div>
    </div>
    <!--end::Header menu toggle-->
</div>
<!--end::Navbar-->
