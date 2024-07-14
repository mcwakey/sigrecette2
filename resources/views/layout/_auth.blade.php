@extends('layout.master')

@section('content')
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">

            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-30 bgi-size-cover bgi-position-center order-1 order-lg-1"
                style="background-image: url({{ image('misc/auth-bg-1.png') }})">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <!--begin::Logo-->
                    <a href="{{ route('dashboard') }}" class="mb-12">
                        <img alt="Logo" src="{{ image('logos/custom-3.png') }}" class="h-180px" />
                    </a>

                    <!--begin::Title-->
                    <h2 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">
                        {{ __('SIG-Recette') }}
                    </h2>
                    <!--end::Title-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->

            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-70 p-10 order-2 order-lg-2">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">

                    <img src="{{ image('logos/giz.png') }}" style="width:320px;height:auto;object-fit:cover;" alt="Mise en oeuvre par la GIZ" >

                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10">
                        <!--begin::Page-->
                        {{ $slot }}
                        <!--end::Page-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->

                <!--begin::Footer-->
                <div class="d-flex flex-center flex-wrap px-5">
                    <!--begin::Links-->
                    <div class="d-flex fw-semibold fs-base">
                        <a href="#" class=" px-5" target="_blank">{{ __('2024 SIG-Recette.') }}</a>
                    </div>

                    <!--end::Links-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::App-->
@endsection
