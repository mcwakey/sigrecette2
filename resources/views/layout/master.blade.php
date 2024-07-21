<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!}>
<!--begin::Head-->

<head>
    <base href="" />
    <title>{{ config('app.name', 'SIG Recette') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="article" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css"
        integrity="sha512-6ZCLMiYwTeli2rVh3XAPxy3YoR5fVxGdH/pz+KMCzRY2M65Emgkw00Yqmhh8qLGeYQ3LbVZGdmOX9KUjSKr0TA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css"
        integrity="sha512-mQ77VzAakzdpWdgfL/lM1ksNy89uFgibRQANsNneSTMD/bj0Y/8+94XMwYhnbzx8eki2hrbPpDm0vD0CiT2lcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"
        integrity="sha512-OFs3W4DIZ5ZkrDhBFtsCP6JXtMEDGmhl0QPlmWYBJay40TT1n3gt2Xuw8Pf/iezgW9CdabjkNChRqozl/YADmg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="icon" type="image/png" href="assets/media/logos/logo.png" sizes="16x16 32x32" />

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    @foreach (getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    @foreach (getVendors('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Custom Stylesheets(optional)-->
    @foreach (getCustomCss() as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Custom Stylesheets-->

    @livewireStyles
</head>
<!--end::Head-->

<!--begin::Body-->

<body {!! printHtmlClasses('body') !!} {!! printHtmlAttributes('body') !!}>

    @include('partials/theme-mode/_init')

    @yield('content')

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    @foreach (getGlobalAssets() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used by this page)-->
    @foreach (getVendors('js') as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(optional)-->
    @foreach (getCustomJs() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Custom Javascript-->
    @stack('scripts')
    <!--end::Javascript-->


    <livewire:stock_request.add-stock-request-modal />
    <livewire:stock_transfer.add-stock-transfer-modal />
    <livewire:accountant_deposit.add-accountant-deposit-modal />
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success', (message) => {
                toastr.success(message);
            });
            Livewire.on('error', (message) => {
                toastr.error(message);
            });

            Livewire.on('swal', (message, icon, confirmButtonText) => {
                if (typeof icon === 'undefined') {
                    icon = 'success';
                }
                if (typeof confirmButtonText === 'undefined') {
                    confirmButtonText = 'Ok, got it!';
                }
                Swal.fire({
                    text: message,
                    icon: icon,
                    buttonsStyling: false,
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });

        function handleClick(buttonId) {
            //
        }

        window.onload = function() {
            setTimeout(function() {
                const urlParams = new URLSearchParams(window.location.search);
                let autoClickValue = urlParams.get('autoClick');
                if (autoClickValue) {
                    const button = document.getElementById(autoClickValue);
                    console.log(button);
                    if (button) {
                        button.click();
                    }
                }
            }, 1000);
        };



        const currentHref = window.location.href;

        if (currentHref?.match(/[?&]notif_id=([^&]*)/)) {
            let notifId = currentHref.match(/[?&]notif_id=([^&]*)/)[1];
            let request = new Request('/api/v1/user/notification/update', {
                method: "POST",
                body: JSON.stringify({
                    notif_id: notifId
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        return response.json();
                    } else {
                        throw new Error("Something went wrong on API server!");
                    }
                })
                .then((response) => {
                    localStorage.setItem('notifSize', response.size);
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    </script>

    <script src="/livewire/add-taxpayer-modal.js"></script>
    <script src="/livewire/add-invoice-modal.js"></script>
    @livewireScripts
</body>
<!--end::Body-->

</html>
