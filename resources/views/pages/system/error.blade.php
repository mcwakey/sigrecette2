<!--begin::Card-->
<div class="card card-flush w-lg-650px py-5">
    <!--begin::Card body-->
    <div class="card-body py-15 py-lg-20">

        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url('{{ image('auth/bg7.jpg') }}');
            }

            [data-bs-theme="dark"] body {
                background-image: url('{{ image('auth/bg7-dark.jpg') }}');
            }
        </style>
        <!--end::Page bg image-->

        <!--begin::Title-->
        <h1 class="fw-bolder fs-2qx text-gray-900 mb-4">
        {{ __('system error') }}
        </h1>
        <!--end::Title-->

        <!--begin::Text-->
        <div class="fw-semibold fs-6 text-gray-500 mb-7">
        {{ __('something went wrong please try again later') }}
        </div>
        <!--end::Text-->

        <!--begin::Illustration-->
        <div class="mb-11">
            <img src="{{ image('auth/500-error.png') }}" class="mw-100 mh-300px theme-light-show" alt=""/>
            <img src="{{ image('auth/500-error-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt=""/>
        </div>
        <!--end::Illustration-->

        <!--begin::Link-->
        <div class="mb-0">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">{{ __('return home') }}</a>
        </div>
        <!--end::Link-->

    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->


