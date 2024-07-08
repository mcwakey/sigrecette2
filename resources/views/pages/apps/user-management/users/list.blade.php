<x-default-layout>

    @section('title')
        @if(!request()->has('disable') && request()->has('type') && request()->has('type') == 'col')
            {{__('Liste des collecteurs')}}
        @elseif(request()->has('disable') && request()->has('type') && request()->has('type') == 'col')
            {{__('Liste des collecteurs désactivés')}}
        @elseif(request()->has('disable'))
        {{__('Liste des utilisateurs désactivés')}}
        @else
            {{__('Liste des utilisateurs')}}
        @endif
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{__('search')}}" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                @can('peut créer un utilisateur')
                    @if (!request()->has('disable') &&  !request()->has('type'))
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                                {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                {{ __('add_user') }}
                            </button>
                            <!--end::Add user-->
                        </div>
                        <!--end::Toolbar-->
                    @endif
                @endcan
                    @can('peut créer un utilisateur')
                        @if (!request()->has('disable') &&  request()->has('type'))
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add user-->
                                <button type="button" class="btn btn-light-success h-45px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user_collector">
                                    {!! getIcon('plus', 'fs-2', '', 'i') !!}
                                    {{ __('Ajouter un collecteur') }}
                                </button>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                        @endif
                    @endcan

                <!--begin::Modal-->
                <livewire:user.add-user-modal></livewire:user.add-user-modal>
                    <livewire:user.add-user-collector-modal></livewire:user.add-user-collector-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['users-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_user').modal('hide');
                    window.LaravelDataTables['users-table'].ajax.reload();
                });

                Livewire.on('error', function() {
                    $('#kt_modal_add_user').modal('hide');
                });
            });
        </script>
    @endpush

</x-default-layout>
