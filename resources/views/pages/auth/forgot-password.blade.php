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
            {{ __('Vous avez oublié votre mot de passe ? Contactez un administrateur.') }}
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <a href="{{ route('login') }}" class="btn btn-light">{{ __('Se Connecter') }}</a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->

</x-auth-layout>
