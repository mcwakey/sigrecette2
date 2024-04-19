<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" data-kt-redirect-url="{{ route('login') }}" action="{{ route('password.request') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">
            {{ __('forgetpass') }}
            </h1>
            <!--end::Title-->

            <!--begin::Link-->
            <div class="text-gray-500 fw-semibold fs-6">
            {{ __('Vous-avez oublier votre mot de passe ? contactez un administrateur.') }}
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!--begin::Input group--->
        {{-- <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="text" placeholder="{{ __('email') }}" name="email" autocomplete="off" class="form-control bg-transparent" value="demo@demo.com"/>
            <!--end::Email-->
        </div> --}}

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            {{-- <button type="button" id="kt_password_reset_submit" class="btn btn-success me-4">
                @include('partials/general/_button-indicator', ['label' => __('submit')])
            </button> --}}

            <a href="{{ route('login') }}" class="btn btn-light">{{ __('cancel') }}</a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->

</x-auth-layout>
