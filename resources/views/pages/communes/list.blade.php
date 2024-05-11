<x-default-layout>
    @php
        $commune= \App\Models\Commune::getFirstCommune();
    @endphp
    @section('title')
    {{ __('info commune') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('communes.index') }}
    @endsection


    <div class="card">
        @if($commune)
            <div class="card-body">
                     <div class="card card-flush pt-3 mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="fw-bold">Commune details</h2>
                    </div>
                    <!--begin::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-light-primary"  data-kt-user-id="{{ $commune->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_commune" data-kt-action="update_row">Mettre à jour les informations de la commune</a>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="mb-10">
                        <!--begin::Title-->
                        <h5 class="mb-4"></h5>
                        <!--end::Title-->

                        <!--begin::Details-->
                        <div class="d-flex flex-wrap py-5">
                            <!--begin::Row-->
                            <div class="flex-equal me-5">
                                <!--begin::Details-->
                                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                    <!--begin::Row-->
                                    <tbody><tr>
                                        <td class="text-gray-500 min-w-175px w-175px">Nom:</td>
                                        <td class="text-gray-800 min-w-200px">
                                            <a href="" class="text-gray-800 text-hover-primary">{{$commune->title}}</a>
                                        </td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Région:</td>
                                        <td class="text-gray-800">
                                            {{ $commune->region_name}}</td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Addresse:</td>
                                        <td class="text-gray-800">
                                          {{ $commune->address}}
                                        </td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">TéléPhone:</td>
                                        <td class="text-gray-800">{{$commune->phone_number}}</td>
                                    </tr>
                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Logo:</td>
                                        <td class="text-gray-800">                     <img src="{{ $commune->getImageUrlAttributeDirect() }}" alt="Logo" style="width: 50px; height: 50px;">
                                        </td>
                                    </tr>
                                    <!--end::Row-->
                                    </tbody></table>
                                <!--end::Details-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Row-->
                            <div class="flex-equal">
                                <!--begin::Details-->
                                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                    <!--begin::Row-->
                                    <tbody><tr>
                                        <td class="text-gray-500 min-w-175px w-175px">{{ __('mayor_name') }}:</td>
                                        <td class="text-gray-800 min-w-200px">
                                            <a href="#" class="text-gray-800 text-hover-primary">{{ $commune->mayor_name }}</a>
                                        </td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">{{ __('treasury_name') }}:</td>
                                        <td class="text-gray-800">
                                            {{ $commune->treasury_name}}
                                        </td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">{{ __('treasury_address') }}:</td>
                                        <td class="text-gray-800">
                                            "{{ $commune->treasury_address }}
                                        </td>
                                    </tr>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">{{ __('treasury_rib') }}:</td>
                                        <td class="text-gray-800">{{$commune->treasury_rib}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500">Limite:</td>
                                        <td class="text-gray-800">{{$commune->latitude.",".$commune->longitude}}</td>
                                    </tr>
                                    <!--end::Row-->
                                    </tbody></table>
                                <!--end::Details-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Row-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            </div>
            <div class="d-none">{{ $dataTable->table() }}</div>
        @else
            <div class="card-body">
                <!--begin::Heading-->
                <div class="card-px text-center pt-15 pb-15">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bold mb-0">Configurer les informations de votre commune</h2>
                    <!--end::Title-->

                    <!--begin::Description-->
                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                        Cliquer sur le boutoun en dessous<br>pour accéder à la page de configuration</p>
                    <a href="#" class="btn btn-light-success er fs-6 px-8 py-4" data-bs-toggle="modal" data-bs-target="#kt_modal_add_commune">
                        {{ __('create commune') }}           </a>
                    <!--end::Action-->
                </div>
                <div class="text-center pb-15 px-5">

                    <img src="" alt="" class="mw-100 h-200px h-sm-325px">
                </div>
                <!--end::Illustration-->
            </div>
        @endif
    </div>
        <livewire:commune.add-commune-modal></livewire:commune.add-commune-modal>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>

            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_commune').modal('hide');
                    window.location.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>
