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

    <!-- LeaftLet -->
    <link rel="stylesheet" href="/assets/js/leaftlet/leaftlet.css">
    <script async defer src="/assets/js/leaftlet/leaftlet.js"></script>

    <!-- Marker Cluster LeaftLet -->
    <link rel="stylesheet" href="/assets/js/leaftlet/MarkerCluster.css">
    <link rel="stylesheet" href="/assets/js/leaftlet/MarkerCluster.Default.css">
    <script async defer src="/assets/js/leaftlet/markercluster.js"></script>

    amr5 graphiques -->
    <script async defer src="assets/js/am5/index.js"></script>
  <script async defer src="assets/js/am5/percent.js"></script>
    <script async defer src="assets/js/am5/xy.js"></script>
    <script async defer src="assets/js/am5/animated.js"></script>
    <script async defer src="assets/js/am5/de_de.js"></script>
    <script async defer src="assets/js/am5/germany_low.js"></script>
    <script async defer src="assets/js/am5/notosans_sc.js"></script>

<link rel="icon" type="image/png" href="assets/media/logos/logo.png" sizes="16x16 32x32" />
<link rel="stylesheet" href="/assets/css/fonts.css">

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

        window.onload = function() {
            setTimeout(function() {
                const urlParams = new URLSearchParams(window.location.search);
                let autoClickValue = urlParams.get('autoClick');
                if (autoClickValue) {
                    const button = document.getElementById(autoClickValue);
                    if (button) {
                        button.click();
                    }
                }
            }, 2000);
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
